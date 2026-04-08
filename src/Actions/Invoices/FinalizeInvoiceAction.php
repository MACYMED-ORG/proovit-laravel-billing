<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Invoices;

use Illuminate\Support\Facades\DB;
use Proovit\Billing\Enums\InvoiceStatus;
use Proovit\Billing\Events\InvoiceFinalized;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\InvoiceSeries;

final class FinalizeInvoiceAction
{
    public function __construct(private readonly ReserveInvoiceNumberAction $reserveInvoiceNumber) {}

    public function handle(Invoice $invoice, ?InvoiceSeries $series = null): Invoice
    {
        return DB::transaction(function () use ($invoice, $series): Invoice {
            if ($invoice->status === InvoiceStatus::Finalized) {
                return $invoice;
            }

            $series ??= $invoice->series;
            if (! $series instanceof InvoiceSeries) {
                throw new \RuntimeException('An invoice series is required to finalize an invoice.');
            }

            $reservation = $this->reserveInvoiceNumber->handle(
                $series,
                $invoice->id,
                $invoice->document_type->value
            );

            $invoice->forceFill([
                'invoice_series_id' => $series->id,
                'invoice_number_reservation_id' => $reservation->id,
                'number' => $reservation->number,
                'status' => InvoiceStatus::Finalized->value,
                'finalized_at' => now(),
            ])->save();

            $reservation->forceFill(['consumed_at' => now()])->save();

            $invoice = $invoice->refresh();

            event(new InvoiceFinalized($invoice));

            return $invoice;
        });
    }
}
