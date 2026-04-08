<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Quotes;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Quotes\ConvertQuoteToInvoiceAction;
use Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource;
use Proovit\Billing\Models\Quote;

#[Group('Quotes')]
final class ConvertQuoteToInvoiceController extends Controller
{
    public function __invoke(Quote $quote, ConvertQuoteToInvoiceAction $convertQuoteToInvoice): JsonResponse
    {
        $invoice = $convertQuoteToInvoice->handle($quote->loadMissing(['company', 'customer', 'lines']));

        return (new InvoiceResource($invoice->loadMissing(['company', 'customer', 'series', 'reservation', 'quote', 'lines', 'payments.allocations'])))->response()->setStatusCode(201);
    }
}
