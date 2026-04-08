<?php

declare(strict_types=1);

namespace Proovit\Billing\Contracts;

use Proovit\Billing\ValueObjects\LegalMentionSet;

interface LegalMentionResolverInterface
{
    public function resolve(array $context = []): LegalMentionSet;
}
