<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Proovit\Billing\Contracts\BillingConfigResolverInterface;

final class ConfigBillingConfigResolver implements BillingConfigResolverInterface
{
    public function all(): array
    {
        return (array) config('billing', []);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return config("billing.{$key}", $default);
    }
}
