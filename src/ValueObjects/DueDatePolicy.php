<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

final readonly class DueDatePolicy
{
    public function __construct(
        public int $days = 30,
        public string $basis = 'invoice_date',
    ) {}
}
