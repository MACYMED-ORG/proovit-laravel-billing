<?php

declare(strict_types=1);

namespace Proovit\Billing\Enums;

use Proovit\Billing\Enums\Concerns\HasTranslatableLabel;

enum InvoiceStatus: string
{
    use HasTranslatableLabel;

    case Draft = 'draft';
    case Pending = 'pending';
    case Finalized = 'finalized';
    case Paid = 'paid';
    case Cancelled = 'cancelled';
    case Overdue = 'overdue';
}
