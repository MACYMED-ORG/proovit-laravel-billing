<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Quotes;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Quotes\ListQuotesAction;
use Proovit\Billing\Http\Resources\Api\Quotes\QuoteResource;

#[Group('Quotes', description: 'Manage quotes and quote-to-invoice conversion.')]
final class ListQuotesController extends Controller
{
    #[Endpoint(
        operationId: 'listQuotes',
        title: 'List quotes',
        description: 'Return the paginated quote register for the selected billing company.'
    )]
    #[Response(description: 'Paginated quotes with company, customer, conversion, lines, and monetary totals.')]
    public function __invoke(ListQuotesAction $listQuotesAction): AnonymousResourceCollection
    {
        return QuoteResource::collection($listQuotesAction->handle());
    }
}
