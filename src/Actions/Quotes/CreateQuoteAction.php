<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Quotes;

use Illuminate\Support\Facades\DB;
use Proovit\Billing\Contracts\InvoiceCalculatorInterface;
use Proovit\Billing\Contracts\TaxResolverInterface;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\Enums\QuoteStatus;
use Proovit\Billing\Models\Quote;
use Proovit\Billing\Models\QuoteLine;
use Proovit\Billing\ValueObjects\Money;

final class CreateQuoteAction
{
    public function __construct(
        private readonly InvoiceCalculatorInterface $calculator,
        private readonly TaxResolverInterface $taxResolver,
    ) {}

    public function handle(InvoiceDraftData $draft, ?int $companyId = null, ?int $customerId = null): Quote
    {
        return DB::transaction(function () use ($draft, $companyId, $customerId): Quote {
            $totals = $this->calculator->calculate($draft);

            $quote = Quote::create([
                'company_id' => $companyId,
                'customer_id' => $customerId,
                'status' => QuoteStatus::Draft->value,
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

                QuoteLine::create([
                    'quote_id' => $quote->id,
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

            return $quote->load('lines');
        });
    }
}
