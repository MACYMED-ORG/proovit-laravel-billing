<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Quotes;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Quotes\ConvertQuoteToInvoiceAction;
use Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource;
use Proovit\Billing\Models\Quote;

#[Group('Quotes', description: 'Manage quotes and quote-to-invoice conversion.')]
final class ConvertQuoteToInvoiceController extends Controller
{
    #[Endpoint(
        operationId: 'convertQuoteToInvoice',
        title: 'Convert quote to invoice',
        description: 'Create a linked invoice from a quote and persist the relationship.'
    )]
    #[Response(status: 201, type: 'Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource', description: 'Converted invoice created from the selected quote and linked back to it.')]
    public function __invoke(Quote $quote, ConvertQuoteToInvoiceAction $convertQuoteToInvoice): JsonResponse
    {
        $invoice = $convertQuoteToInvoice->handle($quote->loadMissing(['company', 'customer', 'lines']));

        return (new InvoiceResource($invoice->loadMissing(['company', 'customer', 'series', 'reservation', 'quote', 'lines', 'payments.allocations'])))->response()->setStatusCode(201);
    }
}
