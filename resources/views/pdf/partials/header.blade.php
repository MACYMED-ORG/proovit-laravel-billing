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
                <table style="width: 100%; border-collapse: separate; border-spacing: 10px;">
                    <tr>
                        <td style="width: 50%;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 14px 16px; background: #1f2937; color: #ffffff; border: 1px solid #334155;">
                                        <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.14em; color: #cbd5e1; margin-bottom: 6px;">{{ __('billing::pdf.status') }}</div>
                                        <div style="font-size: 18px; font-weight: 700; color: #ffffff;">{{ $statusLabel }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 50%;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 14px 16px; background: #1f2937; color: #ffffff; border: 1px solid #334155;">
                                        <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.14em; color: #cbd5e1; margin-bottom: 6px;">{{ __('billing::pdf.type') }}</div>
                                        <div style="font-size: 18px; font-weight: 700; color: #ffffff;">{{ $documentType }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 14px 16px; background: #1f2937; color: #ffffff; border: 1px solid #334155;">
                                        <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.14em; color: #cbd5e1; margin-bottom: 6px;">{{ __('billing::pdf.issued_on') }}</div>
                                        <div style="font-size: 18px; font-weight: 700; color: #ffffff;">{{ $formatDate($invoice->issued_at) }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 50%;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 14px 16px; background: #1f2937; color: #ffffff; border: 1px solid #334155;">
                                        <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.14em; color: #cbd5e1; margin-bottom: 6px;">{{ __('billing::pdf.due_on') }}</div>
                                        <div style="font-size: 18px; font-weight: 700; color: #ffffff;">{{ $formatDate($invoice->due_at) }}</div>
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
