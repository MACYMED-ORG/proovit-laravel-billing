<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Customers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Customers\UpdateCustomerAction;
use Proovit\Billing\Http\Requests\Api\Customers\UpdateCustomerRequest;
use Proovit\Billing\Http\Resources\Api\Customers\CustomerResource;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;

#[Group('Customers')]
final class UpdateCustomerController extends Controller
{
    public function __invoke(UpdateCustomerRequest $request, Customer $customer, UpdateCustomerAction $updateCustomerAction): CustomerResource
    {
        $payload = $request->validated();
        $companyId = $this->resolveCompanyId($payload);

        return new CustomerResource($updateCustomerAction->handle($customer, $payload, $companyId));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function resolveCompanyId(array $payload): ?int
    {
        if (isset($payload['company_uuid_identifier'])) {
            return Company::query()->where('uuid_identifier', $payload['company_uuid_identifier'])->firstOrFail()->id;
        }

        return isset($payload['company_id']) ? (int) $payload['company_id'] : null;
    }
}
