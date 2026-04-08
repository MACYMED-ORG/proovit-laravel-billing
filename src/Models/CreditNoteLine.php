<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

final class CreditNoteLine extends BillingModel
{
    protected $table = 'billing_credit_note_lines';

    protected $guarded = [];
}
