<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Invoices;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Proovit\Billing\Enums\SequenceResetPolicy;
use Proovit\Billing\Models\InvoiceNumberReservation;
use Proovit\Billing\Models\InvoiceSeries;
use Proovit\Billing\ValueObjects\SequencePattern;

final class ReserveInvoiceNumberAction
{
    public function handle(InvoiceSeries $series, int $invoiceId, string $documentType): InvoiceNumberReservation
    {
        return DB::transaction(function () use ($series, $invoiceId, $documentType): InvoiceNumberReservation {
            $lockedSeries = InvoiceSeries::query()
                ->whereKey($series->id)
                ->lockForUpdate()
                ->firstOrFail();

            $sequence = ((int) $lockedSeries->current_sequence) + 1;
            $pattern = new SequencePattern(
                prefix: $lockedSeries->prefix ?? 'INV',
                suffix: $lockedSeries->suffix,
                pattern: $lockedSeries->pattern,
                padding: (int) $lockedSeries->padding,
                reset: SequenceResetPolicy::from($lockedSeries->reset_policy),
            );

            $reservation = InvoiceNumberReservation::create([
                'invoice_series_id' => $lockedSeries->id,
                'company_id' => $lockedSeries->company_id,
                'invoice_id' => $invoiceId,
                'document_type' => $documentType,
                'number' => $pattern->format($sequence, new DateTimeImmutable),
                'sequence' => $sequence,
                'reserved_at' => now(),
            ]);

            $lockedSeries->forceFill(['current_sequence' => $sequence])->save();

            return $reservation;
        });
    }
}
