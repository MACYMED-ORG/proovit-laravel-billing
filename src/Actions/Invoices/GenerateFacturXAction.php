<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Invoices;

use Proovit\Billing\Contracts\FacturXBuilderInterface;
use Proovit\Billing\DTOs\InvoiceDraftData;

final class GenerateFacturXAction
{
    public function __construct(private readonly FacturXBuilderInterface $builder) {}

    public function handle(InvoiceDraftData $draft): string
    {
        return $this->builder->build($draft);
    }
}
