<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Customers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Proovit\Billing\Models\Customer;

final class ListCustomersAction
{
    public function handle(?string $search = null): LengthAwarePaginator
    {
        return Customer::query()
            ->with('company')
            ->when($search, function ($query, string $search): void {
                $query->where(function ($nested) use ($search): void {
                    $nested->where('legal_name', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%")
                        ->orWhere('reference', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate(15);
    }
}
