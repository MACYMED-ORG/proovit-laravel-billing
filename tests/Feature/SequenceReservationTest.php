<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Proovit\Billing\Actions\Invoices\ReserveInvoiceNumberAction;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Enums\SequenceResetPolicy;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\InvoiceSeries;

uses(RefreshDatabase::class);

it('reserves invoice numbers atomically and increments the series sequence', function (): void {
    $company = Company::create([
        'legal_name' => 'ProovIT SAS',
        'registration_country' => 'FR',
        'default_currency' => 'EUR',
        'default_locale' => 'fr',
        'timezone' => 'Europe/Paris',
        'default_payment_terms' => 30,
        'invoice_prefix' => 'INV',
        'invoice_sequence_pattern' => '{prefix}-{year}{month}-{sequence}',
    ]);

    $series = InvoiceSeries::create([
        'company_id' => $company->id,
        'document_type' => InvoiceType::Invoice->value,
        'name' => 'Default invoices',
        'prefix' => 'INV',
        'suffix' => null,
        'pattern' => '{prefix}-{year}{month}-{sequence}',
        'padding' => 6,
        'reset_policy' => SequenceResetPolicy::Annual->value,
        'current_sequence' => 0,
        'is_default' => true,
    ]);

    $invoice = Invoice::create([
        'company_id' => $company->id,
        'document_type' => InvoiceType::Invoice->value,
        'status' => 'draft',
        'currency' => 'EUR',
        'subtotal_amount' => '0.00',
        'tax_amount' => '0.00',
        'total_amount' => '0.00',
    ]);

    $reservation = app(ReserveInvoiceNumberAction::class)->handle($series, $invoice->id, InvoiceType::Invoice->value);

    expect($reservation->sequence)->toBe(1);
    expect($reservation->number)->toMatch('/^INV-\d{6}-000001$/');
    expect($series->refresh()->current_sequence)->toBe(1);
});
