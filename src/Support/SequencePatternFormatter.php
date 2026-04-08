<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use DateTimeInterface;
use Proovit\Billing\ValueObjects\SequencePattern;

final class SequencePatternFormatter
{
    public static function format(SequencePattern $pattern, int $sequence, ?DateTimeInterface $date = null): string
    {
        $date ??= new \DateTimeImmutable;

        $replacements = [
            '{prefix}' => $pattern->prefix,
            '{suffix}' => $pattern->suffix ?? '',
            '{year}' => $date->format('Y'),
            '{month}' => $date->format('m'),
            '{day}' => $date->format('d'),
            '{sequence}' => str_pad((string) $sequence, $pattern->padding, '0', STR_PAD_LEFT),
            '{reset}' => $pattern->reset->value,
        ];

        return trim(strtr($pattern->pattern, $replacements), '-_ ');
    }
}
