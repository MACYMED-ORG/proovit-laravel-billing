<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Models\Company;

/**
 * @extends Factory<Company>
 */
final class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        $legalName = $this->faker->company().' SAS';

        return [
            'legal_name' => $legalName,
            'display_name' => $this->faker->optional()->company(),
            'legal_form' => 'SAS',
            'registration_country' => 'FR',
            'siren' => $this->faker->numerify('#########'),
            'siret' => $this->faker->numerify('##############'),
            'vat_number' => 'FR'.$this->faker->numerify('##').$this->faker->numerify('#######'),
            'intracommunity_vat_number' => 'FR'.$this->faker->numerify('##').$this->faker->numerify('#######'),
            'naf_ape' => $this->faker->numerify('####Z'),
            'rcs_city' => $this->faker->city(),
            'head_office_address' => [
                'line1' => $this->faker->streetAddress(),
                'postal_code' => $this->faker->postcode(),
                'city' => $this->faker->city(),
                'country' => 'FR',
            ],
            'billing_address' => [
                'line1' => $this->faker->streetAddress(),
                'postal_code' => $this->faker->postcode(),
                'city' => $this->faker->city(),
                'country' => 'FR',
            ],
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
            'default_currency' => 'EUR',
            'default_locale' => 'fr',
            'timezone' => 'Europe/Paris',
            'default_payment_terms' => 30,
            'invoice_prefix' => 'INV',
            'invoice_sequence_pattern' => '{prefix}-{year}{month}-{sequence}',
        ];
    }
}
