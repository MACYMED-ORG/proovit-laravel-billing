<?php

declare(strict_types=1);

namespace Proovit\Billing\DTOs;

use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\DueDatePolicy;
use Proovit\Billing\ValueObjects\SequencePattern;

final readonly class InvoiceDraftData
{
    /**
     * @param  array<int, InvoiceLineData>  $lines
     */
    public function __construct(
        public CompanyIdentitySnapshot $seller,
        public CustomerIdentitySnapshot $customer,
        public array $lines,
        public string $currency = 'EUR',
        public ?DueDatePolicy $dueDatePolicy = null,
        public InvoiceType $type = InvoiceType::Invoice,
        public ?SequencePattern $numbering = null,
    ) {}
}
