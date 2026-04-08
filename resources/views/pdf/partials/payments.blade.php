<div class="totals-layout">
    <section class="panel">
        <h2>{{ __('billing::pdf.payments') }}</h2>
        @if ($payments->isEmpty())
            <div class="empty-state">{{ __('billing::pdf.no_payments') }}</div>
        @else
            <div class="stack">
                @foreach ($payments as $payment)
                    <div class="totals-row">
                        <div>
                            <strong>{{ $payment->method?->label() ?? $payment->method?->value ?? __('billing::pdf.payment_method') }}</strong>
                            <div class="muted">
                                {{ __('billing::pdf.payment_status') }}: {{ $payment->status?->label() ?? '—' }}
                                @if ($payment->paid_at)
                                    · {{ __('billing::pdf.paid_at') }} {{ $formatDate($payment->paid_at) }}
                                @endif
                            </div>
                        </div>
                        <strong>{{ $formatMoney($payment->amount) }}</strong>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section class="totals-card">
        <h2>{{ __('billing::pdf.total') }}</h2>
        <div class="totals-row">
            <span>{{ __('billing::pdf.subtotal') }}</span>
            <strong>{{ $formatMoney($subtotalAmount) }}</strong>
        </div>
        <div class="totals-row">
            <span>{{ __('billing::pdf.tax_total') }}</span>
            <strong>{{ $formatMoney($taxAmount) }}</strong>
        </div>
        <div class="totals-row">
            <span>{{ __('billing::pdf.total') }}</span>
            <strong>{{ $formatMoney($totalAmount) }}</strong>
        </div>
        <div class="totals-row">
            <span>{{ __('billing::pdf.paid_total') }}</span>
            <strong>{{ $formatMoney($paidTotal) }}</strong>
        </div>
        <div class="totals-row">
            <span>{{ __('billing::pdf.balance_due') }}</span>
            <strong class="balance">{{ $formatMoney($balanceDue) }}</strong>
        </div>
    </section>
</div>
