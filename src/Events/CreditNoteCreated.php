<?php

declare(strict_types=1);

namespace Proovit\Billing\Events;

use Illuminate\Database\Eloquent\Model;
use Proovit\Billing\Contracts\RecordsBillingAuditTrail;
use Proovit\Billing\Models\CreditNote;

final readonly class CreditNoteCreated implements RecordsBillingAuditTrail
{
    public function __construct(
        public CreditNote $creditNote,
        public ?int $userId = null,
        public array $context = [],
    ) {}

    public function auditEvent(): string
    {
        return 'credit_note.created';
    }

    public function auditContext(): array
    {
        return array_merge([
            'credit_note_id' => $this->creditNote->id,
            'invoice_id' => $this->creditNote->invoice_id,
            'number' => $this->creditNote->number,
            'status' => $this->creditNote->status?->value,
        ], $this->context);
    }

    public function auditCompanyId(): ?int
    {
        return $this->creditNote->company_id;
    }

    public function auditUserId(): ?int
    {
        return $this->userId;
    }

    public function auditModel(): ?Model
    {
        return $this->creditNote;
    }
}
