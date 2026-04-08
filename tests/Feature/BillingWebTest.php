<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Proovit\Billing\Actions\Invoices\FinalizeInvoiceAction;
use Proovit\Billing\Actions\Invoices\GenerateInvoiceShareLinkAction;
use Proovit\Billing\Actions\Quotes\ConvertQuoteToInvoiceAction;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\InvoiceSeries;
use Proovit\Billing\Models\Quote;
use Proovit\Billing\Models\QuoteLine;
use Tests\TestCase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config()->set('billing.public_shares.enabled', true);
});

function billingWebFixture(): Invoice
{
    $company = Company::factory()->create([
        'legal_name' => 'ProovIT SAS',
        'display_name' => 'ProovIT',
        'email' => 'billing@proovit.test',
    ]);

    $customer = Customer::factory()->for($company)->create([
        'legal_name' => 'Client SARL',
        'full_name' => 'Client SARL',
        'reference' => 'CLI-001',
    ]);

    $quote = Quote::factory()->for($company)->for($customer)->create([
        'seller_snapshot' => $company->toSnapshot()->toArray(),
        'customer_snapshot' => $customer->toSnapshot()->toArray(),
    ]);

    QuoteLine::factory()->for($quote)->create([
        'description' => 'Service',
        'quantity' => '1.0000',
        'unit_price' => '100.00',
        'subtotal_amount' => '100.00',
        'tax_amount' => '20.00',
        'total_amount' => '120.00',
        'sort_order' => 1,
    ]);

    $series = InvoiceSeries::factory()->for($company)->create([
        'name' => 'Default series',
        'prefix' => 'INV',
        'is_default' => true,
    ]);

    $invoice = app(ConvertQuoteToInvoiceAction::class)->handle($quote);
    app(FinalizeInvoiceAction::class)->handle($invoice, $series);

    return $invoice->fresh();
}

it('renders invoice preview and print views', function (): void {
    /** @var TestCase $this */
    app()->setLocale('fr');
    $invoice = billingWebFixture();

    $this->get(route('billing.invoices.preview', $invoice))
        ->assertOk()
        ->assertSee('Aperçu web', false)
        ->assertSee('ProovIT SAS', false);

    $this->get(route('billing.invoices.print', $invoice))
        ->assertOk()
        ->assertSee('window.print()', false);
});

it('renders shared invoice views from public links', function (): void {
    /** @var TestCase $this */
    app()->setLocale('fr');
    $invoice = billingWebFixture();
    $shareUrl = app(GenerateInvoiceShareLinkAction::class)->handle($invoice);

    $this->get($shareUrl)
        ->assertOk()
        ->assertSee('Aperçu web', false)
        ->assertSee('ProovIT SAS', false);
});
