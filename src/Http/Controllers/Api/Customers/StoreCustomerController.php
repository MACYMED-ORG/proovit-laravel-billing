<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Customers;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Customers\CreateCustomerAction;
use Proovit\Billing\Http\Requests\Api\Customers\StoreCustomerRequest;
use Proovit\Billing\Http\Resources\Api\Customers\CustomerResource;

#[Group('Customers', description: 'List, create, view, update, and delete billing customers and their related addresses.')]
final class StoreCustomerController extends Controller
{
    #[Endpoint(
        operationId: 'storeCustomer',
        title: 'Create customer',
        description: 'Create a billing customer together with its company and address context.'
    )]
    #[Response(status: 201, type: 'Proovit\Billing\Http\Resources\Api\Customers\CustomerResource', description: 'Created customer with company context, addresses, and related invoices loaded.')]
    public function __invoke(StoreCustomerRequest $request, CreateCustomerAction $createCustomerAction): JsonResponse
    {
        $customer = $createCustomerAction->handle($request->validated());

        return (new CustomerResource($customer->loadMissing(['company', 'addresses', 'invoices'])))->response()->setStatusCode(201);
    }
}
