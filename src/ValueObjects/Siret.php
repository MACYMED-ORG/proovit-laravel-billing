<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

use InvalidArgumentException;

final readonly class Siret
{
    public function __construct(public string $value)
    {
        $normalized = preg_replace('/\D+/', '', $value) ?? '';

        if (! preg_match('/^\d{14}$/', $normalized)) {
            throw new InvalidArgumentException('Invalid SIRET.');
        }
    }

    public function __toString(): string
    {
        return preg_replace('/\D+/', '', $this->value) ?? '';
    }
}
