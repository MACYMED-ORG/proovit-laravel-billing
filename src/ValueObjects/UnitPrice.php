<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

final readonly class UnitPrice
{
    public function __construct(public Money $money) {}
}
