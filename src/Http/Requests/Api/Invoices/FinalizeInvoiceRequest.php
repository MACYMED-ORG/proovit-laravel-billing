<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Requests\Api\Invoices;

use Illuminate\Foundation\Http\FormRequest;

final class FinalizeInvoiceRequest extends FormRequest
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
            'invoice_series_id' => ['nullable', 'integer'],
            'invoice_series_uuid_identifier' => ['nullable', 'uuid'],
        ];
    }
}
