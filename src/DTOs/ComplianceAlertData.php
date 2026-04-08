<?php

declare(strict_types=1);

namespace Proovit\Billing\DTOs;

final readonly class ComplianceAlertData
{
    public function __construct(
        public string $code,
        public string $message,
        public string $severity = 'info',
    ) {}
}
