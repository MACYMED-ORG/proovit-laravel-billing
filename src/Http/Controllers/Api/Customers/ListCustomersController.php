<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Customers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Customers\ListCustomersAction;
use Proovit\Billing\Http\Resources\Api\Customers\CustomerResource;

#[Group('Customers', description: 'List, create, view, update, and delete billing customers and their related addresses.')]
final class ListCustomersController extends Controller
{
    #[Endpoint(
        operationId: 'listCustomers',
        title: 'List customers',
        description: 'Return the paginated customer directory for the selected billing company.'
    )]
    #[Response(type: 'Illuminate\Http\Resources\Json\AnonymousResourceCollection<Proovit\Billing\Http\Resources\Api\Customers\CustomerResource>', description: 'Paginated customers with company, addresses, billing identifiers, and contact data.')]
    public function __invoke(Request $request, ListCustomersAction $listCustomersAction): AnonymousResourceCollection
    {
        return CustomerResource::collection(
            $listCustomersAction->handle($request->string('search')->toString() ?: null)
        );
    }
}
