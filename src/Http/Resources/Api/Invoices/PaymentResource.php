<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Invoices;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Proovit\Billing\Http\Resources\Api\Customers\CustomerResource;
use Proovit\Billing\Http\Resources\Api\Shared\CompanyResource;
use Proovit\Billing\Models\Payment;

/**
 * @mixin Payment
 */
final class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'company_id' => $this->company_id,
            'customer_id' => $this->customer_id,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'method' => $this->method?->value,
            'method_label' => $this->method?->label(),
            'currency' => $this->currency,
            'amount' => $this->amount,
            'paid_at' => $this->paid_at?->toDateString(),
            'company' => $this->resource->relationLoaded('company')
                ? CompanyResource::make($this->company)
                : null,
            'customer' => $this->resource->relationLoaded('customer')
                ? CustomerResource::make($this->customer)
                : null,
            'invoice' => $this->resource->relationLoaded('invoice')
                ? InvoiceReferenceResource::make($this->invoice)
                : null,
        ];
    }
}
