<?php

declare(strict_types=1);

namespace Proovit\Billing\Contracts;

interface BillingConfigResolverInterface
{
    public function all(): array;

    public function get(string $key, mixed $default = null): mixed;
}
