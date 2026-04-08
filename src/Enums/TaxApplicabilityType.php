<?php

declare(strict_types=1);

namespace Proovit\Billing\Enums;

use Proovit\Billing\Enums\Concerns\HasTranslatableLabel;

enum TaxApplicabilityType: string
{
    use HasTranslatableLabel;

    case Standard = 'standard';
    case Exempt = 'exempt';
    case ReverseCharge = 'reverse_charge';
    case OutOfScope = 'out_of_scope';
}
