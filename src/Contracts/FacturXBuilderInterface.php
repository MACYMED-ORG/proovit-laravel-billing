<?php

declare(strict_types=1);

namespace Proovit\Billing\Contracts;

use Proovit\Billing\DTOs\InvoiceDraftData;

interface FacturXBuilderInterface
{
    public function build(InvoiceDraftData $draft): string;
}
