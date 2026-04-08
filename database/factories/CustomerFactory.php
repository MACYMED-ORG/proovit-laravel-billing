<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;

/**
 * @extends Factory<Customer>
 */
final class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $companyName = $this->faker->company();

        return [
            'company_id' => Company::factory(),
            'legal_name' => $companyName,
            'full_name' => $companyName,
            'reference' => $this->faker->unique()->bothify('CUS-###'),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'vat_number' => 'FR'.$this->faker->numerify('##').$this->faker->numerify('#######'),
            'billing_address' => [
                'line1' => $this->faker->streetAddress(),
                'postal_code' => $this->faker->postcode(),
                'city' => $this->faker->city(),
                'country' => 'FR',
            ],
            'shipping_address' => [
                'line1' => $this->faker->streetAddress(),
                'postal_code' => $this->faker->postcode(),
                'city' => $this->faker->city(),
                'country' => 'FR',
            ],
        ];
    }
}
