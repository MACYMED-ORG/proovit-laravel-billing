@php
    /** @var array{invoice:\Illuminate\Support\Fluent} $document */

    $invoice = $document['invoice'];
    $locale = app()->getLocale();
    $documentType = $invoice->document_type?->label() ?? __('billing::web.document_title');
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', $locale) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $documentType }} {{ $invoice->number ?? __('billing::pdf.draft') }}</title>
    @include('billing::web.partials.styles')
</head>
<body>
    @include('billing::web.invoices.content', ['document' => $document, 'shared' => $shared])
</body>
</html>
