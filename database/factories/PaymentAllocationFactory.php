<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\Payment;
use Proovit\Billing\Models\PaymentAllocation;

/**
 * @extends Factory<PaymentAllocation>
 */
final class PaymentAllocationFactory extends Factory
{
    protected $model = PaymentAllocation::class;

    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'invoice_id' => Invoice::factory(),
            'amount' => '100.00',
        ];
    }
}
