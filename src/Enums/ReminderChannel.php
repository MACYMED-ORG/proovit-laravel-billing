<?php

declare(strict_types=1);

namespace Proovit\Billing\Enums;

use Proovit\Billing\Enums\Concerns\HasTranslatableLabel;

enum ReminderChannel: string
{
    use HasTranslatableLabel;

    case Email = 'email';
    case Sms = 'sms';
    case Letter = 'letter';
    case Internal = 'internal';
}
