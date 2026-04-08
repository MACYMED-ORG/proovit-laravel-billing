<?php

declare(strict_types=1);

namespace Proovit\Billing\Listeners;

use Proovit\Billing\Contracts\RecordsBillingAuditTrail;
use Proovit\Billing\Models\AuditLog;

final class RecordBillingAuditTrail
{
    public function handle(RecordsBillingAuditTrail $event): void
    {
        $model = $event->auditModel();

        AuditLog::create([
            'company_id' => $event->auditCompanyId(),
            'user_id' => $event->auditUserId(),
            'auditable_type' => $model ? $model::class : null,
            'auditable_id' => $model?->getKey(),
            'event' => $event->auditEvent(),
            'context' => $event->auditContext(),
            'created_at' => now(),
        ]);
    }
}
