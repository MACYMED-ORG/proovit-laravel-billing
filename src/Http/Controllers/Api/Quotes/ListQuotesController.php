<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Quotes;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Quotes\ListQuotesAction;
use Proovit\Billing\Http\Resources\Api\Quotes\QuoteResource;

#[Group('Quotes')]
final class ListQuotesController extends Controller
{
    public function __invoke(ListQuotesAction $listQuotesAction): AnonymousResourceCollection
    {
        return QuoteResource::collection($listQuotesAction->handle());
    }
}
