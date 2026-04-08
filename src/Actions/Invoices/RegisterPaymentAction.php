<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Invoices;

use Illuminate\Support\Facades\DB;
use Proovit\Billing\Enums\PaymentStatus;
use Proovit\Billing\Events\PaymentRegistered;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\Payment;
use Proovit\Billing\Models\PaymentAllocation;

final class RegisterPaymentAction
{
    public function handle(Invoice $invoice, string $amount, ?string $method = null, ?int $customerId = null): Payment
    {
        return DB::transaction(function () use ($invoice, $amount, $method, $customerId): Payment {
            $payment = Payment::create([
                'company_id' => $invoice->company_id,
                'customer_id' => $customerId ?? $invoice->customer_id,
                'invoice_id' => $invoice->id,
                'status' => PaymentStatus::Pending->value,
                'method' => $method,
                'currency' => $invoice->currency,
                'amount' => $amount,
                'paid_at' => now(),
            ]);

            PaymentAllocation::create([
                'payment_id' => $payment->id,
                'invoice_id' => $invoice->id,
                'amount' => $amount,
            ]);

            $payment = $payment->load('allocations');

            event(new PaymentRegistered($payment));

            return $payment;
        });
    }
}
