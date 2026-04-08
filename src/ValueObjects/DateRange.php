<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

use DateTimeImmutable;
use InvalidArgumentException;

final readonly class DateRange
{
    public function __construct(
        public DateTimeImmutable $start,
        public DateTimeImmutable $end,
    ) {
        if ($end < $start) {
            throw new InvalidArgumentException('Date range end must be after start.');
        }
    }
}
