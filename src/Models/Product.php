<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read Company|null $company
 * @property-read string|null $name
 * @property-read string|null $sku
 * @property-read string|null $default_currency
 */
final class Product extends BillingModel
{
    protected $table = 'billing_products';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class, 'product_id');
    }

    public function invoiceLines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class, 'product_id');
    }

    public function quoteLines(): HasMany
    {
        return $this->hasMany(QuoteLine::class, 'product_id');
    }

    public function creditNoteLines(): HasMany
    {
        return $this->hasMany(CreditNoteLine::class, 'product_id');
    }

    public function isReferencedInDocuments(): bool
    {
        return $this->invoiceLines()->exists()
            || $this->quoteLines()->exists()
            || $this->creditNoteLines()->exists();
    }

    public function canManagePrices(): bool
    {
        return ! $this->isReferencedInDocuments();
    }

    public function canEditCatalog(): bool
    {
        return ! $this->isReferencedInDocuments();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
