<table class="totals-layout">
    <tr>
        <td class="payment-cell left">
            <section class="payments-box">
                <h2 class="box-title">{{ __('billing::pdf.payments') }}</h2>
                @if ($payments->isEmpty())
                    <div class="empty-state">{{ __('billing::pdf.no_payments') }}</div>
                @else
                    <table class="payment-row">
                        @foreach ($payments as $payment)
                            <tr>
                                <td>
                                    <strong>{{ $payment->method?->label() ?? $payment->method?->value ?? __('billing::pdf.payment_method') }}</strong>
                                    <div class="muted">
                                        {{ __('billing::pdf.payment_status') }}: {{ $payment->status?->label() ?? '—' }}
                                        @if ($payment->paid_at)
                                            · {{ __('billing::pdf.paid_at') }} {{ $formatDate($payment->paid_at) }}
                                        @endif
                                    </div>
                                </td>
                                <td class="currency-right"><strong>{{ $formatMoney($payment->amount) }}</strong></td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </section>
        </td>
        <td class="payment-cell right">
            <section class="totals-card">
                <h2 class="box-title">{{ __('billing::pdf.total') }}</h2>
                <table class="totals-grid">
                    <tr>
                        <td class="totals-label">{{ __('billing::pdf.subtotal') }}</td>
                        <td class="totals-value">{{ $formatMoney($subtotalAmount) }}</td>
                    </tr>
                    <tr>
                        <td class="totals-label">{{ __('billing::pdf.tax_total') }}</td>
                        <td class="totals-value">{{ $formatMoney($taxAmount) }}</td>
                    </tr>
                    <tr class="totals-total">
                        <td class="totals-label">{{ __('billing::pdf.total') }}</td>
                        <td class="totals-value">{{ $formatMoney($totalAmount) }}</td>
                    </tr>
                </table>
            </section>
        </td>
    </tr>
</table>
