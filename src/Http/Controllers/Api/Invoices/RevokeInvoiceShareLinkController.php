<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Invoices\RevokeInvoiceShareLinkAction;
use Proovit\Billing\Http\Requests\Api\Invoices\RevokeInvoiceShareLinkRequest;
use Proovit\Billing\Models\Invoice;

#[Group('Invoices', description: 'Manage invoices, payments, credit notes, and public share links.')]
final class RevokeInvoiceShareLinkController extends Controller
{
    #[Endpoint(
        operationId: 'revokeInvoiceShareLink',
        title: 'Revoke invoice share link',
        description: 'Remove the public share link from an invoice and invalidate the token.'
    )]
    #[Response(
        status: 200,
        type: 'array{data: array{invoice_uuid_identifier: string, public_share_url: ?string, public_share_token: ?string, public_share_expires_at: ?string}}',
        description: 'Revoke the public share link for an invoice.'
    )]
    public function __invoke(
        RevokeInvoiceShareLinkRequest $request,
        Invoice $invoice,
        RevokeInvoiceShareLinkAction $revokeInvoiceShareLinkAction
    ): JsonResponse {
        $invoice = $revokeInvoiceShareLinkAction->handle($invoice);

        return response()->json([
            'data' => [
                'invoice_uuid_identifier' => $invoice->uuid_identifier,
                'public_share_url' => null,
                'public_share_token' => null,
                'public_share_expires_at' => null,
            ],
        ]);
    }
}
