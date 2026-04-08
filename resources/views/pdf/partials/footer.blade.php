<div class="footnote">
    <table>
        <tr>
            <td>{{ __('billing::pdf.footer') }}</td>
            <td>{{ __('billing::pdf.generated_on', ['date' => $formatDateTime(now())]) }}</td>
        </tr>
    </table>
</div>
