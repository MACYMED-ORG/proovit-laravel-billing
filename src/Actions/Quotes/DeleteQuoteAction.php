<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Quotes;

use Proovit\Billing\Models\Quote;

final class DeleteQuoteAction
{
    public function handle(Quote $quote): void
    {
        $quote->delete();
    }
}
