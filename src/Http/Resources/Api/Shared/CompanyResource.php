<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Resources\Api\Shared;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Proovit\Billing\Models\Company;

/**
 * @mixin Company
 */
final class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid_identifier' => $this->uuid_identifier,
            'legal_name' => $this->legal_name,
            'display_name' => $this->display_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'default_currency' => $this->default_currency,
            'default_locale' => $this->default_locale,
        ];
    }
}
