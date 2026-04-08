<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Proovit\Billing\Contracts\ReferenceGeneratorInterface;

final class ConfigReferenceGenerator implements ReferenceGeneratorInterface
{
    public function generate(string $prefix, int|string $sequence): string
    {
        return strtoupper(trim($prefix)).'-'.str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
    }
}
