<?php

declare(strict_types=1);

namespace Proovit\Billing\Contracts;

interface ReferenceGeneratorInterface
{
    public function generate(string $prefix, int|string $sequence): string;
}
