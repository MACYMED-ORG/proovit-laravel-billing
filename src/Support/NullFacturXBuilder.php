<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Proovit\Billing\Contracts\FacturXBuilderInterface;
use Proovit\Billing\DTOs\InvoiceDraftData;

final class NullFacturXBuilder implements FacturXBuilderInterface
{
    public function build(InvoiceDraftData $draft): string
    {
        return sprintf(
            '<factur-x draft-lines="%d" currency="%s" />',
            count($draft->lines),
            $draft->currency
        );
    }
}
