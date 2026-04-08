<?php

declare(strict_types=1);

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Proovit\Billing\BillingServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            BillingServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('billing.database.enabled', true);
        $app['config']->set('billing.web.enabled', true);
        $app['config']->set('billing.public_shares.enabled', true);
        $app['config']->set('billing.docs.enabled', true);
        $app['config']->set('billing.api.enabled', true);
        $app['config']->set('app.key', 'base64:'.base64_encode('01234567890123456789012345678901'));
    }
}
