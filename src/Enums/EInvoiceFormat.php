<?php

declare(strict_types=1);

namespace Proovit\Billing\Enums;

use Proovit\Billing\Enums\Concerns\HasTranslatableLabel;

enum EInvoiceFormat: string
{
    use HasTranslatableLabel;

    case FacturX = 'factur_x';
    case UBL = 'ubl';
    case CII = 'cii';
}
