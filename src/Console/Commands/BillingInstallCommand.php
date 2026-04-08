<?php

declare(strict_types=1);

namespace Proovit\Billing\Console\Commands;

use Dedoc\Scramble\Scramble;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\Process\Process;

final class BillingInstallCommand extends Command
{
    protected $signature = 'billing:install
        {--force : Overwrite published resources and config}
        {--publish-resources : Publish billing views, translations, and routes}
        {--no-publish-resources : Skip publishing billing resources}
        {--database : Enable the database-backed billing stack}
        {--no-database : Disable the database-backed billing stack}';

    protected $description = 'Install and configure the Proovit billing package';

    public function handle(): int
    {
        $this->components->info('Installing Proovit billing package');

        $databaseEnabled = $this->resolveDatabaseMode();
        $enableApi = false;
        $apiAuthMiddleware = [];
        $enablePublicShares = false;
        $enableDocs = false;

        if ($databaseEnabled) {
            $enableApi = $this->confirm('Enable the API routes?', true);

            if ($enableApi) {
                $authMode = $this->choice(
                    'How should API routes be protected?',
                    [
                        'none',
                        'existing middleware',
                        'sanctum',
                    ],
                    class_exists(Sanctum::class) ? 'sanctum' : 'none'
                );

                if ($authMode === 'existing middleware') {
                    $value = (string) $this->ask(
                        'Enter the middleware stack separated by commas',
                        class_exists(Sanctum::class) ? 'auth:sanctum' : ''
                    );

                    $apiAuthMiddleware = array_values(array_filter(array_map('trim', explode(',', $value))));
                } elseif ($authMode === 'sanctum') {
                    if (! class_exists(Sanctum::class)) {
                        if ($this->confirm('Laravel Sanctum is not installed. Install it now?', true)) {
                            $this->installSanctum();
                        } else {
                            $this->warn('Sanctum was not installed. Leaving the API middleware empty.');
                        }
                    }

                    $apiAuthMiddleware = ['auth:sanctum'];
                }
            }

            $enablePublicShares = $this->confirm('Enable signed public share links for invoices?', true);
            $enableDocs = $this->confirm('Enable Scramble API documentation for the billing package?', true);

            if ($enableDocs && ! class_exists(Scramble::class)) {
                if ($this->confirm('Scramble is not installed. Install it now?', true)) {
                    $this->installScramble();
                } else {
                    $this->warn('Scramble was not installed. Billing API docs will remain disabled.');
                    $enableDocs = false;
                }
            }
        } else {
            $this->warn('Database-backed features are disabled. API, public share links, and Scramble docs are turned off.');
        }

        $publishResources = $this->resolvePublishResourcesMode();
        $this->writeConfig([
            'features.database' => $databaseEnabled,
            'features.api' => $enableApi,
            'features.web' => $databaseEnabled,
            'features.views' => true,
            'features.pdf' => true,
            'features.factur_x' => true,
            'api.enabled' => $enableApi,
            'api.auth_middleware' => $apiAuthMiddleware,
            'public_shares.enabled' => $enablePublicShares,
            'docs.enabled' => $enableDocs,
            'database.enabled' => $databaseEnabled,
            'database.load_migrations' => $databaseEnabled,
            'web.enabled' => $databaseEnabled,
            'reminders.enabled' => false,
            'audit.enabled' => $databaseEnabled,
            'views.enabled' => true,
            'pdf.enabled' => true,
            'factur_x.enabled' => true,
        ]);

        if ($publishResources) {
            $this->publishResources();
        } else {
            $this->line('Skipping resource publication. Package migrations are still loaded directly from the package.');
        }

        $this->components->info('Billing package installed.');
        $this->line('Next steps:');
        $this->line('- Review config/billing.php');
        if ($databaseEnabled) {
            $this->line('- Run your migrations');
            $this->line('- If you chose Sanctum, keep your application guard configuration aligned with auth:sanctum');
            $this->line('- If you enabled Scramble, visit docs/api/billing or the configured path');
        } else {
            $this->line('- Use the PDF DTO workflow to generate documents without the database');
        }

        return self::SUCCESS;
    }

    private function publishResources(): void
    {
        foreach (['billing-views', 'billing-translations', 'billing-routes'] as $tag) {
            Artisan::call('vendor:publish', [
                '--tag' => $tag,
                '--force' => (bool) $this->option('force'),
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function writeConfig(array $overrides): void
    {
        $config = config('billing', []);

        foreach ($overrides as $path => $value) {
            Arr::set($config, $path, $value);
        }

        $contents = "<?php\n\nreturn ".var_export($config, true).";\n";

        File::put(config_path('billing.php'), $contents);
    }

    private function installSanctum(): void
    {
        $process = Process::fromShellCommandline(
            'composer require laravel/sanctum --no-interaction --with-all-dependencies',
            base_path()
        );

        $process->setTimeout(null);
        $process->run(function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });

        if (! $process->isSuccessful()) {
            $this->error('Unable to install Laravel Sanctum automatically.');
            $this->line('Run `composer require laravel/sanctum` manually, then rerun `php artisan billing:install`.');
        }
    }

    private function installScramble(): void
    {
        $process = Process::fromShellCommandline(
            'composer require --dev dedoc/scramble --no-interaction --with-all-dependencies',
            base_path()
        );

        $process->setTimeout(null);
        $process->run(function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });

        if (! $process->isSuccessful()) {
            $this->error('Unable to install Scramble automatically.');
            $this->line('Run `composer require --dev dedoc/scramble` manually, then rerun `php artisan billing:install`.');
        }
    }

    private function resolveDatabaseMode(): bool
    {
        if ((bool) $this->option('no-database')) {
            return false;
        }

        if ((bool) $this->option('database')) {
            return true;
        }

        return $this->confirm('Enable the database-backed billing stack?', true);
    }

    private function resolvePublishResourcesMode(): bool
    {
        if ((bool) $this->option('no-publish-resources')) {
            return false;
        }

        if ((bool) $this->option('publish-resources')) {
            return true;
        }

        return $this->confirm('Publish billing views, translations, and routes now?', true);
    }
}
