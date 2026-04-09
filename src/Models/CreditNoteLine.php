<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Proovit\Billing\Models\Concerns\CalculatesLineAmounts;

final class CreditNoteLine extends BillingModel
{
    use CalculatesLineAmounts;

    protected $table = 'billing_credit_note_lines';

    protected $guarded = [];

    public function creditNote(): BelongsTo
    {
        return $this->belongsTo(CreditNote::class, 'credit_note_id');
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
