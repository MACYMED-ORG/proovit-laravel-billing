<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Web\Invoices;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;
use Proovit\Billing\Models\Invoice;

final class ShowInvoicePrintController extends Controller
{
    public function __invoke(Request $request, Invoice $invoice)
    {
        return response()->view('billing::web.invoices.print', [
            'document' => InvoiceDocumentData::fromInvoice($invoice)->toViewModel(),
            'invoice_model' => $invoice,
            'shared' => false,
        ]);
    }
}
