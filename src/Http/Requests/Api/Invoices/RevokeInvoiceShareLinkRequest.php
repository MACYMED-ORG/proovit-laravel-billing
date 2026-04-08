<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Requests\Api\Invoices;

use Illuminate\Foundation\Http\FormRequest;

final class RevokeInvoiceShareLinkRequest extends FormRequest
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
        return [];
    }
}
