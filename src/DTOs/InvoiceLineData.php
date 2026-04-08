<?php

declare(strict_types=1);

namespace Proovit\Billing\DTOs;

use Proovit\Billing\ValueObjects\DiscountValue;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\UnitPrice;

final readonly class InvoiceLineData
{
    public function __construct(
        public string $description,
        public LineQuantity $quantity,
        public UnitPrice $unitPrice,
        public Percentage $taxRate,
        public ?DiscountValue $discount = null,
    ) {}
}
