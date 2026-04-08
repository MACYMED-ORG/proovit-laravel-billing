<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Web\Invoices;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

        $invoice->loadMissing(['company.defaultBankAccount', 'company.defaultEstablishment', 'customer', 'establishment', 'reservation', 'series', 'lines.product', 'lines.taxRate', 'payments.allocations']);

        return response()->view('billing::pdf.invoice', [
            'invoice' => $invoice,
        ]);
    }
}
