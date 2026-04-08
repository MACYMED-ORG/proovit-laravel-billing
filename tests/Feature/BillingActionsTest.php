<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Proovit\Billing\Actions\Invoices\CreateCreditNoteFromInvoiceAction;
use Proovit\Billing\Actions\Invoices\CreateDraftInvoiceAction;
use Proovit\Billing\Actions\Invoices\FinalizeInvoiceAction;
use Proovit\Billing\Actions\Invoices\RegisterPaymentAction;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceLineData;
use Proovit\Billing\Enums\InvoiceStatus;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Enums\PaymentMethodType;
use Proovit\Billing\Enums\PaymentStatus;
use Proovit\Billing\Enums\SequenceResetPolicy;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\InvoiceSeries;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\DiscountValue;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\SequencePattern;
use Proovit\Billing\ValueObjects\UnitPrice;

uses(RefreshDatabase::class);

function createBillingDraftFixture(): array
{
    $company = Company::create([
        'legal_name' => 'ProovIT SAS',
        'display_name' => 'ProovIT',
        'legal_form' => 'SAS',
        'registration_country' => 'FR',
        'siren' => '123456789',
        'siret' => '12345678900011',
        'vat_number' => 'FR12345678901',
        'intracommunity_vat_number' => 'FR12345678901',
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

    $customer = Customer::create([
        'company_id' => $company->id,
        'legal_name' => 'Client SARL',
        'full_name' => 'Client SARL',
        'reference' => 'CLI-001',
        'email' => 'client@example.test',
        'vat_number' => 'FR10987654321',
        'billing_address' => ['line1' => '2 avenue des Tests', 'city' => 'Lyon', 'country' => 'FR'],
    ]);

    return [$company, $customer];
}

it('creates a draft invoice with computed line amounts and totals', function (): void {
    [$company, $customer] = createBillingDraftFixture();

    $draft = new InvoiceDraftData(
        seller: new CompanyIdentitySnapshot(
            legalName: 'ProovIT SAS',
            displayName: 'ProovIT',
            legalForm: 'SAS',
            registrationCountry: 'FR',
            address: new AddressData(line1: '1 rue de Paris', city: 'Paris', country: 'FR'),
            vatNumber: null,
            contactEmail: 'billing@proovit.test',
            contactPhone: '+33100000000',
        ),
        customer: new CustomerIdentitySnapshot(
            legalName: 'Client SARL',
            billingAddress: new AddressData(line1: '2 avenue des Tests', city: 'Lyon', country: 'FR'),
            vatNumber: null,
            reference: 'CLI-001',
            email: 'client@example.test',
        ),
        lines: [
            new InvoiceLineData(
                description: 'Service A',
                quantity: new LineQuantity('2'),
                unitPrice: new UnitPrice(Money::fromDecimal('50.00', 'EUR')),
                taxRate: Percentage::fromDecimal('20'),
            ),
            new InvoiceLineData(
                description: 'Service B',
                quantity: new LineQuantity('1'),
                unitPrice: new UnitPrice(Money::fromDecimal('80.00', 'EUR')),
                taxRate: Percentage::fromDecimal('10'),
                discount: new DiscountValue(
                    percentage: Percentage::fromDecimal('0'),
                    amount: Money::fromDecimal('10.00', 'EUR'),
                ),
            ),
        ],
        currency: 'EUR',
        type: InvoiceType::Invoice,
        numbering: new SequencePattern(prefix: 'INV', suffix: null, pattern: '{prefix}-{year}{month}-{sequence}', padding: 6, reset: SequenceResetPolicy::Annual),
    );

    $invoice = app(CreateDraftInvoiceAction::class)->handle($draft, $company->id, $customer->id);

    expect($invoice->status)->toBe(InvoiceStatus::Draft);
    expect($invoice->subtotal_amount)->toBe('170.00');
    expect($invoice->tax_amount)->toBe('27.00');
    expect($invoice->total_amount)->toBe('197.00');
    expect($invoice->lines)->toHaveCount(2);
    expect($invoice->lines[0]->subtotal_amount)->toBe('100.00');
    expect($invoice->lines[0]->tax_amount)->toBe('20.00');
    expect($invoice->lines[1]->subtotal_amount)->toBe('70.00');
    expect($invoice->lines[1]->tax_amount)->toBe('7.00');
});

it('finalizes invoices and reserves immutable numbers', function (): void {
    [$company, $customer] = createBillingDraftFixture();

    $invoice = app(CreateDraftInvoiceAction::class)->handle(
        new InvoiceDraftData(
            seller: new CompanyIdentitySnapshot(legalName: 'ProovIT SAS'),
            customer: new CustomerIdentitySnapshot(legalName: 'Client SARL'),
            lines: [
                new InvoiceLineData(
                    description: 'Service',
                    quantity: new LineQuantity('1'),
                    unitPrice: new UnitPrice(Money::fromDecimal('100.00', 'EUR')),
                    taxRate: Percentage::fromDecimal('20'),
                ),
            ],
        ),
        $company->id,
        $customer->id
    );

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

    $finalized = app(FinalizeInvoiceAction::class)->handle($invoice, $series);

    expect($finalized->status)->toBe(InvoiceStatus::Finalized);
    expect($finalized->number)->toMatch('/^INV-\d{6}-000001$/');
    expect($finalized->invoice_number_reservation_id)->not()->toBeNull();
});

it('registers payments and creates credit notes from invoices', function (): void {
    [$company, $customer] = createBillingDraftFixture();

    $invoice = app(CreateDraftInvoiceAction::class)->handle(
        new InvoiceDraftData(
            seller: new CompanyIdentitySnapshot(legalName: 'ProovIT SAS'),
            customer: new CustomerIdentitySnapshot(legalName: 'Client SARL'),
            lines: [
                new InvoiceLineData(
                    description: 'Service',
                    quantity: new LineQuantity('1'),
                    unitPrice: new UnitPrice(Money::fromDecimal('100.00', 'EUR')),
                    taxRate: Percentage::fromDecimal('20'),
                ),
            ],
        ),
        $company->id,
        $customer->id
    );

    $payment = app(RegisterPaymentAction::class)->handle($invoice, '100.00', 'bank_transfer');
    $creditNote = app(CreateCreditNoteFromInvoiceAction::class)->handle($invoice->load('lines'));

    expect($payment->status)->toBe(PaymentStatus::Pending);
    expect($payment->method)->toBe(PaymentMethodType::BankTransfer);
    expect($payment->allocations)->toHaveCount(1);
    expect($payment->allocations[0]->amount)->toBe('100.00');
    expect($creditNote->invoice_id)->toBe($invoice->id);
    expect($creditNote->lines)->toHaveCount(1);
});
