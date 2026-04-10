<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Invoices\GenerateInvoiceShareLinkAction;
use Proovit\Billing\Http\Requests\Api\Invoices\GenerateInvoiceShareLinkRequest;
use Proovit\Billing\Models\Invoice;

#[Group('Invoices', description: 'Manage invoices, payments, credit notes, and public share links.')]
final class GenerateInvoiceShareLinkController extends Controller
{
    #[Endpoint(
        operationId: 'generateInvoiceShareLink',
        title: 'Generate invoice share link',
        description: 'Create or refresh the public share link for an invoice.'
    )]
    #[Response(
        status: 200,
        type: 'array{data: array{invoice_uuid_identifier: string, public_share_url: string, public_share_token: string, public_share_expires_at: ?string}}',
        description: 'Generate or refresh the public share link for an invoice.'
    )]
    public function __invoke(
        GenerateInvoiceShareLinkRequest $request,
        Invoice $invoice,
        GenerateInvoiceShareLinkAction $generateInvoiceShareLinkAction
    ): JsonResponse {
        $expiresAt = $request->filled('expires_days')
            ? now()->addDays((int) $request->integer('expires_days'))
            : null;

        $url = $generateInvoiceShareLinkAction->handle(
            $invoice,
            $expiresAt,
            $request->boolean('regenerate')
        );

        return response()->json([
            'data' => [
                'invoice_uuid_identifier' => $invoice->uuid_identifier,
                'public_share_url' => $url,
                'public_share_token' => $invoice->public_share_token,
                'public_share_expires_at' => $invoice->public_share_expires_at?->toIso8601String(),
            ],
        ]);
    }
}
