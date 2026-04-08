<table class="cards-2" style="margin-top: 14px;">
    <tr>
        <td style="width: 50%; padding-right: 10px; vertical-align: top;">
            <article class="card">
                <div class="section-label">{{ __('billing::pdf.series') }}</div>
                <h2>{{ $invoice->series?->name ?? '—' }}</h2>
                <table class="identity-grid">
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.reference') }}</div><div class="value">{{ $invoice->reservation?->number ?? $invoice->number ?? '—' }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.sequence') }}</div><div class="value">{{ $invoice->reservation?->sequence ?? $invoice->series?->current_sequence ?? '—' }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.generated_at') }}</div><div class="value">{{ $formatDateTime(now()) }}</div></td></tr>
                </table>
            </article>
        </td>
        <td style="width: 50%; vertical-align: top;">
            <article class="card">
                <div class="section-label">{{ __('billing::pdf.bank_details') }}</div>
                <h2>{{ $bankAccount?->bank_name ?? __('billing::pdf.bank_details') }}</h2>
                <table class="identity-grid">
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.account_holder') }}</div><div class="value">{{ $bankAccount?->account_holder ?? $companySnapshot['display_name'] ?? $companySnapshot['legal_name'] ?? '—' }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.iban') }}</div><div class="value">{{ $bankAccount?->iban ?? '—' }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.bic') }}</div><div class="value">{{ $bankAccount?->bic ?? '—' }}</div></td></tr>
                </table>
            </article>
        </td>
    </tr>
</table>
