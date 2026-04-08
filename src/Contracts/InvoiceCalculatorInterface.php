<?php

declare(strict_types=1);

namespace Proovit\Billing\Contracts;

use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceTotalsData;

interface InvoiceCalculatorInterface
{
    public function calculate(InvoiceDraftData $draft): InvoiceTotalsData;
}
