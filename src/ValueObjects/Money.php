<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use InvalidArgumentException;

final readonly class Money
{
    public function __construct(
        public string $amount,
        public string $currency = 'EUR',
    ) {
        if ($currency === '') {
            throw new InvalidArgumentException('Currency is required.');
        }
    }

    public static function fromDecimal(string|int $amount, string $currency = 'EUR'): self
    {
        return new self(self::normalizeAmount((string) $amount), strtoupper(trim($currency)));
    }

    public static function zero(string $currency = 'EUR'): self
    {
        return new self('0.00', strtoupper(trim($currency)));
    }

    public function plus(self $other): self
    {
        $this->assertSameCurrency($other);

        return new self(
            self::normalizeAmount(BigDecimal::of($this->amount)->plus($other->amount)->__toString()),
            $this->currency
        );
    }

    public function minus(self $other): self
    {
        $this->assertSameCurrency($other);

        return new self(
            self::normalizeAmount(BigDecimal::of($this->amount)->minus($other->amount)->__toString()),
            $this->currency
        );
    }

    public function multipliedBy(string|int $multiplier): self
    {
        return new self(
            self::normalizeAmount(
                BigDecimal::of($this->amount)->multipliedBy((string) $multiplier)->__toString()
            ),
            $this->currency
        );
    }

    public function toDecimalString(int $scale = 2): string
    {
        return BigDecimal::of($this->amount)->toScale($scale, RoundingMode::HALF_UP)->__toString();
    }

    public function isZero(): bool
    {
        return BigDecimal::of($this->amount)->isZero();
    }

    private function assertSameCurrency(self $other): void
    {
        if (strtoupper($this->currency) !== strtoupper($other->currency)) {
            throw new InvalidArgumentException('Currency mismatch.');
        }
    }

    private static function normalizeAmount(string $amount): string
    {
        return BigDecimal::of($amount)->toScale(2, RoundingMode::HALF_UP)->__toString();
    }
}
