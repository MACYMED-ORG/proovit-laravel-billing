<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Company|null $company
 * @property-read string|null $name
 * @property-read string|null $label
 * @property-read array<string, mixed>|null $address
 */
final class CompanyEstablishment extends BillingModel
{
    protected $table = 'billing_company_establishments';

    protected $guarded = [];

    protected $casts = [
        'address' => 'array',
        'is_default' => 'bool',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
