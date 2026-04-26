<?php

declare(strict_types=1);

namespace Proovit\Billing;

use Dedoc\Scramble\Generator;
use Dedoc\Scramble\Scramble;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Proovit\Billing\Console\Commands\BillingInstallCommand;
use Proovit\Billing\Contracts\BillingConfigResolverInterface;
use Proovit\Billing\Contracts\BillingFeatureManagerInterface;
use Proovit\Billing\Contracts\CurrencyFormatterInterface;
use Proovit\Billing\Contracts\FacturXBuilderInterface;
use Proovit\Billing\Contracts\InvoiceCalculatorInterface;
use Proovit\Billing\Contracts\InvoiceValidatorInterface;
use Proovit\Billing\Contracts\LegalMentionResolverInterface;
use Proovit\Billing\Contracts\PdfRendererInterface;
use Proovit\Billing\Contracts\ReferenceGeneratorInterface;
use Proovit\Billing\Contracts\SequenceGeneratorInterface;
use Proovit\Billing\Contracts\TaxResolverInterface;
use Proovit\Billing\Events\CreditNoteCreated;
use Proovit\Billing\Events\InvoiceDraftCreated;
use Proovit\Billing\Events\InvoiceDraftUpdated;
use Proovit\Billing\Events\InvoiceFinalized;
use Proovit\Billing\Events\PaymentRegistered;
use Proovit\Billing\Events\ReminderRecorded;
use Proovit\Billing\Listeners\RecordBillingAuditTrail;
use Proovit\Billing\Support\ConfigBillingConfigResolver;
use Proovit\Billing\Support\ConfigBillingFeatureManager;
use Proovit\Billing\Support\ConfigInvoiceCalculator;
use Proovit\Billing\Support\ConfigInvoiceValidator;
use Proovit\Billing\Support\ConfigLegalMentionResolver;
use Proovit\Billing\Support\ConfigPdfRenderer;
use Proovit\Billing\Support\ConfigReferenceGenerator;
use Proovit\Billing\Support\ConfigSequenceGenerator;
use Proovit\Billing\Support\ConfigTaxResolver;
use Proovit\Billing\Support\DefaultCurrencyFormatter;
use Proovit\Billing\Support\NullFacturXBuilder;

final class BillingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/billing.php',
            'billing'
        );

        if (class_exists(\Barryvdh\DomPDF\ServiceProvider::class)) {
            $this->app->register(\Barryvdh\DomPDF\ServiceProvider::class);
        }

        $this->app->singleton(Billing::class);
        $this->app->singleton(BillingConfigResolverInterface::class, ConfigBillingConfigResolver::class);
        $this->app->singleton(BillingFeatureManagerInterface::class, ConfigBillingFeatureManager::class);
        $this->app->singleton(CurrencyFormatterInterface::class, DefaultCurrencyFormatter::class);
        $this->app->singleton(FacturXBuilderInterface::class, NullFacturXBuilder::class);
        $this->app->singleton(InvoiceCalculatorInterface::class, ConfigInvoiceCalculator::class);
        $this->app->singleton(InvoiceValidatorInterface::class, ConfigInvoiceValidator::class);
        $this->app->singleton(LegalMentionResolverInterface::class, ConfigLegalMentionResolver::class);
        $this->app->singleton(PdfRendererInterface::class, ConfigPdfRenderer::class);
        $this->app->singleton(ReferenceGeneratorInterface::class, ConfigReferenceGenerator::class);
        $this->app->singleton(SequenceGeneratorInterface::class, ConfigSequenceGenerator::class);
        $this->app->singleton(TaxResolverInterface::class, ConfigTaxResolver::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'billing');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'billing');
        $this->registerBillingEventListeners();

        if ($this->databaseEnabled() && (bool) config('billing.database.load_migrations', true)) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        if ($this->databaseEnabled() && (bool) config('billing.web.enabled', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }

        if ($this->databaseEnabled() && (bool) config('billing.api.enabled', false)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        }

        $this->registerDocumentationRoutes();

        if ($this->app->runningInConsole()) {
            $this->commands([
                BillingInstallCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/billing.php' => config_path('billing.php'),
            ], 'billing-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/billing'),
            ], 'billing-views');

            $this->publishes([
                __DIR__.'/../resources/lang' => lang_path('vendor/billing'),
            ], 'billing-translations');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'billing-migrations');

            $this->publishes([
                __DIR__.'/../routes' => base_path('routes/vendor/billing'),
            ], 'billing-routes');
        }
    }

    private function registerBillingEventListeners(): void
    {
        foreach ([
            InvoiceDraftCreated::class,
            InvoiceDraftUpdated::class,
            InvoiceFinalized::class,
            PaymentRegistered::class,
            CreditNoteCreated::class,
            ReminderRecorded::class,
        ] as $event) {
            Event::listen($event, RecordBillingAuditTrail::class);
        }
    }

    private function registerDocumentationRoutes(): void
    {
        if (! $this->databaseEnabled()) {
            return;
        }

        if (! (bool) config('billing.api.enabled', false)) {
            return;
        }

        if (! (bool) config('billing.docs.enabled', true)) {
            return;
        }

        if (! class_exists(Scramble::class)) {
            return;
        }

        $apiName = (string) config('billing.docs.name', 'billing');
        $config = Scramble::registerApi($apiName, [
            'api_path' => config('billing.docs.api_prefix', 'api/billing'),
            'api_domain' => config('billing.docs.domain'),
            'middleware' => (array) config('billing.docs.middleware', ['web']),
        ]);

        $config->expose(
    ui: function (Router $router, mixed $action) use ($apiName) {
        return $router->get(
            (string) config('billing.docs.ui_path', 'docs/api/billing'),
            \Proovit\Billing\Http\BillingDocsUiAction::class
        )->name($apiName.'.docs.ui');
    },
    document: function (Router $router, mixed $action) use ($apiName) {
        return $router->get(
            (string) config('billing.docs.json_path', 'docs/api/billing.json'),
            \Proovit\Billing\Http\BillingDocsJsonAction::class
        )->name($apiName.'.docs.json');
    }
);
    }

    private function databaseEnabled(): bool
    {
        return (bool) config('billing.database.enabled', config('billing.features.database', true));
    }
}
