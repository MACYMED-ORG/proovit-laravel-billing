<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Proovit\Billing\Contracts\LegalMentionResolverInterface;
use Proovit\Billing\ValueObjects\LegalMentionSet;

final class ConfigLegalMentionResolver implements LegalMentionResolverInterface
{
    public function resolve(array $context = []): LegalMentionSet
    {
        $mentions = array_filter([
            config('billing.invoice.legal_mentions.vat_exempt') ? __('billing::pdf.legal_mentions.vat_exempt') : null,
            config('billing.invoice.legal_mentions.late_payment') ? __('billing::pdf.legal_mentions.late_payment') : null,
            config('billing.invoice.legal_mentions.penalties') ? __('billing::pdf.legal_mentions.penalties') : null,
        ]);

        return new LegalMentionSet(array_values($mentions));
    }
}
