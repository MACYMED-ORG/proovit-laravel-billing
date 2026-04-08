<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

use InvalidArgumentException;

final readonly class LineQuantity
{
    public function __construct(public string $value)
    {
        if (! is_numeric($value)) {
            throw new InvalidArgumentException('Line quantity must be numeric.');
        }
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
