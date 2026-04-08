<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Customers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid_identifier' => $this->uuid_identifier,
            'company_id' => $this->company_id,
            'legal_name' => $this->legal_name,
            'full_name' => $this->full_name,
            'reference' => $this->reference,
            'email' => $this->email,
            'phone' => $this->phone,
            'vat_number' => $this->vat_number,
            'billing_address' => $this->billing_address,
            'shipping_address' => $this->shipping_address,
            'addresses' => $this->resource->relationLoaded('addresses')
                ? $this->addresses->map(fn ($address): array => [
                    'id' => $address->id,
                    'uuid_identifier' => $address->uuid_identifier,
                    'label' => $address->label,
                    'line1' => $address->line1,
                    'line2' => $address->line2,
                    'postal_code' => $address->postal_code,
                    'city' => $address->city,
                    'region' => $address->region,
                    'country' => $address->country,
                    'is_default_billing' => (bool) $address->is_default_billing,
                    'is_default_shipping' => (bool) $address->is_default_shipping,
                ])->all()
                : null,
            'company' => $this->resource->relationLoaded('company') ? [
                'id' => $this->company?->id,
                'uuid_identifier' => $this->company?->uuid_identifier,
                'legal_name' => $this->company?->legal_name,
                'display_name' => $this->company?->display_name,
            ] : null,
        ];
    }
}
