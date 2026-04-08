<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Models\Product;
use Proovit\Billing\Models\ProductPrice;
use Proovit\Billing\Models\TaxRate;

/**
 * @extends Factory<ProductPrice>
 */
final class ProductPriceFactory extends Factory
{
    protected $model = ProductPrice::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'tax_rate_id' => TaxRate::factory(),
            'currency' => 'EUR',
            'amount' => $this->faker->randomFloat(2, 10, 5000),
            'starts_at' => now()->subMonth(),
            'ends_at' => null,
        ];
    }
}
