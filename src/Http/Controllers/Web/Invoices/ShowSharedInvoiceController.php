<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Web\Invoices;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;
use Proovit\Billing\Models\Invoice;

final class ShowSharedInvoiceController extends Controller
{
    public function __invoke(Request $request, string $token)
    {
        $invoice = Invoice::query()
            ->where('public_share_token', $token)
            ->where(function ($query): void {
                $query->whereNull('public_share_expires_at')
                    ->orWhere('public_share_expires_at', '>', now());
            })
            ->firstOrFail();

        return response()->view('billing::web.invoices.preview', [
            'document' => InvoiceDocumentData::fromInvoice($invoice)->toViewModel(),
            'invoice_model' => $invoice,
            'shared' => true,
        ]);
    }
}
