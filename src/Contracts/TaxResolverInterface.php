<?php

declare(strict_types=1);

namespace Proovit\Billing\Contracts;

use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\TaxBreakdown;

interface TaxResolverInterface
{
    public function resolve(Money $baseAmount, Percentage $rate): TaxBreakdown;
}
