<?php

declare(strict_types=1);

namespace Proovit\Billing\Enums;

use Proovit\Billing\Enums\Concerns\HasTranslatableLabel;

enum PaymentStatus: string
{
    use HasTranslatableLabel;

    case Pending = 'pending';
    case PartiallyPaid = 'partially_paid';
    case Paid = 'paid';
    case Failed = 'failed';
    case Refunded = 'refunded';
}
