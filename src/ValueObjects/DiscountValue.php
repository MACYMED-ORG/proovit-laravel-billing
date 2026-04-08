<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

final readonly class DiscountValue
{
    public function __construct(
        public Percentage $percentage,
        public ?Money $amount = null,
    ) {}
}
