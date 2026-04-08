<?php

declare(strict_types=1);

namespace Proovit\Billing\Contracts;

use Proovit\Billing\ValueObjects\Money;

interface CurrencyFormatterInterface
{
    public function format(Money $money, ?string $locale = null): string;
}
