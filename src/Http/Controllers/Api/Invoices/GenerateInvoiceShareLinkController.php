<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Invoices\GenerateInvoiceShareLinkAction;
use Proovit\Billing\Http\Requests\Api\Invoices\GenerateInvoiceShareLinkRequest;
use Proovit\Billing\Models\Invoice;

#[Group('Invoices')]
final class GenerateInvoiceShareLinkController extends Controller
{
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
