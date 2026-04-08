<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Quotes;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Proovit\Billing\Models\Quote;

final class ListQuotesAction
{
    public function handle(): LengthAwarePaginator
    {
        return Quote::query()
            ->with(['company', 'customer', 'lines', 'convertedInvoice'])
            ->latest('id')
            ->paginate(15);
    }
}
