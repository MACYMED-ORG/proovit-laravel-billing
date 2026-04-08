<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Quotes;

use Illuminate\Support\Facades\DB;
use Proovit\Billing\Contracts\InvoiceCalculatorInterface;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceLineData;
use Proovit\Billing\Enums\InvoiceStatus;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\InvoiceLine;
use Proovit\Billing\Models\Quote;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\DiscountValue;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\UnitPrice;

final class ConvertQuoteToInvoiceAction
{
    public function __construct(private readonly InvoiceCalculatorInterface $calculator) {}

    public function handle(Quote $quote, ?int $customerId = null): Invoice
    {
        return DB::transaction(function () use ($quote, $customerId): Invoice {
            $quote->loadMissing(['company', 'customer', 'lines']);

            $seller = $quote->seller_snapshot ? $this->mapCompanySnapshot($quote->seller_snapshot) : $quote->company?->toSnapshot();
            $customer = $quote->customer_snapshot ? $this->mapCustomerSnapshot($quote->customer_snapshot) : $quote->customer?->toSnapshot();

            if (! $seller instanceof CompanyIdentitySnapshot || ! $customer instanceof CustomerIdentitySnapshot) {
                throw new \RuntimeException('Quote snapshots are required to convert a quote to an invoice.');
            }

            $draft = new InvoiceDraftData(
                seller: $seller,
                customer: $customer,
                lines: $quote->lines->map(fn ($line): InvoiceLineData => new InvoiceLineData(
                    description: $line->description,
                    quantity: new LineQuantity((string) $line->quantity),
                    unitPrice: new UnitPrice(Money::fromDecimal((string) $line->unit_price, $quote->company?->default_currency ?? 'EUR')),
                    taxRate: Percentage::fromDecimal((string) $line->tax_rate),
                    discount: (float) $line->discount_amount > 0
                        ? new DiscountValue(
                            percentage: Percentage::fromDecimal('0'),
                            amount: Money::fromDecimal((string) $line->discount_amount, $quote->company?->default_currency ?? 'EUR'),
                        )
                        : null,
                ))->all(),
                currency: $quote->company?->default_currency ?? 'EUR',
                type: InvoiceType::Invoice,
            );

            $totals = $this->calculator->calculate($draft);

            $invoice = Invoice::create([
                'company_id' => $quote->company_id,
                'customer_id' => $customerId ?? $quote->customer_id,
                'quote_id' => $quote->id,
                'document_type' => InvoiceType::Invoice->value,
                'status' => InvoiceStatus::Draft->value,
                'currency' => $draft->currency,
                'seller_snapshot' => $seller->toArray(),
                'customer_snapshot' => $customer->toArray(),
                'subtotal_amount' => $totals->subtotal->amount,
                'tax_amount' => $totals->taxTotal->amount,
                'total_amount' => $totals->total->amount,
                'notes' => $quote->number ? sprintf('Converted from quote %s', $quote->number) : null,
            ]);

            foreach ($quote->lines as $index => $line) {
                InvoiceLine::create([
                    'invoice_id' => $invoice->id,
                    'description' => $line->description,
                    'quantity' => $line->quantity,
                    'unit_price' => $line->unit_price,
                    'discount_amount' => $line->discount_amount,
                    'tax_rate' => $line->tax_rate,
                    'subtotal_amount' => $line->subtotal_amount,
                    'tax_amount' => $line->tax_amount,
                    'total_amount' => $line->total_amount,
                    'sort_order' => $index + 1,
                ]);
            }

            $quote->forceFill(['converted_invoice_id' => $invoice->id])->save();

            return $invoice->load('lines', 'quote');
        });
    }

    private function mapCompanySnapshot(array $snapshot): CompanyIdentitySnapshot
    {
        return new CompanyIdentitySnapshot(
            legalName: $snapshot['legal_name'] ?? null,
            displayName: $snapshot['display_name'] ?? null,
            legalForm: $snapshot['legal_form'] ?? null,
            registrationCountry: $snapshot['registration_country'] ?? null,
            contactEmail: $snapshot['contact_email'] ?? null,
            contactPhone: $snapshot['contact_phone'] ?? null,
            address: isset($snapshot['address']) && is_array($snapshot['address'])
                ? new AddressData(
                    line1: $snapshot['address']['line1'] ?? null,
                    line2: $snapshot['address']['line2'] ?? null,
                    postalCode: $snapshot['address']['postal_code'] ?? null,
                    city: $snapshot['address']['city'] ?? null,
                    region: $snapshot['address']['region'] ?? null,
                    country: $snapshot['address']['country'] ?? null,
                )
                : null,
        );
    }

    private function mapCustomerSnapshot(array $snapshot): CustomerIdentitySnapshot
    {
        return new CustomerIdentitySnapshot(
            legalName: $snapshot['legal_name'] ?? null,
            fullName: $snapshot['full_name'] ?? null,
            reference: $snapshot['reference'] ?? null,
            email: $snapshot['email'] ?? null,
            billingAddress: isset($snapshot['billing_address']) && is_array($snapshot['billing_address'])
                ? new AddressData(
                    line1: $snapshot['billing_address']['line1'] ?? null,
                    line2: $snapshot['billing_address']['line2'] ?? null,
                    postalCode: $snapshot['billing_address']['postal_code'] ?? null,
                    city: $snapshot['billing_address']['city'] ?? null,
                    region: $snapshot['billing_address']['region'] ?? null,
                    country: $snapshot['billing_address']['country'] ?? null,
                )
                : null,
        );
    }
}
