<?php

declare(strict_types=1);

namespace Proovit\Billing\Events;

use Illuminate\Database\Eloquent\Model;
use Proovit\Billing\Contracts\RecordsBillingAuditTrail;
use Proovit\Billing\Models\Invoice;

final readonly class InvoiceFinalized implements RecordsBillingAuditTrail
{
    public function __construct(
        public Invoice $invoice,
        public ?int $userId = null,
        public array $context = [],
    ) {}

    public function auditEvent(): string
    {
        return 'invoice.finalized';
    }

    public function auditContext(): array
    {
        return array_merge([
            'invoice_number' => $this->invoice->number,
            'series_id' => $this->invoice->invoice_series_id,
            'reservation_id' => $this->invoice->invoice_number_reservation_id,
            'status' => $this->invoice->status?->value,
            'document_type' => $this->invoice->document_type?->value,
        ], $this->context);
    }

    public function auditCompanyId(): ?int
    {
        return $this->invoice->company_id;
    }

    public function auditUserId(): ?int
    {
        return $this->userId;
    }

    public function auditModel(): ?Model
    {
        return $this->invoice;
    }
}
