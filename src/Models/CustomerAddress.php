<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CustomerAddress extends BillingModel
{
    protected $table = 'billing_customer_addresses';

    protected $guarded = [];

    protected $casts = [
        'is_default' => 'bool',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
