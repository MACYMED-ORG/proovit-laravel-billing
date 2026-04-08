<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

use InvalidArgumentException;

final readonly class Siren
{
    public function __construct(public string $value)
    {
        $normalized = preg_replace('/\D+/', '', $value) ?? '';

        if (! preg_match('/^\d{9}$/', $normalized)) {
            throw new InvalidArgumentException('Invalid SIREN.');
        }
    }

    public function __toString(): string
    {
        return preg_replace('/\D+/', '', $this->value) ?? '';
    }
}
