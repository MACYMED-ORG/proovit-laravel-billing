<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Quotes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Proovit\Billing\Http\Resources\Api\Customers\CustomerResource;
use Proovit\Billing\Http\Resources\Api\Invoices\InvoiceReferenceResource;
use Proovit\Billing\Http\Resources\Api\Shared\CompanyResource;
use Proovit\Billing\Models\Quote;

/**
 * @mixin Quote
 */
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
            'company' => $this->resource->relationLoaded('company')
                ? CompanyResource::make($this->company)
                : null,
            'customer' => $this->resource->relationLoaded('customer')
                ? CustomerResource::make($this->customer)
                : null,
            'converted_invoice' => $this->resource->relationLoaded('convertedInvoice')
                ? InvoiceReferenceResource::make($this->convertedInvoice)
                : null,
            'lines' => $this->resource->relationLoaded('lines')
                ? QuoteLineResource::collection($this->lines)
                : null,
        ];
    }
}
