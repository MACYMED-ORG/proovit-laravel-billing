<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Customers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Customers\ListCustomersAction;
use Proovit\Billing\Http\Resources\Api\Customers\CustomerResource;

#[Group('Customers')]
final class ListCustomersController extends Controller
{
    public function __invoke(Request $request, ListCustomersAction $listCustomersAction): AnonymousResourceCollection
    {
        return CustomerResource::collection(
            $listCustomersAction->handle($request->string('search')->toString() ?: null)
        );
    }
}
