<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

final readonly class TaxBreakdown
{
    public function __construct(
        public Money $baseAmount,
        public Percentage $rate,
        public Money $taxAmount,
        public Money $totalAmount,
    ) {}
}
