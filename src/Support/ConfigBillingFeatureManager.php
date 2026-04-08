<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Proovit\Billing\Billing;
use Proovit\Billing\Contracts\BillingFeatureManagerInterface;

final class ConfigBillingFeatureManager implements BillingFeatureManagerInterface
{
    public function __construct(private readonly Billing $billing) {}

    public function enabled(string $feature, bool $default = false): bool
    {
        return $this->billing->featureEnabled($feature, $default);
    }
}
