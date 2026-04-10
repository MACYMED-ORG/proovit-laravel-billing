<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Customers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Customers\ShowCustomerAction;
use Proovit\Billing\Http\Resources\Api\Customers\CustomerResource;
use Proovit\Billing\Models\Customer;

#[Group('Customers', description: 'List, create, view, update, and delete billing customers and their related addresses.')]
final class ShowCustomerController extends Controller
{
    #[Endpoint(
        operationId: 'showCustomer',
        title: 'View customer',
        description: 'Return a single billing customer with its addresses and company context.'
    )]
    #[Response(type: 'Proovit\Billing\Http\Resources\Api\Customers\CustomerResource', description: 'Single customer with company context and loaded addresses.')]
    public function __invoke(Customer $customer, ShowCustomerAction $showCustomerAction): CustomerResource
    {
        return new CustomerResource($showCustomerAction->handle($customer));
    }
}
