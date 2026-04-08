<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

use InvalidArgumentException;

final readonly class VatNumber
{
    public function __construct(public string $value)
    {
        $normalized = strtoupper(preg_replace('/\s+/', '', $value) ?? '');

        if (! preg_match('/^[A-Z]{2}[A-Z0-9]{2,12}$/', $normalized)) {
            throw new InvalidArgumentException('Invalid VAT number.');
        }
    }

    public function __toString(): string
    {
        return strtoupper(preg_replace('/\s+/', '', $this->value) ?? '');
    }
}
