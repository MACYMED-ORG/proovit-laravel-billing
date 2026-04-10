<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Invoices;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Proovit\Billing\Models\InvoiceNumberReservation;

/**
 * @mixin InvoiceNumberReservation
 */
final class InvoiceNumberReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid_identifier' => $this->uuid_identifier,
            'invoice_series_id' => $this->invoice_series_id,
            'company_id' => $this->company_id,
            'number' => $this->number,
            'sequence' => $this->sequence,
            'reserved_at' => $this->reserved_at?->toIso8601String(),
            'consumed_at' => $this->consumed_at?->toIso8601String(),
        ];
    }
}
