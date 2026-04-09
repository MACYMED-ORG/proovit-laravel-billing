<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string|null $amount
 * @property-read string|null $currency
 */
final class ProductPrice extends BillingModel
{
    protected $table = 'billing_product_prices';

    protected $guarded = [];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class, 'tax_rate_id');
    }
}
