<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Proovit\Billing\Contracts\InvoiceValidatorInterface;
use Proovit\Billing\DTOs\InvoiceDraftData;

final class ConfigInvoiceValidator implements InvoiceValidatorInterface
{
    public function validate(InvoiceDraftData $draft): array
    {
        $errors = [];

        if ($draft->lines === []) {
            $errors[] = 'invoice.lines.required';
        }

        return $errors;
    }
}
