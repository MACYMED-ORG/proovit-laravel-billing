<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Seeders;

use Illuminate\Database\Seeder;
use Proovit\Billing\Actions\Invoices\GenerateInvoiceShareLinkAction;
use Proovit\Billing\Actions\Quotes\ConvertQuoteToInvoiceAction;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Enums\PaymentMethodType;
use Proovit\Billing\Enums\PaymentStatus;
use Proovit\Billing\Enums\QuoteStatus;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\CompanyBankAccount;
use Proovit\Billing\Models\CompanyEstablishment;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\CustomerAddress;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\InvoiceSeries;
use Proovit\Billing\Models\Payment;
use Proovit\Billing\Models\PaymentAllocation;
use Proovit\Billing\Models\Product;
use Proovit\Billing\Models\ProductPrice;
use Proovit\Billing\Models\Quote;
use Proovit\Billing\Models\QuoteLine;
use Proovit\Billing\Models\TaxRate;

final class BillingDemoSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::factory()->create([
            'legal_name' => 'ProovIT SAS',
            'display_name' => 'ProovIT',
            'email' => 'billing@proovit.test',
            'phone' => '+33100000000',
        ]);

        $establishment = CompanyEstablishment::factory()
            ->for($company)
            ->create([
                'name' => 'Paris HQ',
                'is_default' => true,
            ]);

        CompanyBankAccount::factory()
            ->for($company)
            ->for($establishment, 'establishment')
            ->create([
                'label' => 'Main account',
                'account_holder' => 'ProovIT SAS',
                'bank_name' => 'Demo Bank',
                'iban' => 'FR7630006000011234567890189',
                'bic' => 'AGRIFRPP',
                'is_default' => true,
            ]);

        $taxRate = TaxRate::factory()
            ->for($company)
            ->create([
                'name' => 'Standard VAT',
                'rate' => '20.0000',
                'is_default' => true,
            ]);

        $customer = Customer::factory()
            ->for($company)
            ->create([
                'legal_name' => 'Client SARL',
                'full_name' => 'Client SARL',
                'reference' => 'CLI-DEMO',
                'email' => 'client@example.test',
            ]);

        CustomerAddress::factory()
            ->for($customer)
            ->create([
                'type' => 'billing',
                'line1' => '2 avenue des Tests',
                'city' => 'Lyon',
                'postal_code' => '69000',
                'country' => 'FR',
                'is_default' => true,
            ]);

        $product = Product::factory()
            ->for($company)
            ->create([
                'sku' => 'SERV-001',
                'name' => 'Implementation service',
            ]);

        ProductPrice::factory()
            ->for($product)
            ->for($taxRate)
            ->create([
                'amount' => '500.00',
            ]);

        $series = InvoiceSeries::factory()
            ->for($company)
            ->for($establishment, 'establishment')
            ->create([
                'document_type' => InvoiceType::Invoice->value,
                'name' => 'Default invoice series',
                'prefix' => 'INV',
                'is_default' => true,
            ]);

        $quote = Quote::factory()
            ->for($company)
            ->for($customer)
            ->create([
                'status' => QuoteStatus::Sent->value,
                'seller_snapshot' => $company->toSnapshot()->toArray(),
                'customer_snapshot' => $customer->toSnapshot()->toArray(),
            ]);

        QuoteLine::factory()
            ->for($quote)
            ->for($product)
            ->for($taxRate)
            ->create([
                'description' => 'Implementation service',
                'quantity' => '1.0000',
                'unit_price' => '500.00',
                'subtotal_amount' => '500.00',
                'tax_amount' => '100.00',
                'total_amount' => '600.00',
                'sort_order' => 1,
            ]);

        $invoice = app(ConvertQuoteToInvoiceAction::class)->handle($quote);

        app(GenerateInvoiceShareLinkAction::class)->handle($invoice, now()->addDays(30), true);

        $payment = Payment::factory()
            ->for($company)
            ->for($customer)
            ->for($invoice)
            ->create([
                'status' => PaymentStatus::Paid->value,
                'method' => PaymentMethodType::BankTransfer->value,
                'amount' => '600.00',
                'paid_at' => now()->toDateString(),
            ]);

        PaymentAllocation::factory()
            ->for($payment)
            ->for($invoice)
            ->create([
                'amount' => '600.00',
            ]);
    }
}
