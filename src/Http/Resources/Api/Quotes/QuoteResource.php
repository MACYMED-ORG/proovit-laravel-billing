<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Quotes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class QuoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid_identifier' => $this->uuid_identifier,
            'company_id' => $this->company_id,
            'customer_id' => $this->customer_id,
            'converted_invoice_id' => $this->converted_invoice_id,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'number' => $this->number,
            'currency' => $this->company?->default_currency ?? 'EUR',
            'seller_snapshot' => $this->seller_snapshot,
            'customer_snapshot' => $this->customer_snapshot,
            'subtotal_amount' => $this->subtotal_amount,
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total_amount,
            'company' => $this->resource->relationLoaded('company') ? [
                'id' => $this->company?->id,
                'uuid_identifier' => $this->company?->uuid_identifier,
                'legal_name' => $this->company?->legal_name,
                'display_name' => $this->company?->display_name,
            ] : null,
            'customer' => $this->resource->relationLoaded('customer') ? [
                'id' => $this->customer?->id,
                'uuid_identifier' => $this->customer?->uuid_identifier,
                'legal_name' => $this->customer?->legal_name,
                'full_name' => $this->customer?->full_name,
                'reference' => $this->customer?->reference,
            ] : null,
            'converted_invoice' => $this->resource->relationLoaded('convertedInvoice') ? [
                'id' => $this->convertedInvoice?->id,
                'uuid_identifier' => $this->convertedInvoice?->uuid_identifier,
                'number' => $this->convertedInvoice?->number,
            ] : null,
            'lines' => QuoteLineResource::collection($this->whenLoaded('lines')),
        ];
    }
}
