<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use DateTimeInterface;
use Proovit\Billing\Contracts\SequenceGeneratorInterface;
use Proovit\Billing\ValueObjects\InvoiceNumber;
use Proovit\Billing\ValueObjects\SequencePattern;

final class ConfigSequenceGenerator implements SequenceGeneratorInterface
{
    public function generate(SequencePattern $pattern, int $sequence, ?DateTimeInterface $date = null): InvoiceNumber
    {
        return new InvoiceNumber($pattern->format($sequence, $date));
    }
}
