<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Invoices;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Proovit\Billing\Models\Invoice;

/**
 * @mixin Invoice
 */
final class InvoiceReferenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid_identifier' => $this->uuid_identifier,
            'number' => $this->number,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
        ];
    }
}
