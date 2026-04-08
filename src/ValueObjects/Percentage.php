<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use InvalidArgumentException;

final readonly class Percentage
{
    public function __construct(public string $value)
    {
        if (! is_numeric($value)) {
            throw new InvalidArgumentException('Percentage must be numeric.');
        }
    }

    public static function fromDecimal(string|int $value): self
    {
        return new self((string) $value);
    }

    public function asFraction(): string
    {
        return BigDecimal::of($this->value)
            ->dividedBy('100', 8, RoundingMode::HALF_UP)
            ->__toString();
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
