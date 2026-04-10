<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Customers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response as ScrambleResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Customers\DeleteCustomerAction;
use Proovit\Billing\Models\Customer;

#[Group('Customers', description: 'List, create, view, update, and delete billing customers and their related addresses.')]
final class DeleteCustomerController extends Controller
{
    #[Endpoint(
        operationId: 'deleteCustomer',
        title: 'Delete customer',
        description: 'Delete a billing customer if it has no blocking usage or traceability constraint.'
    )]
    #[ScrambleResponse(status: 204, description: 'Customer deleted.')]
    public function __invoke(Customer $customer, DeleteCustomerAction $deleteCustomerAction): HttpResponse
    {
        $deleteCustomerAction->handle($customer);

        return response()->noContent();
    }
}
