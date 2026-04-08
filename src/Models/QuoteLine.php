<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

/**
 * @property-read Quote|null $quote
 * @property-read Product|null $product
 * @property-read string|null $description
 * @property-read string|null $quantity
 * @property-read string|null $unit_price
 * @property-read string|null $discount_amount
 * @property-read string|null $tax_rate
 * @property-read string|null $subtotal_amount
 * @property-read string|null $tax_amount
 * @property-read string|null $total_amount
 */
final class QuoteLine extends BillingModel
{
    protected $table = 'billing_quote_lines';

    protected $guarded = [];
}
