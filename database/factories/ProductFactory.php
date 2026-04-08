<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Product;

/**
 * @extends Factory<Product>
 */
final class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'sku' => $this->faker->unique()->bothify('SKU-###'),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->sentence(),
            'currency' => 'EUR',
            'is_active' => true,
        ];
    }
}
