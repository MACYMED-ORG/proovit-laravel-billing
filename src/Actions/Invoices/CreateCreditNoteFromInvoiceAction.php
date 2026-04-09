<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Invoices;

use Illuminate\Support\Facades\DB;
use Proovit\Billing\Enums\CreditNoteStatus;
use Proovit\Billing\Events\CreditNoteCreated;
use Proovit\Billing\Models\CreditNote;
use Proovit\Billing\Models\CreditNoteLine;
use Proovit\Billing\Models\Invoice;

final class CreateCreditNoteFromInvoiceAction
{
    public function handle(Invoice $invoice): CreditNote
    {
        return DB::transaction(function () use ($invoice): CreditNote {
            $creditNote = CreditNote::create([
                'company_id' => $invoice->company_id,
                'invoice_id' => $invoice->id,
                'status' => CreditNoteStatus::Draft->value,
                'seller_snapshot' => $invoice->seller_snapshot,
                'customer_snapshot' => $invoice->customer_snapshot,
                'subtotal_amount' => $invoice->subtotal_amount,
                'tax_amount' => $invoice->tax_amount,
                'total_amount' => $invoice->total_amount,
            ]);

            foreach ($invoice->lines as $line) {
                CreditNoteLine::create([
                    'credit_note_id' => $creditNote->id,
                    'product_id' => $line->getAttribute('product_id'),
                    'tax_rate_id' => $line->getAttribute('tax_rate_id'),
                    'description' => $line->description,
                    'quantity' => $line->quantity,
                    'unit_price' => $line->unit_price,
                    'discount_amount' => $line->discount_amount,
                    'tax_rate' => $line->tax_rate,
                    'subtotal_amount' => $line->subtotal_amount,
                    'tax_amount' => $line->tax_amount,
                    'total_amount' => $line->total_amount,
                ]);
            }

            $creditNote = $creditNote->load('lines');

            event(new CreditNoteCreated($creditNote));

            return $creditNote;
        });
    }
}
