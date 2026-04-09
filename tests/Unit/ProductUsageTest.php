<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Proovit\Billing\Actions\Invoices\CreateDraftInvoiceAction;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceLineData;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\Product;
use Proovit\Billing\Models\TaxRate;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\UnitPrice;

uses(RefreshDatabase::class);

it('marks products as referenced once they are used in billing documents', function (): void {
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

    $taxRate = TaxRate::create([
        'company_id' => $company->id,
        'name' => 'Standard VAT',
        'rate' => '20.0000',
        'country' => 'FR',
        'is_default' => true,
    ]);

    $product = Product::create([
        'company_id' => $company->id,
        'sku' => 'SERV-001',
        'name' => 'Implementation service',
        'description' => 'Initial project setup and implementation work.',
    ]);

    expect($product->isReferencedInDocuments())->toBeFalse();
    expect($product->canEditCatalog())->toBeTrue();
    expect($product->canManagePrices())->toBeTrue();

    app(CreateDraftInvoiceAction::class)->handle(
        new InvoiceDraftData(
            seller: new CompanyIdentitySnapshot(legalName: 'ProovIT SAS', address: new AddressData(line1: '1 rue de Paris', city: 'Paris', country: 'FR')),
            customer: new CustomerIdentitySnapshot(legalName: 'Client SARL', billingAddress: new AddressData(line1: '2 avenue des Tests', city: 'Lyon', country: 'FR')),
            lines: [
                new InvoiceLineData(
                    description: 'Implementation service',
                    quantity: new LineQuantity('1'),
                    unitPrice: new UnitPrice(Money::fromDecimal('500.00', 'EUR')),
                    taxRate: Percentage::fromDecimal('20'),
                ),
            ],
            type: InvoiceType::Invoice,
        ),
        $company->id,
        $customer->id
    )->lines->first()->forceFill([
        'product_id' => $product->id,
        'tax_rate_id' => $taxRate->id,
    ])->save();

    $product->refresh();

    expect($product->isReferencedInDocuments())->toBeTrue();
    expect($product->canEditCatalog())->toBeFalse();
    expect($product->canManagePrices())->toBeFalse();
});
