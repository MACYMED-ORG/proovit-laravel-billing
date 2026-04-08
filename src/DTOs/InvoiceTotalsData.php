<?php

declare(strict_types=1);

namespace Proovit\Billing\DTOs;

use Proovit\Billing\ValueObjects\Money;

final readonly class InvoiceTotalsData
{
    public function __construct(
        public Money $subtotal,
        public Money $taxTotal,
        public Money $total,
    ) {}
}
