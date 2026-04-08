<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Quotes;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Quotes\DeleteQuoteAction;
use Proovit\Billing\Models\Quote;

#[Group('Quotes')]
final class DeleteQuoteController extends Controller
{
    public function __invoke(Quote $quote, DeleteQuoteAction $deleteQuoteAction): Response
    {
        $deleteQuoteAction->handle($quote);

        return response()->noContent();
    }
}
