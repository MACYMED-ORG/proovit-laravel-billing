@php
    /** @var array{invoice:\Illuminate\Support\Fluent} $document */
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('billing::web.print_title') }}</title>
    @include('billing::web.partials.styles')
    @include('billing::pdf.partials.styles')
</head>
<body class="print-mode">
    @include('billing::web.invoices.content', ['document' => $document, 'shared' => $shared])
    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
