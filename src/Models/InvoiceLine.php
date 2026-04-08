<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Invoice|null $invoice
 * @property-read Product|null $product
 * @property-read TaxRate|null $taxRate
 * @property-read string|null $sort_order
 * @property-read string|null $description
 * @property-read string|null $quantity
 * @property-read string|null $unit_price
 * @property-read string|null $discount_amount
 * @property-read string|null $tax_rate
 * @property-read string|null $subtotal_amount
 * @property-read string|null $tax_amount
 * @property-read string|null $total_amount
 */
final class InvoiceLine extends BillingModel
{
    protected $table = 'billing_invoice_lines';

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_rate' => 'decimal:4',
        'subtotal_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class, 'tax_rate_id');
    }
}
