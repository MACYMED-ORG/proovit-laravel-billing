<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Seeders;

use Illuminate\Database\Seeder;
use Proovit\Billing\Actions\Invoices\CreateCreditNoteFromInvoiceAction;
use Proovit\Billing\Actions\Invoices\CreateDraftInvoiceAction;
use Proovit\Billing\Actions\Invoices\EnsureInvoicePdfStoredAction;
use Proovit\Billing\Actions\Invoices\GenerateInvoiceShareLinkAction;
use Proovit\Billing\Actions\Quotes\ConvertQuoteToInvoiceAction;
use Proovit\Billing\Actions\Quotes\CreateQuoteAction;
use Proovit\Billing\Builders\Documents\InvoiceDocumentBuilder;
use Proovit\Billing\Enums\EInvoiceFormat;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Enums\PaymentMethodType;
use Proovit\Billing\Enums\PaymentStatus;
use Proovit\Billing\Enums\QuoteStatus;
use Proovit\Billing\Enums\ReminderChannel;
use Proovit\Billing\Models\AuditLog;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\CompanyBankAccount;
use Proovit\Billing\Models\CompanyEstablishment;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\CustomerAddress;
use Proovit\Billing\Models\EInvoiceExport;
use Proovit\Billing\Models\InvoiceSeries;
use Proovit\Billing\Models\Payment;
use Proovit\Billing\Models\PaymentAllocation;
use Proovit\Billing\Models\Product;
use Proovit\Billing\Models\ProductPrice;
use Proovit\Billing\Models\Reminder;
use Proovit\Billing\Models\TaxRate;

final class BillingDemoSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::factory()->create([
            'legal_name' => 'ProovIT SAS',
            'display_name' => 'ProovIT',
            'legal_form' => 'SAS',
            'registration_country' => 'FR',
            'email' => 'billing@proovit.test',
            'phone' => '+33100000000',
            'default_currency' => 'EUR',
            'default_locale' => 'fr',
            'timezone' => 'Europe/Paris',
            'invoice_prefix' => 'INV',
            'invoice_sequence_pattern' => '{prefix}-{year}{month}-{sequence}',
        ]);

        $headOffice = CompanyEstablishment::factory()
            ->for($company)
            ->create([
                'name' => 'Paris HQ',
                'code' => 'PAR-HQ',
                'email' => 'hq@proovit.test',
                'phone' => '+33100000001',
                'is_default' => true,
                'address' => [
                    'line1' => '12 rue des Factures',
                    'postal_code' => '75001',
                    'city' => 'Paris',
                    'country' => 'FR',
                ],
            ]);

        $satelliteOffice = CompanyEstablishment::factory()
            ->for($company)
            ->create([
                'name' => 'Lyon Office',
                'code' => 'LYO-01',
                'email' => 'lyon@proovit.test',
                'phone' => '+33400000000',
                'is_default' => false,
                'address' => [
                    'line1' => '4 place du Billing',
                    'postal_code' => '69000',
                    'city' => 'Lyon',
                    'country' => 'FR',
                ],
            ]);

        CompanyBankAccount::factory()
            ->for($company)
            ->for($headOffice, 'establishment')
            ->create([
                'label' => 'Main account',
                'account_holder' => 'ProovIT SAS',
                'bank_name' => 'Demo Bank',
                'iban' => 'FR7630006000011234567890189',
                'bic' => 'AGRIFRPP',
                'is_default' => true,
            ]);

        CompanyBankAccount::factory()
            ->for($company)
            ->for($satelliteOffice, 'establishment')
            ->create([
                'label' => 'Secondary account',
                'account_holder' => 'ProovIT SAS',
                'bank_name' => 'Demo Bank 2',
                'iban' => 'FR7630006000011234567890190',
                'bic' => 'AGRIFRPP',
                'is_default' => false,
            ]);

        $vatStandard = TaxRate::factory()
            ->for($company)
            ->create([
                'name' => 'Standard VAT',
                'rate' => '20.0000',
                'country' => 'FR',
                'is_default' => true,
            ]);

        $vatReduced = TaxRate::factory()
            ->for($company)
            ->create([
                'name' => 'Reduced VAT',
                'rate' => '5.5000',
                'country' => 'FR',
                'is_default' => false,
            ]);

        $primaryCustomer = Customer::factory()
            ->for($company)
            ->create([
                'legal_name' => 'Client SARL',
                'full_name' => 'Client SARL',
                'reference' => 'CLI-DEMO',
                'email' => 'client@example.test',
                'phone' => '+33999000001',
                'vat_number' => 'FR12345678901',
            ]);

        CustomerAddress::factory()
            ->for($primaryCustomer)
            ->create([
                'type' => 'billing',
                'line1' => '2 avenue des Tests',
                'city' => 'Lyon',
                'postal_code' => '69000',
                'country' => 'FR',
                'is_default' => true,
            ]);

        CustomerAddress::factory()
            ->for($primaryCustomer)
            ->create([
                'type' => 'shipping',
                'line1' => '8 rue des Livraisons',
                'city' => 'Villeurbanne',
                'postal_code' => '69100',
                'country' => 'FR',
                'is_default' => false,
            ]);

        $secondaryCustomer = Customer::factory()
            ->for($company)
            ->create([
                'legal_name' => 'Agence Beta',
                'full_name' => 'Agence Beta',
                'reference' => 'AGB-DEMO',
                'email' => 'beta@example.test',
                'phone' => '+33999000002',
                'vat_number' => 'FR10987654321',
            ]);

        CustomerAddress::factory()
            ->for($secondaryCustomer)
            ->create([
                'type' => 'billing',
                'line1' => '18 boulevard du Panel',
                'city' => 'Marseille',
                'postal_code' => '13000',
                'country' => 'FR',
                'is_default' => true,
            ]);

        $implementationService = Product::factory()
            ->for($company)
            ->create([
                'sku' => 'SERV-001',
                'name' => 'Implementation service',
                'description' => 'Initial project setup and implementation work.',
            ]);

        $maintenanceService = Product::factory()
            ->for($company)
            ->create([
                'sku' => 'SERV-002',
                'name' => 'Maintenance retainer',
                'description' => 'Monthly support and maintenance retainer.',
            ]);

        $hardwareBundle = Product::factory()
            ->for($company)
            ->create([
                'sku' => 'HW-001',
                'name' => 'Hardware bundle',
                'description' => 'Equipment bundle for the initial delivery.',
            ]);

        ProductPrice::factory()
            ->for($implementationService)
            ->for($vatStandard)
            ->create([
                'amount' => '500.00',
            ]);

        ProductPrice::factory()
            ->for($maintenanceService)
            ->for($vatReduced)
            ->create([
                'amount' => '180.00',
            ]);

        ProductPrice::factory()
            ->for($hardwareBundle)
            ->for($vatStandard)
            ->create([
                'amount' => '250.00',
            ]);

        $invoiceSeries = InvoiceSeries::factory()
            ->for($company)
            ->for($headOffice, 'establishment')
            ->create([
                'document_type' => InvoiceType::Invoice->value,
                'name' => 'Invoices',
                'prefix' => 'INV',
                'is_default' => true,
            ]);

        InvoiceSeries::factory()
            ->for($company)
            ->for($headOffice, 'establishment')
            ->create([
                'document_type' => InvoiceType::Quote->value,
                'name' => 'Quotes',
                'prefix' => 'QTE',
                'is_default' => false,
            ]);

        InvoiceSeries::factory()
            ->for($company)
            ->for($headOffice, 'establishment')
            ->create([
                'document_type' => InvoiceType::CreditNote->value,
                'name' => 'Credit notes',
                'prefix' => 'CRN',
                'is_default' => false,
            ]);

        $quoteDraft = InvoiceDocumentBuilder::make()
            ->withSeller($company->toSnapshot()->toArray())
            ->withCustomer($primaryCustomer->toSnapshot()->toArray())
            ->withCurrency('EUR')
            ->withNotes('Demo quote for implementation services and maintenance.')
            ->addLines([
                [
                    'description' => 'Implementation service',
                    'quantity' => '1',
                    'unit_price' => '500.00',
                    'tax_rate' => '20',
                ],
                [
                    'description' => 'Maintenance retainer',
                    'quantity' => '3',
                    'unit_price' => '180.00',
                    'tax_rate' => '5.5',
                    'discount_amount' => '30.00',
                ],
            ]);

        $quote = app(CreateQuoteAction::class)->handle($quoteDraft->toDraft(), $company->id, $primaryCustomer->id);
        $quote->update(['status' => QuoteStatus::Sent->value]);
        $quote->refresh()->load('lines');

        $quote->lines->each(function ($line) use ($implementationService, $maintenanceService, $vatStandard, $vatReduced): void {
            if ($line->description === 'Implementation service') {
                $line->forceFill([
                    'product_id' => $implementationService->id,
                    'tax_rate_id' => $vatStandard->id,
                ])->save();

                return;
            }

            if ($line->description === 'Maintenance retainer') {
                $line->forceFill([
                    'product_id' => $maintenanceService->id,
                    'tax_rate_id' => $vatReduced->id,
                ])->save();
            }
        });

        $invoice = app(ConvertQuoteToInvoiceAction::class)->handle($quote);
        app(GenerateInvoiceShareLinkAction::class)->handle($invoice, now()->addDays(30), true);
        app(EnsureInvoicePdfStoredAction::class)->handle($invoice);

        $payment = Payment::factory()
            ->for($company)
            ->for($primaryCustomer)
            ->for($invoice)
            ->create([
                'status' => PaymentStatus::Paid->value,
                'method' => PaymentMethodType::BankTransfer->value,
                'currency' => 'EUR',
                'amount' => $invoice->total_amount,
                'paid_at' => now()->toDateString(),
            ]);

        PaymentAllocation::factory()
            ->for($payment)
            ->for($invoice)
            ->create([
                'amount' => $invoice->total_amount,
            ]);

        app(CreateCreditNoteFromInvoiceAction::class)->handle($invoice);

        $draftInvoice = app(CreateDraftInvoiceAction::class)->handle(
            InvoiceDocumentBuilder::make()
                ->withSeller($company->toSnapshot()->toArray())
                ->withCustomer($secondaryCustomer->toSnapshot()->toArray())
                ->withCurrency('EUR')
                ->withNotes('Draft invoice waiting for approval.')
                ->addLines([
                    [
                        'description' => 'Hardware bundle',
                        'quantity' => '2',
                        'unit_price' => '250.00',
                        'tax_rate' => '20',
                    ],
                    [
                        'description' => 'Setup assistance',
                        'quantity' => '1',
                        'unit_price' => '120.00',
                        'tax_rate' => '20',
                    ],
                ])
                ->toDraft(),
            $company->id,
            $secondaryCustomer->id
        );

        $draftInvoice->refresh()->load('lines');
        $draftInvoice->lines->each(function ($line) use ($hardwareBundle, $vatStandard): void {
            if ($line->description === 'Hardware bundle') {
                $line->forceFill([
                    'product_id' => $hardwareBundle->id,
                    'tax_rate_id' => $vatStandard->id,
                ])->save();

                return;
            }

            if ($line->description === 'Setup assistance') {
                $line->forceFill([
                    'tax_rate_id' => $vatStandard->id,
                ])->save();
            }
        });

        Reminder::factory()
            ->for($company)
            ->for($invoice)
            ->create([
                'channel' => ReminderChannel::Email->value,
                'status' => 'sent',
                'sent_at' => now()->subDay(),
            ]);

        EInvoiceExport::factory()
            ->for($company)
            ->for($invoice)
            ->create([
                'format' => EInvoiceFormat::FacturX->value,
                'status' => 'generated',
                'path' => 'billing/invoices/'.$invoice->id.'/factur-x.xml',
            ]);

        AuditLog::factory()
            ->for($company)
            ->create([
                'auditable_type' => 'invoice',
                'auditable_id' => $invoice->id,
                'event' => 'seeded',
                'context' => [
                    'source' => 'BillingDemoSeeder',
                    'invoice_id' => $invoice->id,
                    'quote_id' => $quote->id,
                    'draft_invoice_id' => $draftInvoice->id,
                ],
                'created_at' => now(),
            ]);
    }
}
