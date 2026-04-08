<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Enums\CreditNoteStatus;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\CreditNote;
use Proovit\Billing\Models\Invoice;

/**
 * @extends Factory<CreditNote>
 */
final class CreditNoteFactory extends Factory
{
    protected $model = CreditNote::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'invoice_id' => Invoice::factory(),
            'status' => CreditNoteStatus::Draft->value,
            'number' => null,
            'seller_snapshot' => [
                'legal_name' => $this->faker->company(),
                'display_name' => $this->faker->company(),
            ],
            'customer_snapshot' => [
                'legal_name' => $this->faker->company(),
            ],
            'subtotal_amount' => '0.00',
            'tax_amount' => '0.00',
            'total_amount' => '0.00',
        ];
    }
}
