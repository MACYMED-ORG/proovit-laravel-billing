<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Proovit\Billing\Database\Seeders\BillingDemoSeeder;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\CreditNote;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\Payment;
use Proovit\Billing\Models\Quote;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config()->set('billing.public_shares.enabled', true);
    Storage::fake('public');
});

it('seeds a realistic billing demo dataset', function (): void {
    app(BillingDemoSeeder::class)->run();

    expect(Company::query()->count())->toBeGreaterThan(0);
    expect(Customer::query()->count())->toBeGreaterThan(0);
    expect(Quote::query()->count())->toBeGreaterThan(0);
    expect(Invoice::query()->count())->toBeGreaterThan(0);
    expect(Payment::query()->count())->toBeGreaterThan(0);

    $invoice = Invoice::query()
        ->where('document_type', InvoiceType::Invoice->value)
        ->whereNotNull('public_share_token')
        ->firstOrFail();

    expect($invoice->uuid_identifier)->not()->toBeEmpty();
    expect($invoice->latestPdfDocumentRender())->not()->toBeNull();
    expect(Storage::disk('public')->exists((string) $invoice->latestPdfDocumentRenderPath()))->toBeTrue();

    $creditNote = CreditNote::query()->firstOrFail();

    expect($creditNote->lines)->not()->toBeEmpty();
    expect($creditNote->lines->first()->getAttribute('product_id'))->not()->toBeNull();
    expect($creditNote->lines->first()->getAttribute('tax_rate_id'))->not()->toBeNull();
});
