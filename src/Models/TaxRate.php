<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class TaxRate extends BillingModel
{
    protected $table = 'billing_tax_rates';

    protected $guarded = [];

    protected $casts = [
        'rate' => 'decimal:4',
        'is_default' => 'bool',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function productPrices(): HasMany
    {
        return $this->hasMany(ProductPrice::class, 'tax_rate_id');
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }
}
