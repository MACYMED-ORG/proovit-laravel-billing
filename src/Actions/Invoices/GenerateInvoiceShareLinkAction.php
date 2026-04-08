<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Invoices;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Proovit\Billing\Models\Invoice;

final class GenerateInvoiceShareLinkAction
{
    public function handle(Invoice $invoice, ?Carbon $expiresAt = null): string
    {
        $token = $invoice->public_share_token ?: Str::random(64);
        $expiresAt ??= now()->addDays((int) config('billing.public_shares.expires_days', 30));

        $invoice->forceFill([
            'public_share_token' => $token,
            'public_shared_at' => now(),
            'public_share_expires_at' => $expiresAt,
        ])->save();

        return URL::temporarySignedRoute(
            'billing.public.invoices.show',
            $expiresAt,
            ['token' => $token]
        );
    }
}
