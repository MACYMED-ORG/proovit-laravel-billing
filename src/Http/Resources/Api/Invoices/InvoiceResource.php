<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Invoices;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'series' => $this->series ? [
                'id' => $this->series->id,
                'name' => $this->series->name,
                'prefix' => $this->series->prefix,
                'pattern' => $this->series->pattern,
                'current_sequence' => $this->series->current_sequence,
            ] : null,
            'reservation' => $this->reservation ? [
                'id' => $this->reservation->id,
                'number' => $this->reservation->number,
                'sequence' => $this->reservation->sequence,
                'reserved_at' => $this->reservation->reserved_at?->toIso8601String(),
                'consumed_at' => $this->reservation->consumed_at?->toIso8601String(),
            ] : null,
            'quote' => $this->resource->relationLoaded('quote') ? [
                'id' => $this->quote?->id,
                'uuid_identifier' => $this->quote?->uuid_identifier,
                'number' => $this->quote?->number,
                'status' => $this->quote?->status?->value,
            ] : ($this->quote_id ? [
                'id' => $this->quote_id,
            ] : null),
            'totals' => [
                'subtotal_amount' => $this->subtotal_amount,
                'tax_amount' => $this->tax_amount,
                'total_amount' => $this->total_amount,
                'paid_amount' => number_format((float) $paidTotal, 2, '.', ''),
                'balance_due' => number_format($balance, 2, '.', ''),
            ],
            'company' => $this->resource->relationLoaded('company') ? [
                'id' => $this->company?->id,
                'legal_name' => $this->company?->legal_name,
                'display_name' => $this->company?->display_name,
                'email' => $this->company?->email,
                'phone' => $this->company?->phone,
            ] : null,
            'customer' => $this->resource->relationLoaded('customer') ? [
                'id' => $this->customer?->id,
                'legal_name' => $this->customer?->legal_name,
                'full_name' => $this->customer?->full_name,
                'reference' => $this->customer?->reference,
                'email' => $this->customer?->email,
                'phone' => $this->customer?->phone,
            ] : null,
            'lines' => InvoiceLineResource::collection($this->whenLoaded('lines')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
        ];
    }
}
