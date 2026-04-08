<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Proovit\Billing\Database\Seeders\BillingDemoSeeder;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\Payment;
use Proovit\Billing\Models\Quote;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config()->set('billing.public_shares.enabled', true);
});

it('seeds a realistic billing demo dataset', function (): void {
    app(BillingDemoSeeder::class)->run();

    expect(Company::query()->count())->toBeGreaterThan(0);
    expect(Customer::query()->count())->toBeGreaterThan(0);
    expect(Quote::query()->count())->toBeGreaterThan(0);
    expect(Invoice::query()->count())->toBeGreaterThan(0);
    expect(Payment::query()->count())->toBeGreaterThan(0);
});
