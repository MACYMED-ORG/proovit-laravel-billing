<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

use DateTimeInterface;
use Proovit\Billing\Enums\SequenceResetPolicy;
use Proovit\Billing\Support\SequencePatternFormatter;

final readonly class SequencePattern
{
    public function __construct(
        public string $prefix = 'INV',
        public ?string $suffix = null,
        public string $pattern = '{prefix}-{year}{month}-{sequence}',
        public int $padding = 6,
        public SequenceResetPolicy $reset = SequenceResetPolicy::Annual,
    ) {}

    public function format(int $sequence, ?DateTimeInterface $date = null): string
    {
        return SequencePatternFormatter::format($this, $sequence, $date);
    }
}
