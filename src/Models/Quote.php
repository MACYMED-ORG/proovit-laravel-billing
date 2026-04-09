<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Proovit\Billing\Enums\QuoteStatus;

/**
 * @property-read Company|null $company
 * @property-read Customer|null $customer
 * @property-read Invoice|null $convertedInvoice
 * @property-read Collection<int, QuoteLine> $lines
 * @property-read QuoteStatus|null $status
 * @property-read string|null $number
 * @property-read string|null $subtotal_amount
 * @property-read string|null $tax_amount
 * @property-read string|null $total_amount
 */
final class Quote extends BillingModel
{
    protected $table = 'billing_quotes';

    protected $guarded = [];

    protected $casts = [
        'status' => QuoteStatus::class,
        'converted_invoice_id' => 'integer',
        'seller_snapshot' => 'array',
        'customer_snapshot' => 'array',
        'subtotal_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(QuoteLine::class, 'quote_id');
    }

    public function convertedInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'converted_invoice_id');
    }

    public function canManageLineItems(): bool
    {
        $status = $this->getAttribute('status');
        $statusValue = $status instanceof QuoteStatus ? $status->value : (string) $status;

        return $this->getAttribute('converted_invoice_id') === null
            && in_array($statusValue, [QuoteStatus::Draft->value, QuoteStatus::Sent->value], true);
    }
}
