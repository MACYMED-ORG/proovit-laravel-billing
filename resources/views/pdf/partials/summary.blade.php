<table class="summary">
    <tr>
        <td style="width: 25%; padding-right: 10px;">
            <div class="card">
                <div class="section-label">{{ __('billing::pdf.subtotal') }}</div>
                <div class="metric">{{ $formatMoney($subtotalAmount) }}</div>
                <div class="hint">{{ __('billing::pdf.currency') }}: {{ $currency }}</div>
            </div>
        </td>
        <td style="width: 25%; padding-right: 10px;">
            <div class="card">
                <div class="section-label">{{ __('billing::pdf.tax_total') }}</div>
                <div class="metric">{{ $formatMoney($taxAmount) }}</div>
                <div class="hint">{{ __('billing::pdf.vat_number') }}</div>
            </div>
        </td>
        <td style="width: 25%; padding-right: 10px;">
            <div class="card">
                <div class="section-label">{{ __('billing::pdf.total') }}</div>
                <div class="metric">{{ $formatMoney($totalAmount) }}</div>
                <div class="hint">{{ __('billing::pdf.balance_due') }}: {{ $formatMoney($balanceDue) }}</div>
            </div>
        </td>
        <td style="width: 25%;">
            <div class="card">
                <div class="section-label">{{ __('billing::pdf.paid_total') }}</div>
                <div class="metric">{{ $formatMoney($paidTotal) }}</div>
                <div class="hint">{{ __('billing::pdf.sequence') }}: {{ $invoice->series?->name ?? '—' }}</div>
            </div>
        </td>
    </tr>
</table>
