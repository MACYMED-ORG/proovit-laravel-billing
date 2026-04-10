<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Invoices;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Proovit\Billing\Http\Resources\Api\Shared\CompanyResource;
use Proovit\Billing\Models\CreditNote;

/**
 * @mixin CreditNote
 */
final class CreditNoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'company_id' => $this->company_id,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'number' => $this->number,
            'subtotal_amount' => $this->subtotal_amount,
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total_amount,
            'company' => $this->resource->relationLoaded('company')
                ? CompanyResource::make($this->company)
                : null,
            'invoice' => $this->resource->relationLoaded('invoice')
                ? InvoiceReferenceResource::make($this->invoice)
                : null,
            'lines' => $this->resource->relationLoaded('lines')
                ? InvoiceLineResource::collection($this->lines)
                : null,
        ];
    }
}
