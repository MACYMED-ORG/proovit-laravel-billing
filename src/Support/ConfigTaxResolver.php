<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Proovit\Billing\Contracts\TaxResolverInterface;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\TaxBreakdown;

final class ConfigTaxResolver implements TaxResolverInterface
{
    public function resolve(Money $baseAmount, Percentage $rate): TaxBreakdown
    {
        $taxAmount = $baseAmount->multipliedBy($rate->asFraction());

        return new TaxBreakdown(
            baseAmount: $baseAmount,
            rate: $rate,
            taxAmount: $taxAmount,
            totalAmount: $baseAmount->plus($taxAmount),
        );
    }
}
