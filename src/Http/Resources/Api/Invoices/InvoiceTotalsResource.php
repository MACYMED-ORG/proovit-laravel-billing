<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Invoices;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class InvoiceTotalsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'subtotal_amount' => data_get($this->resource, 'subtotal_amount'),
            'tax_amount' => data_get($this->resource, 'tax_amount'),
            'total_amount' => data_get($this->resource, 'total_amount'),
            'paid_amount' => data_get($this->resource, 'paid_amount'),
            'balance_due' => data_get($this->resource, 'balance_due'),
        ];
    }
}
