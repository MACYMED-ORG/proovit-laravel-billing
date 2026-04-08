<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\CompanyBankAccount;
use Proovit\Billing\Models\CompanyEstablishment;

/**
 * @extends Factory<CompanyBankAccount>
 */
final class CompanyBankAccountFactory extends Factory
{
    protected $model = CompanyBankAccount::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'establishment_id' => CompanyEstablishment::factory(),
            'label' => 'Main account',
            'iban' => 'FR76'.$this->faker->numerify('###################'),
            'bic' => strtoupper($this->faker->bothify('??##??##')),
            'bank_name' => $this->faker->company().' Bank',
            'account_holder' => $this->faker->company(),
            'is_default' => false,
        ];
    }
}
