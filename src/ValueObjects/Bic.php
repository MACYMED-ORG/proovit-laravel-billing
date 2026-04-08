<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

use InvalidArgumentException;

final readonly class Bic
{
    public function __construct(public string $value)
    {
        $normalized = strtoupper(trim($value));

        if (! preg_match('/^[A-Z0-9]{8}([A-Z0-9]{3})?$/', $normalized)) {
            throw new InvalidArgumentException('Invalid BIC.');
        }
    }

    public function __toString(): string
    {
        return strtoupper(trim($this->value));
    }
}
