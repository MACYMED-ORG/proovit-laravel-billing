<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Proovit\Billing\Contracts\CurrencyFormatterInterface;
use Proovit\Billing\ValueObjects\Money;

final class DefaultCurrencyFormatter implements CurrencyFormatterInterface
{
    public function format(Money $money, ?string $locale = null): string
    {
        return sprintf('%s %s', $money->currency, $money->toDecimalString());
    }
}
