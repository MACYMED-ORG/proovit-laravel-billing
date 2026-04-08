<?php

declare(strict_types=1);

namespace Proovit\Billing\Events;

use Illuminate\Database\Eloquent\Model;
use Proovit\Billing\Contracts\RecordsBillingAuditTrail;
use Proovit\Billing\Models\Reminder;

final readonly class ReminderRecorded implements RecordsBillingAuditTrail
{
    public function __construct(
        public Reminder $reminder,
        public ?int $userId = null,
        public array $context = [],
    ) {}

    public function auditEvent(): string
    {
        return 'reminder.recorded';
    }

    public function auditContext(): array
    {
        return array_merge([
            'reminder_id' => $this->reminder->id,
            'invoice_id' => $this->reminder->invoice_id,
            'channel' => $this->reminder->channel,
            'status' => $this->reminder->status,
            'sent_at' => $this->reminder->sent_at?->toIso8601String(),
        ], $this->context);
    }

    public function auditCompanyId(): ?int
    {
        return $this->reminder->company_id;
    }

    public function auditUserId(): ?int
    {
        return $this->userId;
    }

    public function auditModel(): ?Model
    {
        return $this->reminder;
    }
}
