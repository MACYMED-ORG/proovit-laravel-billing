<?php

declare(strict_types=1);

namespace Proovit\Billing\Enums;

use Proovit\Billing\Enums\Concerns\HasTranslatableLabel;

enum SequenceResetPolicy: string
{
    use HasTranslatableLabel;

    case Never = 'never';
    case Monthly = 'monthly';
    case Annual = 'annual';
}
