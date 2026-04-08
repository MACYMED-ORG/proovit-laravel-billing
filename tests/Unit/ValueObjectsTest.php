<?php

declare(strict_types=1);

use Proovit\Billing\Enums\SequenceResetPolicy;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\SequencePattern;
use Proovit\Billing\ValueObjects\VatNumber;

it('normalizes money and percentage values without using floats', function (): void {
    $money = Money::fromDecimal('12.345', 'eur');
    $percentage = Percentage::fromDecimal('20');

    expect($money->amount)->toBe('12.35');
    expect($money->currency)->toBe('EUR');
    expect($percentage->asFraction())->toBe('0.20000000');
});

it('formats numbering patterns deterministically', function (): void {
    $pattern = new SequencePattern(
        prefix: 'INV',
        suffix: null,
        pattern: '{prefix}-{year}{month}-{sequence}',
        padding: 6,
        reset: SequenceResetPolicy::Annual,
    );

    expect($pattern->format(12, new DateTimeImmutable('2026-04-08')))->toBe('INV-202604-000012');
});

it('validates identifier value objects', function (): void {
    expect((string) new VatNumber('fr12345678901'))->toBe('FR12345678901');
});
