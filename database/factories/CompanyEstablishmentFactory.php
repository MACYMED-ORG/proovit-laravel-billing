<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\CompanyEstablishment;

/**
 * @extends Factory<CompanyEstablishment>
 */
final class CompanyEstablishmentFactory extends Factory
{
    protected $model = CompanyEstablishment::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => $this->faker->company(),
            'code' => $this->faker->bothify('EST-###'),
            'address' => [
                'line1' => $this->faker->streetAddress(),
                'postal_code' => $this->faker->postcode(),
                'city' => $this->faker->city(),
                'country' => 'FR',
            ],
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'is_default' => false,
        ];
    }
}
