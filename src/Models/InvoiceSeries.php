<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read Company|null $company
 * @property-read CompanyEstablishment|null $establishment
 * @property-read string|null $name
 * @property-read string|null $prefix
 * @property-read string|null $pattern
 * @property-read int|null $current_sequence
 */
final class InvoiceSeries extends BillingModel
{
    protected $table = 'billing_invoice_series';

    protected $guarded = [];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(CompanyEstablishment::class, 'establishment_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(InvoiceNumberReservation::class, 'invoice_series_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'invoice_series_id');
    }
}
