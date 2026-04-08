<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource;
use Proovit\Billing\Models\Invoice;

#[Group('Invoices')]
final class ListInvoicesController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $invoices = Invoice::query()
            ->with(['company', 'customer', 'lines', 'payments'])
            ->latest('id')
            ->paginate(15);

        return InvoiceResource::collection($invoices)->response();
    }
}
