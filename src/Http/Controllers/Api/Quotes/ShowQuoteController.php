<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Quotes;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Quotes\ShowQuoteAction;
use Proovit\Billing\Http\Resources\Api\Quotes\QuoteResource;
use Proovit\Billing\Models\Quote;

#[Group('Quotes', description: 'Manage quotes and quote-to-invoice conversion.')]
final class ShowQuoteController extends Controller
{
    #[Endpoint(
        operationId: 'showQuote',
        title: 'View quote',
        description: 'Return a single quote with its lines, totals, and invoice conversion state.'
    )]
    #[Response(type: 'Proovit\Billing\Http\Resources\Api\Quotes\QuoteResource', description: 'Single quote with company, customer, conversion reference, lines, and totals.')]
    public function __invoke(Quote $quote, ShowQuoteAction $showQuoteAction): QuoteResource
    {
        return new QuoteResource($showQuoteAction->handle($quote));
    }
}
