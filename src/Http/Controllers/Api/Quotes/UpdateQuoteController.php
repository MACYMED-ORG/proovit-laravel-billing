<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Quotes;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Quotes\UpdateQuoteAction;
use Proovit\Billing\Http\Requests\Api\Quotes\UpdateQuoteRequest;
use Proovit\Billing\Http\Resources\Api\Quotes\QuoteResource;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\Quote;
use Proovit\Billing\Support\InvoiceDraftPayloadMapper;

#[Group('Quotes', description: 'Manage quotes and quote-to-invoice conversion.')]
final class UpdateQuoteController extends Controller
{
    #[Endpoint(
        operationId: 'updateQuote',
        title: 'Update quote',
        description: 'Update a quote before conversion and refresh its totals.'
    )]
    #[Response(type: 'Proovit\Billing\Http\Resources\Api\Quotes\QuoteResource', description: 'Updated quote with refreshed company, customer, conversion, lines, and totals.')]
    public function __invoke(UpdateQuoteRequest $request, Quote $quote, UpdateQuoteAction $updateQuoteAction, InvoiceDraftPayloadMapper $mapper): QuoteResource
    {
        $payload = array_merge($request->validated(), ['type' => 'quote']);
        $companyId = $this->resolveCompanyId($payload);
        $customerId = $this->resolveCustomerId($payload);

        return new QuoteResource(
            $updateQuoteAction->handle(
                $quote,
                $mapper->map($payload),
                $companyId,
                $customerId,
            )
        );
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
