<?php

declare(strict_types=1);

namespace Proovit\Billing\Contracts;

use DateTimeInterface;
use Proovit\Billing\ValueObjects\InvoiceNumber;
use Proovit\Billing\ValueObjects\SequencePattern;

interface SequenceGeneratorInterface
{
    public function generate(SequencePattern $pattern, int $sequence, ?DateTimeInterface $date = null): InvoiceNumber;
}
