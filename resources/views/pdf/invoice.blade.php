@php
    /** @var array{invoice:\Illuminate\Support\Fluent} $document */

    $invoice = $document['invoice'];
    $locale = $invoice->locale ?? app()->getLocale();
    $documentType = $invoice->document_type?->label() ?? __('billing::pdf.document_title');
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', $locale) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('billing::pdf.title', ['type' => $documentType, 'number' => $invoice->number ?? __('billing::pdf.draft')]) }}</title>
    @include('billing::pdf.partials.styles')
</head>
<body>
<main class="page">
    @include('billing::shared.invoices.document')
</main>
</body>
</html>
