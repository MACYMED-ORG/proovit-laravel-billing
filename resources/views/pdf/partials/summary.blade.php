<div class="summary">
    <div class="card">
        <div class="section-label">{{ __('billing::pdf.subtotal') }}</div>
        <div class="metric">{{ $formatMoney($subtotalAmount) }}</div>
        <div class="hint">{{ __('billing::pdf.currency') }}: {{ $currency }}</div>
    </div>
    <div class="card">
        <div class="section-label">{{ __('billing::pdf.tax_total') }}</div>
        <div class="metric">{{ $formatMoney($taxAmount) }}</div>
        <div class="hint">{{ __('billing::pdf.vat_number') }}</div>
    </div>
    <div class="card">
        <div class="section-label">{{ __('billing::pdf.total') }}</div>
        <div class="metric">{{ $formatMoney($totalAmount) }}</div>
        <div class="hint">{{ __('billing::pdf.balance_due') }}: {{ $formatMoney($balanceDue) }}</div>
    </div>
    <div class="card">
        <div class="section-label">{{ __('billing::pdf.paid_total') }}</div>
        <div class="metric">{{ $formatMoney($paidTotal) }}</div>
        <div class="hint">{{ __('billing::pdf.sequence') }}: {{ $invoice->series?->name ?? '—' }}</div>
    </div>
</div>
