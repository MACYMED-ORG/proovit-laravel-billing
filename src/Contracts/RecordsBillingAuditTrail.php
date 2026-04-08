<?php

declare(strict_types=1);

namespace Proovit\Billing\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RecordsBillingAuditTrail
{
    public function auditEvent(): string;

    public function auditContext(): array;

    public function auditCompanyId(): ?int;

    public function auditUserId(): ?int;

    public function auditModel(): ?Model;
}
