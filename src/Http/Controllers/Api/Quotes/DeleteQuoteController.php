<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Quotes;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response as ScrambleResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Quotes\DeleteQuoteAction;
use Proovit\Billing\Models\Quote;

#[Group('Quotes', description: 'Manage quotes and quote-to-invoice conversion.')]
final class DeleteQuoteController extends Controller
{
    #[Endpoint(
        operationId: 'deleteQuote',
        title: 'Delete quote',
        description: 'Delete a quote if it is still allowed by business traceability rules.'
    )]
    #[ScrambleResponse(status: 204, description: 'Quote deleted.')]
    public function __invoke(Quote $quote, DeleteQuoteAction $deleteQuoteAction): HttpResponse
    {
        $deleteQuoteAction->handle($quote);

        return response()->noContent();
    }
}
