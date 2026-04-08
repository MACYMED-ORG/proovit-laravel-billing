<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Proovit\Billing\Enums\PaymentMethodType;
use Proovit\Billing\Enums\PaymentStatus;

/**
 * @property-read Company|null $company
 * @property-read Customer|null $customer
 * @property-read Invoice|null $invoice
 * @property-read PaymentAllocation[]|Collection<int, PaymentAllocation> $allocations
 * @property-read PaymentMethodType|null $method
 * @property-read PaymentStatus|null $status
 * @property-read string|null $amount
 * @property-read Carbon|null $paid_at
 * @property-read string|null $reference
 * @property-read string|null $notes
 */
final class Payment extends BillingModel
{
    protected $table = 'billing_payments';

    protected $guarded = [];

    protected $casts = [
        'status' => PaymentStatus::class,
        'method' => PaymentMethodType::class,
        'amount' => 'decimal:2',
        'paid_at' => 'date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(PaymentAllocation::class, 'payment_id');
    }
}
