<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Models\CreditNote;
use Proovit\Billing\Models\CreditNoteLine;
use Proovit\Billing\Models\Product;
use Proovit\Billing\Models\TaxRate;

/**
 * @extends Factory<CreditNoteLine>
 */
final class CreditNoteLineFactory extends Factory
{
    protected $model = CreditNoteLine::class;

    public function definition(): array
    {
        $quantity = $this->faker->randomFloat(4, 1, 5);
        $unitPrice = $this->faker->randomFloat(2, 10, 250);
        $subtotal = round($quantity * $unitPrice, 2);
        $taxRate = 20.0;

        return [
            'credit_note_id' => CreditNote::factory(),
            'product_id' => Product::factory(),
            'tax_rate_id' => TaxRate::factory(),
            'description' => $this->faker->sentence(4),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount_amount' => '0.00',
            'tax_rate' => $taxRate,
            'subtotal_amount' => number_format($subtotal, 2, '.', ''),
            'tax_amount' => number_format($subtotal * ($taxRate / 100), 2, '.', ''),
            'total_amount' => number_format($subtotal * 1.2, 2, '.', ''),
            'sort_order' => 1,
        ];
    }
}
