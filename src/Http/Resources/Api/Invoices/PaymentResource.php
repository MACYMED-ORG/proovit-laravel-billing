<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Invoices;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        ];
    }
}
