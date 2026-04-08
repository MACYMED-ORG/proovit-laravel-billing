<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Requests\Api\Invoices;

use Illuminate\Foundation\Http\FormRequest;

final class GenerateInvoiceShareLinkRequest extends FormRequest
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
            'regenerate' => ['sometimes', 'boolean'],
            'expires_days' => ['sometimes', 'integer', 'min:1', 'max:3650'],
        ];
    }
}
