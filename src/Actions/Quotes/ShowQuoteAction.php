<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Quotes;

use Proovit\Billing\Models\Quote;

final class ShowQuoteAction
{
    public function handle(Quote $quote): Quote
    {
        return $quote->loadMissing(['company', 'customer', 'convertedInvoice', 'lines']);
    }
}
