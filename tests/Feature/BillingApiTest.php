<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Proovit\Billing\Actions\Invoices\CreateDraftInvoiceAction;
use Proovit\Billing\Actions\Invoices\FinalizeInvoiceAction;
use Proovit\Billing\Actions\Invoices\GenerateInvoiceShareLinkAction;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceLineData;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\InvoiceSeries;
use Proovit\Billing\Models\Quote;
use Proovit\Billing\Models\QuoteLine;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\UnitPrice;

uses(RefreshDatabase::class);

function billingApiFixture(): array
{
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

    $customer = Customer::create([
        'company_id' => $company->id,
        'legal_name' => 'Client SARL',
        'full_name' => 'Client SARL',
        'reference' => 'CLI-001',
        'email' => 'client@example.test',
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

    return [$company, $customer, $invoice->fresh()];
}

function billingQuoteFixture(Company $company, Customer $customer): Quote
{
    $quote = Quote::create([
        'company_id' => $company->id,
        'customer_id' => $customer->id,
        'status' => 'draft',
        'seller_snapshot' => [
            'legal_name' => $company->legal_name,
            'display_name' => $company->display_name,
            'address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        ],
        'customer_snapshot' => [
            'legal_name' => $customer->legal_name,
            'reference' => $customer->reference,
            'billing_address' => ['line1' => '2 avenue des Tests', 'city' => 'Lyon', 'country' => 'FR'],
        ],
        'subtotal_amount' => '100.00',
        'tax_amount' => '20.00',
        'total_amount' => '120.00',
    ]);

    QuoteLine::create([
        'quote_id' => $quote->id,
        'description' => 'Service',
        'quantity' => '1',
        'unit_price' => '100.00',
        'discount_amount' => '0.00',
        'tax_rate' => '20',
        'subtotal_amount' => '100.00',
        'tax_amount' => '20.00',
        'total_amount' => '120.00',
        'sort_order' => 1,
    ]);

    return $quote->fresh('lines');
}

it('exposes the api status endpoint and creates invoices through form requests', function (): void {
    [$company, $customer] = billingApiFixture();

    $this->getJson(route('billing.api.status'))
        ->assertOk()
        ->assertJsonPath('data.loaded', true);

    $this->postJson(route('billing.api.invoices.store'), [
        'seller' => [
            'legal_name' => $company->legal_name,
            'display_name' => $company->display_name,
            'address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        ],
        'customer' => [
            'legal_name' => $customer->legal_name,
            'reference' => $customer->reference,
            'billing_address' => ['line1' => '2 avenue des Tests', 'city' => 'Lyon', 'country' => 'FR'],
        ],
        'lines' => [
            [
                'description' => 'Service',
                'quantity' => '1',
                'unit_price' => '100.00',
                'tax_rate' => '20',
            ],
        ],
        'currency' => 'EUR',
        'type' => 'invoice',
        'company_uuid_identifier' => $company->uuid_identifier,
        'customer_uuid_identifier' => $customer->uuid_identifier,
    ])
        ->assertCreated()
        ->assertJsonPath('data.document_type', 'invoice')
        ->assertJsonPath('data.customer.reference', 'CLI-001');
});

it('uses api form requests for nested invoice actions', function (): void {
    [$company, $customer, $invoice] = billingApiFixture();
    $quote = billingQuoteFixture($company, $customer);

    expect(route('billing.api.invoices.show', $invoice))->toContain($invoice->uuid_identifier);
    expect(route('billing.api.quotes.convert', $quote))->toContain($quote->uuid_identifier);

    $this->postJson(route('billing.api.invoices.finalize', $invoice), [
        'invoice_series_uuid_identifier' => InvoiceSeries::query()->where('company_id', $company->id)->firstOrFail()->uuid_identifier,
    ])->assertOk();

    $this->postJson(route('billing.api.invoices.payments.store', $invoice), [
        'amount' => '120.00',
        'method' => 'bank_transfer',
        'customer_uuid_identifier' => $customer->uuid_identifier,
    ])->assertCreated()
        ->assertJsonPath('data.method', 'bank_transfer');

    $this->postJson(route('billing.api.invoices.store'), [
        'customer' => [],
    ])->assertStatus(422);

    $this->postJson(route('billing.api.quotes.convert', $quote))
        ->assertCreated()
        ->assertJsonPath('data.quote.uuid_identifier', $quote->uuid_identifier)
        ->assertJsonPath('data.document_type', 'invoice');
});

it('supports customer quote draft and public share flows through uuid routes', function (): void {
    [$company, $customer, $invoice] = billingApiFixture();

    $customerResponse = $this->postJson(route('billing.api.customers.store'), [
        'company_uuid_identifier' => $company->uuid_identifier,
        'legal_name' => 'Client Public',
        'full_name' => 'Client Public',
        'reference' => 'CLI-PUBLIC',
        'email' => 'public@example.test',
        'billing_address' => ['line1' => '10 rue des Clients', 'city' => 'Paris', 'country' => 'FR'],
    ])->assertCreated();

    $customerUuid = $customerResponse->json('data.uuid_identifier');

    $this->patchJson(route('billing.api.customers.update', $customerUuid), [
        'full_name' => 'Client Public Updated',
        'company_uuid_identifier' => $company->uuid_identifier,
    ])->assertOk()
        ->assertJsonPath('data.full_name', 'Client Public Updated');

    $quoteResponse = $this->postJson(route('billing.api.quotes.store'), [
        'seller' => [
            'legal_name' => $company->legal_name,
            'display_name' => $company->display_name,
            'address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        ],
        'customer' => [
            'legal_name' => $customer->legal_name,
            'reference' => $customer->reference,
            'billing_address' => ['line1' => '2 avenue des Tests', 'city' => 'Lyon', 'country' => 'FR'],
        ],
        'lines' => [
            [
                'description' => 'Quote service',
                'quantity' => '2',
                'unit_price' => '50.00',
                'tax_rate' => '20',
            ],
        ],
        'currency' => 'EUR',
        'type' => 'quote',
        'company_uuid_identifier' => $company->uuid_identifier,
        'customer_uuid_identifier' => $customer->uuid_identifier,
    ])->assertCreated();

    $quoteUuid = $quoteResponse->json('data.uuid_identifier');

    $this->patchJson(route('billing.api.quotes.update', $quoteUuid), [
        'seller' => [
            'legal_name' => $company->legal_name,
            'display_name' => $company->display_name,
            'address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        ],
        'customer' => [
            'legal_name' => $customer->legal_name,
            'reference' => $customer->reference,
            'billing_address' => ['line1' => '2 avenue des Tests', 'city' => 'Lyon', 'country' => 'FR'],
        ],
        'lines' => [
            [
                'description' => 'Updated quote service',
                'quantity' => '1',
                'unit_price' => '75.00',
                'tax_rate' => '20',
            ],
        ],
        'currency' => 'EUR',
        'type' => 'quote',
        'company_uuid_identifier' => $company->uuid_identifier,
        'customer_uuid_identifier' => $customer->uuid_identifier,
    ])->assertOk()
        ->assertJsonPath('data.lines.0.description', 'Updated quote service');

    $this->patchJson(route('billing.api.invoices.update', $invoice), [
        'seller' => [
            'legal_name' => $company->legal_name,
            'display_name' => $company->display_name,
            'address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        ],
        'customer' => [
            'legal_name' => $customer->legal_name,
            'reference' => $customer->reference,
            'billing_address' => ['line1' => '2 avenue des Tests', 'city' => 'Lyon', 'country' => 'FR'],
        ],
        'lines' => [
            [
                'description' => 'Updated invoice service',
                'quantity' => '3',
                'unit_price' => '40.00',
                'tax_rate' => '20',
            ],
        ],
        'currency' => 'EUR',
        'type' => 'invoice',
        'company_uuid_identifier' => $company->uuid_identifier,
        'customer_uuid_identifier' => $customer->uuid_identifier,
    ])->assertOk()
        ->assertJsonPath('data.lines.0.description', 'Updated invoice service');

    $shareUrl = app(GenerateInvoiceShareLinkAction::class)->handle($invoice->fresh());

    $this->get($shareUrl)->assertOk()->assertSee('ProovIT SAS');

    $this->postJson(route('billing.api.invoices.share-link', $invoice->fresh()->uuid_identifier))
        ->assertOk()
        ->assertJsonPath('data.public_share_url', $shareUrl);
});
