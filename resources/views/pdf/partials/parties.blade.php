<table class="cards-2">
    <tr>
        <td style="width: 50%; padding-right: 10px; vertical-align: top;">
            <article class="card">
                <div class="section-label">{{ __('billing::pdf.seller') }}</div>
                <h2>{{ $companySnapshot['display_name'] ?? $companySnapshot['legal_name'] ?? $company?->legal_name ?? '—' }}</h2>
                <table class="identity-grid">
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.company_identity') }}</div><div class="value">{{ $companySnapshot['legal_form'] ?? $company?->legal_form ?? '—' }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.legal_name') }}</div><div class="value">{{ $companySnapshot['legal_name'] ?? $company?->legal_name ?? '—' }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.billing_address') }}</div><div class="value">{{ $formatAddress($companyAddress) }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.contact') }}</div><div class="value">{{ $formatContact([
                        'email' => $companySnapshot['contact_email'] ?? $company?->email,
                        'phone' => $companySnapshot['contact_phone'] ?? $company?->phone,
                    ]) }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.vat_number') }}</div><div class="value">{{ $companySnapshot['vat_number'] ?? '—' }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.siren') }}</div><div class="value">{{ $companySnapshot['siren'] ?? '—' }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.siret') }}</div><div class="value">{{ $companySnapshot['siret'] ?? '—' }}</div></td></tr>
                </table>
            </article>
        </td>
        <td style="width: 50%; vertical-align: top;">
            <article class="card">
                <div class="section-label">{{ __('billing::pdf.customer') }}</div>
                <h2>{{ $customerSnapshot['legal_name'] ?? $customerSnapshot['full_name'] ?? $customer?->legal_name ?? $customer?->full_name ?? '—' }}</h2>
                <table class="identity-grid">
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.customer_reference') }}</div><div class="value">{{ $customerSnapshot['reference'] ?? $customer?->reference ?? '—' }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.billing_address') }}</div><div class="value">{{ $formatAddress($customerAddress) }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.contact') }}</div><div class="value">{{ $formatContact($customerSnapshot['contact'] ?? null) }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.email') }}</div><div class="value">{{ $customerSnapshot['email'] ?? $customer?->email ?? '—' }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.phone') }}</div><div class="value">{{ data_get($customerSnapshot, 'contact.phone') ?? $customer?->phone ?? '—' }}</div></td></tr>
                    <tr><td class="field"><div class="label">{{ __('billing::pdf.vat_number') }}</div><div class="value">{{ $customerSnapshot['vat_number'] ?? '—' }}</div></td></tr>
                </table>
            </article>
        </td>
    </tr>
</table>

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
