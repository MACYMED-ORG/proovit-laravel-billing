<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Shared;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Proovit\Billing\Models\CustomerAddress;

/**
 * @mixin CustomerAddress
 */
final class CustomerAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid_identifier' => $this->uuid_identifier,
            'label' => $this->label,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
            'region' => $this->region,
            'country' => $this->country,
            'is_default_billing' => (bool) $this->is_default_billing,
            'is_default_shipping' => (bool) $this->is_default_shipping,
        ];
    }
}
