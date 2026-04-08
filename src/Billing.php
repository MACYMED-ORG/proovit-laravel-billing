<?php

declare(strict_types=1);

namespace Proovit\Billing;

final class Billing
{
    /**
     * Read a value from the package configuration.
     */
    public function config(?string $key = null, mixed $default = null): mixed
    {
        $configKey = $key === null || $key === '' ? 'billing' : "billing.{$key}";

        return config($configKey, $default);
    }

    public function features(): array
    {
        return (array) $this->config('features', []);
    }

    public function featureEnabled(string $feature, bool $default = false): bool
    {
        return (bool) data_get($this->features(), $feature, $default);
    }
}
