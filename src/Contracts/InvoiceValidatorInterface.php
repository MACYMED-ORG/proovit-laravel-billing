<?php

declare(strict_types=1);

namespace Proovit\Billing\Contracts;

use Proovit\Billing\DTOs\InvoiceDraftData;

interface InvoiceValidatorInterface
{
    /**
     * @return array<int, string>
     */
    public function validate(InvoiceDraftData $draft): array;
}
