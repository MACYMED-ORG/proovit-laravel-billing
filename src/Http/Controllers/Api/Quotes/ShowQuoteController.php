<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Quotes;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Quotes\ShowQuoteAction;
use Proovit\Billing\Http\Resources\Api\Quotes\QuoteResource;
use Proovit\Billing\Models\Quote;

#[Group('Quotes')]
final class ShowQuoteController extends Controller
{
    public function __invoke(Quote $quote, ShowQuoteAction $showQuoteAction): QuoteResource
    {
        return new QuoteResource($showQuoteAction->handle($quote));
    }
}
