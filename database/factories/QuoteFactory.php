<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Enums\QuoteStatus;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\Quote;

/**
 * @extends Factory<Quote>
 */
final class QuoteFactory extends Factory
{
    protected $model = Quote::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'customer_id' => Customer::factory(),
            'status' => QuoteStatus::Draft->value,
            'number' => null,
            'seller_snapshot' => [
                'legal_name' => $this->faker->company(),
                'display_name' => $this->faker->company(),
            ],
            'customer_snapshot' => [
                'legal_name' => $this->faker->company(),
                'reference' => $this->faker->bothify('CUS-###'),
            ],
            'subtotal_amount' => '0.00',
            'tax_amount' => '0.00',
            'total_amount' => '0.00',
        ];
    }
}
