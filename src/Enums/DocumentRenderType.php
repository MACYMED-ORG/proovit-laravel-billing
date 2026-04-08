<?php

declare(strict_types=1);

namespace Proovit\Billing\Enums;

use Proovit\Billing\Enums\Concerns\HasTranslatableLabel;

enum DocumentRenderType: string
{
    use HasTranslatableLabel;

    case Html = 'html';
    case Pdf = 'pdf';
    case Xml = 'xml';
    case FacturX = 'factur_x';
}
