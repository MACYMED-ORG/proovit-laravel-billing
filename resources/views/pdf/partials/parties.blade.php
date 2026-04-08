<div class="cards-2">
    <article class="card">
        <div class="section-label">{{ __('billing::pdf.seller') }}</div>
        <h2>{{ $companySnapshot['display_name'] ?? $companySnapshot['legal_name'] ?? $company?->legal_name ?? '—' }}</h2>
        <div class="identity-grid">
            <div class="field">
                <div class="label">{{ __('billing::pdf.company_identity') }}</div>
                <div class="value">{{ $companySnapshot['legal_form'] ?? $company?->legal_form ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.legal_name') }}</div>
                <div class="value">{{ $companySnapshot['legal_name'] ?? $company?->legal_name ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.billing_address') }}</div>
                <div class="value">{{ $formatAddress($companyAddress) }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.contact') }}</div>
                <div class="value">{{ $formatContact([
                    'email' => $companySnapshot['contact_email'] ?? $company?->email,
                    'phone' => $companySnapshot['contact_phone'] ?? $company?->phone,
                ]) }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.vat_number') }}</div>
                <div class="value">{{ $companySnapshot['vat_number'] ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.siren') }}</div>
                <div class="value">{{ $companySnapshot['siren'] ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.siret') }}</div>
                <div class="value">{{ $companySnapshot['siret'] ?? '—' }}</div>
            </div>
        </div>
    </article>

    <article class="card">
        <div class="section-label">{{ __('billing::pdf.customer') }}</div>
        <h2>{{ $customerSnapshot['legal_name'] ?? $customerSnapshot['full_name'] ?? $customer?->legal_name ?? $customer?->full_name ?? '—' }}</h2>
        <div class="identity-grid">
            <div class="field">
                <div class="label">{{ __('billing::pdf.customer_reference') }}</div>
                <div class="value">{{ $customerSnapshot['reference'] ?? $customer?->reference ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.billing_address') }}</div>
                <div class="value">{{ $formatAddress($customerAddress) }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.contact') }}</div>
                <div class="value">{{ $formatContact($customerSnapshot['contact'] ?? null) }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.email') }}</div>
                <div class="value">{{ $customerSnapshot['email'] ?? $customer?->email ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.phone') }}</div>
                <div class="value">{{ data_get($customerSnapshot, 'contact.phone') ?? $customer?->phone ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.vat_number') }}</div>
                <div class="value">{{ $customerSnapshot['vat_number'] ?? '—' }}</div>
            </div>
        </div>
    </article>
</div>

<div class="cards-2">
    <article class="card">
        <div class="section-label">{{ __('billing::pdf.series') }}</div>
        <h2>{{ $invoice->series?->name ?? '—' }}</h2>
        <div class="identity-grid">
            <div class="field">
                <div class="label">{{ __('billing::pdf.reference') }}</div>
                <div class="value">{{ $invoice->reservation?->number ?? $invoice->number ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.sequence') }}</div>
                <div class="value">{{ $invoice->reservation?->sequence ?? $invoice->series?->current_sequence ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.generated_at') }}</div>
                <div class="value">{{ $formatDateTime(now()) }}</div>
            </div>
        </div>
    </article>

    <article class="card">
        <div class="section-label">{{ __('billing::pdf.bank_details') }}</div>
        <h2>{{ $bankAccount?->bank_name ?? __('billing::pdf.bank_details') }}</h2>
        <div class="identity-grid">
            <div class="field">
                <div class="label">{{ __('billing::pdf.account_holder') }}</div>
                <div class="value">{{ $bankAccount?->account_holder ?? $companySnapshot['display_name'] ?? $companySnapshot['legal_name'] ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.iban') }}</div>
                <div class="value">{{ $bankAccount?->iban ?? '—' }}</div>
            </div>
            <div class="field">
                <div class="label">{{ __('billing::pdf.bic') }}</div>
                <div class="value">{{ $bankAccount?->bic ?? '—' }}</div>
            </div>
        </div>
    </article>
</div>
