<?php

declare(strict_types=1);

namespace Proovit\Billing\Enums;

use Proovit\Billing\Enums\Concerns\HasTranslatableLabel;

enum CreditNoteStatus: string
{
    use HasTranslatableLabel;

    case Draft = 'draft';
    case Finalized = 'finalized';
    case Voided = 'voided';
}
