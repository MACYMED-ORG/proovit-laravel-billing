<section class="invoice-section line-section">
    <h2 class="section-title">{{ __('billing::pdf.line_items') }}</h2>
    @if ($lineItems->isEmpty())
        <div class="empty-state">{{ __('billing::pdf.no_lines') }}</div>
    @else
        <table class="line-table">
            <colgroup>
                <col class="line-col-index">
                <col class="line-col-description">
                <col class="line-col-quantity">
                <col class="line-col-unit">
                <col class="line-col-total">
            </colgroup>
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __('billing::pdf.description') }}</th>
                <th>{{ __('billing::pdf.quantity') }}</th>
                <th>{{ __('billing::pdf.unit_price') }}</th>
                <th class="currency-right">{{ __('billing::pdf.total') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($lineItems as $line)
                <tr>
                    <td>{{ $line->sort_order ?: $loop->iteration }}</td>
                    <td>
                        <div class="line-desc">{{ $line->product?->name ?? $line->description }}</div>
                        @if ($line->product?->sku)
                            <div class="line-meta">{{ $line->product->sku }}</div>
                        @endif
                        <div class="line-meta">
                            @if ((float) $line->discount_amount > 0)
                                {{ __('billing::pdf.discount') }}: {{ $formatMoney($line->discount_amount) }}
                            @endif
                            @if ((float) $line->discount_amount > 0 && (float) $line->tax_rate >= 0)
                                <span class="line-separator">·</span>
                            @endif
                            {{ __('billing::pdf.tax_rate') }}: {{ rtrim(rtrim(number_format((float) $line->tax_rate, 2, ',', ' '), '0'), ',') }}%
                        </div>
                    </td>
                    <td>{{ $line->quantity }}</td>
                    <td>{{ $formatMoney($line->unit_price) }}</td>
                    <td class="currency-right"><strong>{{ $formatMoney($line->total_amount) }}</strong></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</section>
