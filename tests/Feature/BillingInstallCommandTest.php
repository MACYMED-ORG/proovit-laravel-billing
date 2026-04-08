<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Proovit\Billing\Console\Commands\BillingInstallCommand;
use Symfony\Component\Console\Output\BufferedOutput;
use Tests\TestCase;

$billingConfigBackup = null;

beforeEach(function () use (&$billingConfigBackup): void {
    $path = config_path('billing.php');
    $billingConfigBackup = File::exists($path) ? File::get($path) : null;
});

afterEach(function () use (&$billingConfigBackup): void {
    $path = config_path('billing.php');

    if ($billingConfigBackup === null) {
        if (File::exists($path)) {
            File::delete($path);
        }

        config()->set('billing', []);

        return;
    }

    File::put($path, $billingConfigBackup);
    config()->set('billing', require $path);
});

it('falls back cleanly when optional composer installs fail', function (): void {
    /** @var TestCase $this */
    Process::fake(fn () => Process::result('', 'composer failed', 1));

    $this->artisan('billing:install --database --no-publish-resources')
        ->expectsQuestion('Enable the API routes?', 'yes')
        ->expectsChoice('How should API routes be protected?', 'sanctum', ['none', 'existing middleware', 'sanctum'])
        ->expectsConfirmation('Laravel Sanctum is not installed. Install it now?', 'yes')
        ->expectsConfirmation('Enable signed public share links for invoices?', 'no')
        ->expectsConfirmation('Enable Scramble API documentation for the billing package?', 'no')
        ->expectsOutputToContain('Unable to install Laravel Sanctum automatically.')
        ->assertExitCode(0);

    $config = require config_path('billing.php');

    expect($config['api']['enabled'])->toBeTrue();
    expect($config['api']['auth_middleware'])->toBe([]);
    expect($config['public_shares']['enabled'])->toBeFalse();
    expect(File::exists(config_path('billing.php')))->toBeTrue();
});

it('returns false when sanctum cannot be installed automatically', function (): void {
    Process::fake(fn () => Process::result('', 'composer failed', 1));

    $command = app(BillingInstallCommand::class);
    $output = new BufferedOutput;

    $outputProperty = new ReflectionProperty($command, 'output');
    $outputProperty->setAccessible(true);
    $outputProperty->setValue($command, $output);

    $method = new ReflectionMethod($command, 'installSanctum');
    $method->setAccessible(true);

    expect($method->invoke($command))->toBeFalse();
});

it('disables scramble docs when automatic installation fails', function (): void {
    Process::fake(fn () => Process::result('', 'composer failed', 1));

    $command = app(BillingInstallCommand::class);
    $output = new BufferedOutput;

    $outputProperty = new ReflectionProperty($command, 'output');
    $outputProperty->setAccessible(true);
    $outputProperty->setValue($command, $output);

    $method = new ReflectionMethod($command, 'installScramble');
    $method->setAccessible(true);

    expect($method->invoke($command))->toBeFalse();

    Process::assertRanTimes('composer require --dev dedoc/scramble --no-interaction --with-all-dependencies', 1);
});
