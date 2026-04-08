<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Proovit\Billing\Billing;
use Proovit\Billing\Contracts\BillingConfigResolverInterface;
use Proovit\Billing\Contracts\InvoiceCalculatorInterface;
use Proovit\Billing\Support\ConfigBillingConfigResolver;
use Proovit\Billing\Support\ConfigInvoiceCalculator;

beforeEach(function (): void {
    config()->set('billing.features.api', false);
});

it('loads the billing configuration and container binding', function (): void {
    expect(config('billing.features.api'))->toBeFalse();
    expect(config('billing.numbering.prefix'))->toBe('INV');
    expect(config('billing.company_defaults.default_currency'))->toBe('EUR');
    expect(view()->exists('billing::welcome'))->toBeTrue();

    $billing = app(Billing::class);

    expect($billing->featureEnabled('web'))->toBeTrue();
    expect($billing->featureEnabled('api'))->toBeFalse();
    expect($billing->config('companies.default_currency'))->toBe('EUR');
});

it('binds the main contracts to default implementations', function (): void {
    expect(app(BillingConfigResolverInterface::class))->toBeInstanceOf(ConfigBillingConfigResolver::class);
    expect(app(InvoiceCalculatorInterface::class))->toBeInstanceOf(ConfigInvoiceCalculator::class);
});

it('ships the core migration files and schema documentation', function (): void {
    $packageRoot = dirname(__DIR__, 2);
    $migrationFiles = File::files($packageRoot.'/database/migrations');

    expect($migrationFiles)->not()->toBeEmpty();
    expect(File::exists($packageRoot.'/docs/database-schema.md'))->toBeTrue();
});
