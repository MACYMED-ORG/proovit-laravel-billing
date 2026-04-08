<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read InvoiceSeries|null $series
 * @property-read Company|null $company
 * @property-read string|null $number
 * @property-read int|null $sequence
 * @property-read Carbon|null $reserved_at
 * @property-read Carbon|null $consumed_at
 */
final class InvoiceNumberReservation extends BillingModel
{
    protected $table = 'billing_invoice_number_reservations';

    protected $guarded = [];

    protected $casts = [
        'reserved_at' => 'datetime',
        'consumed_at' => 'datetime',
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(InvoiceSeries::class, 'invoice_series_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
