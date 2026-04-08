<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Requests\Api\Invoices;

use Illuminate\Foundation\Http\FormRequest;

final class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['nullable', 'string', 'max:32'],
            'customer_id' => ['nullable', 'integer'],
            'customer_uuid_identifier' => ['nullable', 'uuid'],
        ];
    }
}
