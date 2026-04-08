<?php

declare(strict_types=1);

namespace Proovit\Billing\Contracts;

interface BillingFeatureManagerInterface
{
    public function enabled(string $feature, bool $default = false): bool;
}
