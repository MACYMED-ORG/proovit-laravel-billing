<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Proovit\Billing\Enums\ReminderChannel;

final class Reminder extends BillingModel
{
    protected $table = 'billing_reminders';

    protected $guarded = [];

    protected $casts = [
        'channel' => ReminderChannel::class,
        'sent_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
