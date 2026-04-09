<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Proovit\Billing\Actions\Invoices\CreateDraftInvoiceAction;
use Proovit\Billing\Actions\Invoices\EnsureInvoicePdfStoredAction;
use Proovit\Billing\Actions\Invoices\GenerateInvoicePdfAction;
use Proovit\Billing\Actions\Invoices\StoreInvoicePdfAction;
use Proovit\Billing\Contracts\InvoiceCalculatorInterface;
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceLineData;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\DocumentRender;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\UnitPrice;

uses(RefreshDatabase::class);

it('renders a billing pdf view through the configured renderer', function (): void {
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
        $customer->id
    );

    $rendered = app(GenerateInvoicePdfAction::class)->handle($invoice);

    expect($rendered)->toContain('PDF:billing::pdf.invoice');
    expect($rendered)->toContain('Facture');
    expect($rendered)->toContain('ProovIT SAS');
    expect($rendered)->toContain('Client SARL');
    expect($rendered)->toContain('Sous-total');
    expect($rendered)->toContain('Mentions légales');
});

it('renders and stores a billing pdf from a dto without requiring the model graph', function (): void {
    Storage::fake('public');

    $draft = new InvoiceDraftData(
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
    );

    $totals = app(InvoiceCalculatorInterface::class)->calculate($draft);

    $document = InvoiceDocumentData::fromDraft($draft, $totals, [
        'number' => 'INV-2026-0001',
        'issued_at' => now(),
        'due_at' => now()->addDays(30),
        'locale' => 'fr',
    ]);

    $rendered = app(GenerateInvoicePdfAction::class)->handle($document);

    expect($rendered)->toContain('PDF:billing::pdf.invoice');
    expect($rendered)->toContain('INV-2026-0001');

    $path = app(StoreInvoicePdfAction::class)->handle($document);

    Storage::disk('public')->assertExists($path);
    expect($path)->toStartWith('billing/invoices/');
});

it('regenerates a stored invoice pdf when the file is missing', function (): void {
    Storage::fake('public');

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
        $customer->id
    );

    $render = app(EnsureInvoicePdfStoredAction::class)->handle($invoice, 'invoice-demo.pdf');

    expect($render->getAttribute('path'))->toBe('billing/invoices/invoice-demo.pdf');
    expect(DocumentRender::query()->count())->toBe(1);
    Storage::disk('public')->assertExists((string) $render->getAttribute('path'));

    Storage::disk('public')->delete((string) $render->getAttribute('path'));

    $regenerated = app(EnsureInvoicePdfStoredAction::class)->handle($invoice, 'invoice-demo.pdf');

    expect($regenerated->getAttribute('path'))->toBe('billing/invoices/invoice-demo.pdf');
    expect(DocumentRender::query()->count())->toBe(2);
    Storage::disk('public')->assertExists((string) $regenerated->getAttribute('path'));
});
