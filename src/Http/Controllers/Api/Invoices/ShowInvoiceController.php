<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Routing\Controller;
use Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource;
use Proovit\Billing\Models\Invoice;

#[Group('Invoices')]
final class ShowInvoiceController extends Controller
{
    public function __invoke(Invoice $invoice): InvoiceResource
    {
        return new InvoiceResource($invoice->loadMissing(['company', 'customer', 'series', 'reservation', 'quote', 'lines', 'payments.allocations']));
    }
}
