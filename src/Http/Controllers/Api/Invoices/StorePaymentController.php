<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Invoices\RegisterPaymentAction;
use Proovit\Billing\Http\Requests\Api\Invoices\StorePaymentRequest;
use Proovit\Billing\Http\Resources\Api\Invoices\PaymentResource;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\Invoice;

#[Group('Invoices')]
final class StorePaymentController extends Controller
{
    public function __invoke(StorePaymentRequest $request, Invoice $invoice, RegisterPaymentAction $registerPayment): JsonResponse
    {
        $payload = $request->validated();

        $payment = $registerPayment->handle(
            $invoice->loadMissing(['company', 'customer']),
            (string) $payload['amount'],
            $payload['method'] ?? null,
            $this->resolveCustomerId($payload)
        );

        return (new PaymentResource($payment->loadMissing(['allocations'])))->response()->setStatusCode(201);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function resolveCustomerId(array $payload): ?int
    {
        if (isset($payload['customer_uuid_identifier'])) {
            return Customer::query()->where('uuid_identifier', $payload['customer_uuid_identifier'])->firstOrFail()->id;
        }

        return isset($payload['customer_id']) ? (int) $payload['customer_id'] : null;
    }
}
