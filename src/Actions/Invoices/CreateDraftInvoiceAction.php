<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Invoices;

use Illuminate\Support\Facades\DB;
use Proovit\Billing\Contracts\InvoiceCalculatorInterface;
use Proovit\Billing\Contracts\TaxResolverInterface;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\Enums\InvoiceStatus;
use Proovit\Billing\Events\InvoiceDraftCreated;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\InvoiceLine;
use Proovit\Billing\ValueObjects\Money;

final class CreateDraftInvoiceAction
{
    public function __construct(
        private readonly InvoiceCalculatorInterface $calculator,
        private readonly TaxResolverInterface $taxResolver,
    ) {}

    public function handle(InvoiceDraftData $draft, ?int $companyId = null, ?int $customerId = null): Invoice
    {
        return DB::transaction(function () use ($draft, $companyId, $customerId): Invoice {
            $totals = $this->calculator->calculate($draft);

            $invoice = Invoice::create([
                'company_id' => $companyId,
                'customer_id' => $customerId,
                'document_type' => $draft->type->value,
                'status' => InvoiceStatus::Draft->value,
                'currency' => $draft->currency,
                'seller_snapshot' => $draft->seller->toArray(),
                'customer_snapshot' => $draft->customer->toArray(),
                'subtotal_amount' => $totals->subtotal->amount,
                'tax_amount' => $totals->taxTotal->amount,
                'total_amount' => $totals->total->amount,
            ]);

            foreach ($draft->lines as $index => $line) {
                $lineBaseAmount = $line->unitPrice->money->multipliedBy((string) $line->quantity->value);

                if ($line->discount?->amount instanceof Money) {
                    $lineBaseAmount = $lineBaseAmount->minus($line->discount->amount);
                }

                $lineBreakdown = $this->taxResolver->resolve($lineBaseAmount, $line->taxRate);

                InvoiceLine::create([
                    'invoice_id' => $invoice->id,
                    'description' => $line->description,
                    'quantity' => $line->quantity->value,
                    'unit_price' => $line->unitPrice->money->amount,
                    'discount_amount' => $line->discount?->amount?->amount ?? '0.00',
                    'tax_rate' => $line->taxRate->value,
                    'subtotal_amount' => $lineBreakdown->baseAmount->amount,
                    'tax_amount' => $lineBreakdown->taxAmount->amount,
                    'total_amount' => $lineBreakdown->totalAmount->amount,
                    'sort_order' => $index + 1,
                ]);
            }

            $invoice = $invoice->load('lines');

            event(new InvoiceDraftCreated($invoice));

            return $invoice;
        });
    }
}
