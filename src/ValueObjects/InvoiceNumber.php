<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

final readonly class InvoiceNumber
{
    public function __construct(public string $value)
    {
        $this->value = trim($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
