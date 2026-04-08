<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Payment|null $payment
 * @property-read Invoice|null $invoice
 * @property-read string|null $amount
 * @property-read string|null $applied_amount
 */
final class PaymentAllocation extends BillingModel
{
    protected $table = 'billing_payment_allocations';

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
