<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Requests\Api\Invoices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
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
            'seller' => ['required', 'array'],
            'customer' => ['required', 'array'],
            'lines' => ['required', 'array', 'min:1'],
            'currency' => ['nullable', 'string', 'size:3'],
            'type' => ['nullable', 'string', Rule::in(['invoice', 'credit_note', 'quote'])],
            'numbering' => ['nullable', 'array'],
            'company_id' => ['nullable', 'integer'],
            'company_uuid_identifier' => ['nullable', 'uuid'],
            'customer_id' => ['nullable', 'integer'],
            'customer_uuid_identifier' => ['nullable', 'uuid'],
        ];
    }
}
