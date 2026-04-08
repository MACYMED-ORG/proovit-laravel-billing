<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Enums\PaymentMethodType;
use Proovit\Billing\Enums\PaymentStatus;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\Payment;

/**
 * @extends Factory<Payment>
 */
final class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'customer_id' => Customer::factory(),
            'invoice_id' => Invoice::factory(),
            'status' => PaymentStatus::Pending->value,
            'method' => PaymentMethodType::BankTransfer->value,
            'currency' => 'EUR',
            'amount' => '100.00',
            'paid_at' => now(),
        ];
    }
}
