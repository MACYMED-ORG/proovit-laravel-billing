<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Invoices;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Proovit\Billing\Models\InvoiceSeries;

/**
 * @mixin InvoiceSeries
 */
final class InvoiceSeriesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid_identifier' => $this->uuid_identifier,
            'company_id' => $this->company_id,
            'establishment_id' => $this->establishment_id,
            'name' => $this->name,
            'prefix' => $this->prefix,
            'pattern' => $this->pattern,
            'current_sequence' => $this->current_sequence,
        ];
    }
}
