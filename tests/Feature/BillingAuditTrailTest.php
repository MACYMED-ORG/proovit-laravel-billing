<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Proovit\Billing\Actions\Invoices\CreateDraftInvoiceAction;
use Proovit\Billing\Actions\Invoices\FinalizeInvoiceAction;
use Proovit\Billing\Actions\Invoices\RegisterPaymentAction;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceLineData;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Models\AuditLog;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\InvoiceSeries;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\UnitPrice;

uses(RefreshDatabase::class);

it('records audit logs when sensitive billing actions run', function (): void {
    $company = Company::create([
        'legal_name' => 'ProovIT SAS',
        'display_name' => 'ProovIT',
        'legal_form' => 'SAS',
        'registration_country' => 'FR',
        'head_office_address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        'billing_address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        'email' => 'billing@proovit.test',
        'phone' => '+33100000000',
        'default_currency' => 'EUR',
        'default_locale' => 'fr',
        'timezone' => 'Europe/Paris',
        'default_payment_terms' => 30,
        'invoice_prefix' => 'INV',
        'invoice_sequence_pattern' => '{prefix}-{year}{month}-{sequence}',
    ]);

    Customer::create([
        'company_id' => $company->id,
        'legal_name' => 'Client SARL',
        'full_name' => 'Client SARL',
        'reference' => 'CLI-001',
        'email' => 'client@example.test',
        'billing_address' => ['line1' => '2 avenue des Tests', 'city' => 'Lyon', 'country' => 'FR'],
    ]);

    $customer = Customer::query()->firstOrFail();

    $invoice = app(CreateDraftInvoiceAction::class)->handle(
        new InvoiceDraftData(
            seller: new CompanyIdentitySnapshot(
                legalName: 'ProovIT SAS',
                displayName: 'ProovIT',
                address: new AddressData(line1: '1 rue de Paris', city: 'Paris', country: 'FR'),
            ),
            customer: new CustomerIdentitySnapshot(
                legalName: 'Client SARL',
                billingAddress: new AddressData(line1: '2 avenue des Tests', city: 'Lyon', country: 'FR'),
                reference: 'CLI-001',
            ),
            lines: [
                new InvoiceLineData(
                    description: 'Service',
                    quantity: new LineQuantity('1'),
                    unitPrice: new UnitPrice(Money::fromDecimal('100.00', 'EUR')),
                    taxRate: Percentage::fromDecimal('20'),
                ),
            ],
            type: InvoiceType::Invoice,
        ),
        $company->id,
        $customer->id,
    );

    $series = InvoiceSeries::create([
        'company_id' => $company->id,
        'document_type' => InvoiceType::Invoice->value,
        'name' => 'Main series',
        'prefix' => 'INV',
        'pattern' => '{prefix}-{year}{month}-{sequence}',
        'padding' => 6,
        'reset_policy' => 'annual',
        'current_sequence' => 0,
        'is_default' => true,
    ]);

    app(FinalizeInvoiceAction::class)->handle($invoice, $series);
    app(RegisterPaymentAction::class)->handle($invoice->fresh(), '120.00', 'bank_transfer', $customer->id);

    expect(AuditLog::query()->count())->toBeGreaterThanOrEqual(3);

    $events = AuditLog::query()->pluck('event')->all();

    expect($events)->toContain('invoice.drafted');
    expect($events)->toContain('invoice.finalized');
    expect($events)->toContain('payment.registered');
});
