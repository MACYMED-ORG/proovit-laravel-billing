<section class="panel">
    <h2>{{ __('billing::pdf.line_items') }}</h2>
    @if ($lineItems->isEmpty())
        <div class="empty-state">{{ __('billing::pdf.no_lines') }}</div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('billing::pdf.description') }}</th>
                    <th>{{ __('billing::pdf.quantity') }}</th>
                    <th>{{ __('billing::pdf.unit_price') }}</th>
                    <th>{{ __('billing::pdf.discount') }}</th>
                    <th>{{ __('billing::pdf.tax_rate') }}</th>
                    <th style="text-align: right;">{{ __('billing::pdf.total') }}</th>
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
                        </td>
                        <td>{{ $line->quantity }}</td>
                        <td>{{ $formatMoney($line->unit_price) }}</td>
                        <td>{{ (float) $line->discount_amount > 0 ? $formatMoney($line->discount_amount) : '—' }}</td>
                        <td>{{ rtrim(rtrim(number_format((float) $line->tax_rate, 2, ',', ' '), '0'), ',') }}%</td>
                        <td style="text-align: right; font-weight: 700;">{{ $formatMoney($line->total_amount) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>
