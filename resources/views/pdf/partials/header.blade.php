<section class="hero">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 60%; vertical-align: top; padding-right: 18px;">
                <div class="eyebrow">{{ __('billing::pdf.document_title') }}</div>
                <h1>{{ $documentType }} <span>{{ $invoice->number ?? __('billing::pdf.draft') }}</span></h1>
                <p class="subtitle">
                    {{ collect([
                        $companySnapshot['display_name'] ?? null,
                        $companySnapshot['legal_name'] ?? $company?->legal_name ?? null,
                    ])->filter()->implode(' · ') ?: __('billing::pdf.seller') }}
                    @if ($establishment)
                        · {{ $establishment->name ?? $establishment->label ?? __('billing::pdf.seller') }}
                    @endif
                </p>
            </td>
            <td style="width: 40%; vertical-align: top;">
                <table style="width: 100%; border-collapse: separate; border-spacing: 8px;">
                    <tr>
                        <td style="width: 50%;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 12px 14px; background: #1f2937; color: #ffffff; border: 1px solid #334155;">
                                        <div style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.14em; color: #cbd5e1; margin-bottom: 4px;">{{ __('billing::pdf.status') }}</div>
                                        <div style="font-size: 16px; font-weight: 700; color: #ffffff;">{{ $statusLabel }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 50%;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 12px 14px; background: #1f2937; color: #ffffff; border: 1px solid #334155;">
                                        <div style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.14em; color: #cbd5e1; margin-bottom: 4px;">{{ __('billing::pdf.type') }}</div>
                                        <div style="font-size: 16px; font-weight: 700; color: #ffffff;">{{ $documentType }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</section>
