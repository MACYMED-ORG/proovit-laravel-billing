<?php

declare(strict_types=1);

namespace Proovit\Billing\Enums;

use Proovit\Billing\Enums\Concerns\HasTranslatableLabel;

enum InvoiceType: string
{
    use HasTranslatableLabel;

    case Invoice = 'invoice';
    case CreditNote = 'credit_note';
    case Quote = 'quote';
}
