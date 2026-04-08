<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Proovit\Billing\Contracts\InvoiceCalculatorInterface;
use Proovit\Billing\Contracts\TaxResolverInterface;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceTotalsData;
use Proovit\Billing\ValueObjects\Money;

final class ConfigInvoiceCalculator implements InvoiceCalculatorInterface
{
    public function __construct(private readonly TaxResolverInterface $taxResolver) {}

    public function calculate(InvoiceDraftData $draft): InvoiceTotalsData
    {
        $currency = $draft->currency;
        $subtotal = Money::zero($currency);
        $taxTotal = Money::zero($currency);

        foreach ($draft->lines as $line) {
            $lineBase = $line->unitPrice->money->multipliedBy((string) $line->quantity->value);

            if ($line->discount?->amount instanceof Money) {
                $lineBase = $lineBase->minus($line->discount->amount);
            }

            $subtotal = $subtotal->plus($lineBase);
            $taxTotal = $taxTotal->plus($this->taxResolver->resolve($lineBase, $line->taxRate)->taxAmount);
        }

        return new InvoiceTotalsData(
            subtotal: $subtotal,
            taxTotal: $taxTotal,
            total: $subtotal->plus($taxTotal),
        );
    }
}
