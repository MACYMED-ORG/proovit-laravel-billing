<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\TaxRate;

/**
 * @extends Factory<TaxRate>
 */
final class TaxRateFactory extends Factory
{
    protected $model = TaxRate::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => 'Standard VAT',
            'rate' => '20.0000',
            'country' => 'FR',
            'is_default' => false,
        ];
    }
}
