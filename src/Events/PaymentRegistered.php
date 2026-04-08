<?php

declare(strict_types=1);

namespace Proovit\Billing\Events;

use Illuminate\Database\Eloquent\Model;
use Proovit\Billing\Contracts\RecordsBillingAuditTrail;
use Proovit\Billing\Models\Payment;

final readonly class PaymentRegistered implements RecordsBillingAuditTrail
{
    public function __construct(
        public Payment $payment,
        public ?int $userId = null,
        public array $context = [],
    ) {}

    public function auditEvent(): string
    {
        return 'payment.registered';
    }

    public function auditContext(): array
    {
        return array_merge([
            'payment_id' => $this->payment->id,
            'invoice_id' => $this->payment->invoice_id,
            'amount' => $this->payment->amount,
            'currency' => $this->payment->currency,
            'status' => $this->payment->status?->value,
            'method' => $this->payment->method?->value,
        ], $this->context);
    }

    public function auditCompanyId(): ?int
    {
        return $this->payment->company_id;
    }

    public function auditUserId(): ?int
    {
        return $this->userId;
    }

    public function auditModel(): ?Model
    {
        return $this->payment;
    }
}
