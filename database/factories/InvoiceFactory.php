<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Enums\InvoiceStatus;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\InvoiceSeries;

/**
 * @extends Factory<Invoice>
 */
final class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'customer_id' => Customer::factory(),
            'invoice_series_id' => InvoiceSeries::factory(),
            'document_type' => InvoiceType::Invoice->value,
            'status' => InvoiceStatus::Draft->value,
            'number' => null,
            'currency' => 'EUR',
            'issued_at' => now()->toDateString(),
            'due_at' => now()->addDays(30)->toDateString(),
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
            'notes' => null,
            'finalized_at' => null,
            'cancelled_at' => null,
        ];
    }
}
