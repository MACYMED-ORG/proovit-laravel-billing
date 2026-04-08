<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\CompanyEstablishment;
use Proovit\Billing\Models\InvoiceSeries;

/**
 * @extends Factory<InvoiceSeries>
 */
final class InvoiceSeriesFactory extends Factory
{
    protected $model = InvoiceSeries::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'establishment_id' => CompanyEstablishment::factory(),
            'document_type' => InvoiceType::Invoice->value,
            'name' => 'Default series',
            'prefix' => 'INV',
            'suffix' => null,
            'pattern' => '{prefix}-{year}{month}-{sequence}',
            'padding' => 6,
            'reset_policy' => 'annual',
            'current_sequence' => 0,
            'is_default' => true,
        ];
    }
}
