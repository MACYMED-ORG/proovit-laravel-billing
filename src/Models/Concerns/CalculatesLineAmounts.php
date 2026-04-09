<?php

declare(strict_types=1);

namespace Proovit\Billing\Models\Concerns;

use Brick\Math\BigDecimal;
use Illuminate\Database\Eloquent\Model;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;

trait CalculatesLineAmounts
{
    protected static function bootCalculatesLineAmounts(): void
    {
        static::saving(function (Model $model): void {
            if (method_exists($model, 'syncCalculatedAmounts')) {
                $model->syncCalculatedAmounts();
            }
        });
    }

    public function syncCalculatedAmounts(): void
    {
        $currency = $this->resolveLineCurrency();

        $quantity = (string) ($this->getAttribute('quantity') ?? '0');
        $unitPrice = Money::fromDecimal((string) ($this->getAttribute('unit_price') ?? '0'), $currency);
        $discount = Money::fromDecimal((string) ($this->getAttribute('discount_amount') ?? '0'), $currency);
        $taxRate = Percentage::fromDecimal((string) ($this->getAttribute('tax_rate') ?? '0'));

        $subtotal = $unitPrice->multipliedBy($quantity);

        if (! $discount->isZero()) {
            $subtotal = $subtotal->minus($discount);
        }

        $taxAmount = Money::fromDecimal(
            BigDecimal::of($subtotal->amount)
                ->multipliedBy($taxRate->asFraction())
                ->__toString(),
            $currency,
        );

        $this->setAttribute('subtotal_amount', $subtotal->toDecimalString());
        $this->setAttribute('tax_amount', $taxAmount->toDecimalString());
        $this->setAttribute('total_amount', $subtotal->plus($taxAmount)->toDecimalString());
    }

    protected function resolveLineCurrency(): string
    {
        foreach (['invoice', 'quote', 'creditNote'] as $relation) {
            if (! method_exists($this, $relation)) {
                continue;
            }

            $document = $this->getRelationValue($relation) ?? $this->{$relation} ?? null;

            if (! $document instanceof Model) {
                continue;
            }

            $currency = $document->getAttribute('currency');

            if (filled($currency)) {
                return (string) $currency;
            }

            $company = $document->getRelationValue('company') ?? $document->company ?? null;

            if ($company instanceof Model) {
                $companyCurrency = $company->getAttribute('default_currency');

                if (filled($companyCurrency)) {
                    return (string) $companyCurrency;
                }
            }
        }

        return 'EUR';
    }
}
