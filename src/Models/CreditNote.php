<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Proovit\Billing\Enums\CreditNoteStatus;

final class CreditNote extends BillingModel
{
    protected $table = 'billing_credit_notes';

    protected $guarded = [];

    protected $casts = [
        'status' => CreditNoteStatus::class,
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

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(CreditNoteLine::class, 'credit_note_id');
    }
}
