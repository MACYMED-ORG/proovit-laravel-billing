<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Customers;

use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;

final class CreateCustomerAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): Customer
    {
        $company = null;

        if (isset($data['company_uuid_identifier'])) {
            $company = Company::query()->where('uuid_identifier', $data['company_uuid_identifier'])->firstOrFail();
        } elseif (isset($data['company_id'])) {
            $company = Company::query()->findOrFail((int) $data['company_id']);
        }

        unset($data['company_id'], $data['company_uuid_identifier']);

        return Customer::create([
            'company_id' => $company?->id,
            ...$data,
        ]);
    }
}
