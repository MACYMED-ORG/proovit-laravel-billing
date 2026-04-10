<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource;
use Proovit\Billing\Models\Invoice;

#[Group('Invoices', description: 'Manage invoices, payments, credit notes, and public share links.')]
final class ListInvoicesController extends Controller
{
    #[Endpoint(
        operationId: 'listInvoices',
        title: 'List invoices',
        description: 'Return the paginated invoice register for the selected billing company.'
    )]
    #[Response(description: 'Paginated invoices with company, customer, quote, series, totals, lines, payments, and public share metadata.')]
    public function __invoke(): JsonResponse
    {
        $invoices = Invoice::query()
            ->with(['company', 'customer', 'lines', 'payments'])
            ->latest('id')
            ->paginate(15);

        return InvoiceResource::collection($invoices)->response();
    }
}
