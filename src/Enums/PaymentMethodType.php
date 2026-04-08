<?php

declare(strict_types=1);

namespace Proovit\Billing\Enums;

use Proovit\Billing\Enums\Concerns\HasTranslatableLabel;

enum PaymentMethodType: string
{
    use HasTranslatableLabel;

    case BankTransfer = 'bank_transfer';
    case DirectDebit = 'direct_debit';
    case Card = 'card';
    case Cash = 'cash';
    case Cheque = 'cheque';
    case Other = 'other';
}
