<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

use InvalidArgumentException;

final readonly class Currency
{
    public function __construct(public string $code)
    {
        $normalized = strtoupper(trim($code));

        if (! preg_match('/^[A-Z]{3}$/', $normalized)) {
            throw new InvalidArgumentException('Invalid currency code.');
        }
    }

    public function __toString(): string
    {
        return strtoupper(trim($this->code));
    }
}
