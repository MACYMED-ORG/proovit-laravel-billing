<table class="party-grid">
    <tr>
        <td class="party-cell">
            <article class="card">
                <div class="party-label">{{ __('billing::pdf.seller') }}</div>
                <div class="party-name">{{ $companySnapshot['display_name'] ?? $companySnapshot['legal_name'] ?? $company?->legal_name ?? '—' }}</div>
                <table class="detail-list">
                    <tr><td class="detail-label">{{ __('billing::pdf.legal_name') }}</td><td class="detail-value">{{ $companySnapshot['legal_name'] ?? $company?->legal_name ?? '—' }}</td></tr>
                    <tr><td class="detail-label">{{ __('billing::pdf.billing_address') }}</td><td class="detail-value">{{ $formatAddress($companyAddress) }}</td></tr>
                    <tr><td class="detail-label">{{ __('billing::pdf.contact') }}</td><td class="detail-value">{{ $formatContact([
                        'email' => $companySnapshot['contact_email'] ?? $company?->email,
                        'phone' => $companySnapshot['contact_phone'] ?? $company?->phone,
                    ]) }}</td></tr>
                    <tr><td class="detail-label">{{ __('billing::pdf.vat_number') }}</td><td class="detail-value">{{ $companySnapshot['vat_number'] ?? '—' }}</td></tr>
                </table>
            </article>
        </td>
        <td class="party-cell">
            <article class="card">
                <div class="party-label">{{ __('billing::pdf.customer') }}</div>
                <div class="party-name">{{ $customerSnapshot['legal_name'] ?? $customerSnapshot['full_name'] ?? $customer?->legal_name ?? $customer?->full_name ?? '—' }}</div>
                <table class="detail-list">
                    <tr><td class="detail-label">{{ __('billing::pdf.customer_reference') }}</td><td class="detail-value">{{ $customerSnapshot['reference'] ?? $customer?->reference ?? '—' }}</td></tr>
                    <tr><td class="detail-label">{{ __('billing::pdf.billing_address') }}</td><td class="detail-value">{{ $formatAddress($customerAddress) }}</td></tr>
                    <tr><td class="detail-label">{{ __('billing::pdf.contact') }}</td><td class="detail-value">{{ $formatContact($customerSnapshot['contact'] ?? null) }}</td></tr>
                    <tr><td class="detail-label">{{ __('billing::pdf.email') }}</td><td class="detail-value">{{ $customerSnapshot['email'] ?? $customer?->email ?? '—' }}</td></tr>
                    <tr><td class="detail-label">{{ __('billing::pdf.phone') }}</td><td class="detail-value">{{ data_get($customerSnapshot, 'contact.phone') ?? $customer?->phone ?? '—' }}</td></tr>
                </table>
            </article>
        </td>
    </tr>
</table>
