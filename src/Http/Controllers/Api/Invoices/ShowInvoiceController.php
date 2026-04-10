<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Routing\Controller;
use Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource;
use Proovit\Billing\Models\Invoice;

#[Group('Invoices', description: 'Manage invoices, payments, credit notes, and public share links.')]
final class ShowInvoiceController extends Controller
{
    #[Endpoint(
        operationId: 'showInvoice',
        title: 'View invoice',
        description: 'Return a single invoice with its relations, totals, and share data.'
    )]
    #[Response(type: 'Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource', description: 'Single invoice with company, customer, series, reservation, quote, lines, payments, totals, and share link data.')]
    public function __invoke(Invoice $invoice): InvoiceResource
    {
        return new InvoiceResource($invoice->loadMissing(['company', 'customer', 'series', 'reservation', 'quote', 'lines', 'payments.invoice', 'payments.allocations']));
    }
}
