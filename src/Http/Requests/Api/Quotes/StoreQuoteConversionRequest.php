<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Requests\Api\Quotes;

use Illuminate\Foundation\Http\FormRequest;

final class StoreQuoteConversionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
