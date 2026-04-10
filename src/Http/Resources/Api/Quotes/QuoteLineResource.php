<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Quotes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Proovit\Billing\Models\QuoteLine;

/**
 * @mixin QuoteLine
 */
final class QuoteLineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid_identifier' => $this->uuid_identifier,
            'quote_id' => $this->quote_id,
            'product_id' => $this->product_id,
            'tax_rate_id' => $this->tax_rate_id,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'discount_amount' => $this->discount_amount,
            'tax_rate' => $this->tax_rate,
            'subtotal_amount' => $this->subtotal_amount,
            'tax_amount' => $this->tax_amount,
            'total_amount' => $this->total_amount,
            'sort_order' => $this->sort_order,
        ];
    }
}
