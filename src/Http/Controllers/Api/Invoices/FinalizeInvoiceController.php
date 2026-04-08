<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Invoices\FinalizeInvoiceAction;
use Proovit\Billing\Http\Requests\Api\Invoices\FinalizeInvoiceRequest;
use Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\InvoiceSeries;

#[Group('Invoices')]
final class FinalizeInvoiceController extends Controller
{
    public function __invoke(FinalizeInvoiceRequest $request, Invoice $invoice, FinalizeInvoiceAction $finalizeInvoice): InvoiceResource
    {
        $payload = $request->validated();
        $series = null;

        if (isset($payload['invoice_series_uuid_identifier'])) {
            $series = InvoiceSeries::query()
                ->where('uuid_identifier', $payload['invoice_series_uuid_identifier'])
                ->firstOrFail();
        }

        if (isset($payload['invoice_series_id'])) {
            $series = InvoiceSeries::query()->findOrFail((int) $payload['invoice_series_id']);
        }

        return new InvoiceResource($finalizeInvoice->handle($invoice, $series)->loadMissing(['company', 'customer', 'series', 'reservation', 'quote', 'lines', 'payments.allocations']));
    }
}
