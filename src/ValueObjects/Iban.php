<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

use InvalidArgumentException;

final readonly class Iban
{
    public function __construct(public string $value)
    {
        $normalized = strtoupper(preg_replace('/\s+/', '', $value) ?? '');

        if (! preg_match('/^[A-Z]{2}[0-9A-Z]{13,32}$/', $normalized)) {
            throw new InvalidArgumentException('Invalid IBAN.');
        }
    }

    public function __toString(): string
    {
        return strtoupper(preg_replace('/\s+/', '', $this->value) ?? '');
    }
}
