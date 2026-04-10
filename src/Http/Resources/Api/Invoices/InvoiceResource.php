<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Invoices;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Proovit\Billing\Http\Resources\Api\Customers\CustomerResource;
use Proovit\Billing\Http\Resources\Api\Shared\CompanyResource;
use Proovit\Billing\Models\Invoice;

/**
 * @mixin Invoice
 */
final class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $paidTotal = $this->resource->relationLoaded('payments')
            ? $this->resource->payments->sum(fn ($payment) => (float) $payment->amount)
            : 0.0;

        $total = (float) $this->total_amount;
        $balance = max(0, $total - (float) $paidTotal);

        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'customer_id' => $this->customer_id,
            'invoice_series_id' => $this->invoice_series_id,
            'quote_id' => $this->quote_id,
            'invoice_number_reservation_id' => $this->invoice_number_reservation_id,
            'public_share_url' => $this->public_share_token ? $this->publicShareUrl() : null,
            'public_shared_at' => $this->public_shared_at?->toIso8601String(),
            'public_share_expires_at' => $this->public_share_expires_at?->toIso8601String(),
            'number' => $this->number,
            'document_type' => $this->document_type?->value,
            'document_type_label' => $this->document_type?->label(),
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'currency' => $this->currency,
            'issued_at' => $this->issued_at?->toDateString(),
            'due_at' => $this->due_at?->toDateString(),
            'finalized_at' => $this->finalized_at?->toIso8601String(),
            'seller_snapshot' => $this->seller_snapshot,
            'customer_snapshot' => $this->customer_snapshot,
            'series' => $this->resource->relationLoaded('series')
                ? InvoiceSeriesResource::make($this->series)
                : null,
            'reservation' => $this->resource->relationLoaded('reservation')
                ? InvoiceNumberReservationResource::make($this->reservation)
                : null,
            'quote' => $this->resource->relationLoaded('quote')
                ? InvoiceReferenceResource::make($this->quote)
                : ($this->quote_id ? [
                    'id' => $this->quote_id,
                ] : null),
            'totals' => InvoiceTotalsResource::make([
                'subtotal_amount' => $this->subtotal_amount,
                'tax_amount' => $this->tax_amount,
                'total_amount' => $this->total_amount,
                'paid_amount' => number_format((float) $paidTotal, 2, '.', ''),
                'balance_due' => number_format($balance, 2, '.', ''),
            ]),
            'company' => $this->resource->relationLoaded('company')
                ? CompanyResource::make($this->company)
                : null,
            'customer' => $this->resource->relationLoaded('customer')
                ? CustomerResource::make($this->customer)
                : null,
            'lines' => $this->resource->relationLoaded('lines')
                ? InvoiceLineResource::collection($this->lines)
                : null,
            'payments' => $this->resource->relationLoaded('payments')
                ? PaymentResource::collection($this->payments)
                : null,
        ];
    }
}
