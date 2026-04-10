<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Customers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Proovit\Billing\Http\Resources\Api\Shared\CompanyResource;
use Proovit\Billing\Http\Resources\Api\Shared\CustomerAddressResource;
use Proovit\Billing\Models\Customer;

/**
 * @mixin Customer
 */
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
                ? CustomerAddressResource::collection($this->addresses)
                : null,
            'company' => $this->resource->relationLoaded('company')
                ? CompanyResource::make($this->company)
                : null,
        ];
    }
}
