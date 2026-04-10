<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Quotes;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Quotes\CreateQuoteAction;
use Proovit\Billing\Http\Requests\Api\Quotes\StoreQuoteRequest;
use Proovit\Billing\Http\Resources\Api\Quotes\QuoteResource;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Support\InvoiceDraftPayloadMapper;

#[Group('Quotes', description: 'Manage quotes and quote-to-invoice conversion.')]
final class StoreQuoteController extends Controller
{
    #[Endpoint(
        operationId: 'storeQuote',
        title: 'Create quote',
        description: 'Create a new draft quote with customer, lines, and totals.'
    )]
    #[Response(status: 201, type: 'Proovit\Billing\Http\Resources\Api\Quotes\QuoteResource', description: 'Created quote with company, customer, lines, and totals.')]
    public function __invoke(StoreQuoteRequest $request, CreateQuoteAction $createQuoteAction, InvoiceDraftPayloadMapper $mapper): JsonResponse
    {
        $payload = array_merge($request->validated(), ['type' => 'quote']);
        $companyId = $this->resolveCompanyId($payload);
        $customerId = $this->resolveCustomerId($payload);

        $quote = $createQuoteAction->handle(
            $mapper->map($payload),
            $companyId,
            $customerId,
        );

        return (new QuoteResource($quote->loadMissing(['company', 'customer', 'convertedInvoice', 'lines'])))->response()->setStatusCode(201);
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
