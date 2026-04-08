@php
    $locale = app()->getLocale();
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', $locale) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('billing::web.home_title') }}</title>
    <style>
        :root {
            --bg: #f4efe6;
            --panel: #fffaf3;
            --text: #241f1a;
            --muted: #6f665d;
            --accent: #2a6f68;
            --border: rgba(36, 31, 26, 0.12);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(42, 111, 104, 0.12), transparent 26%),
                linear-gradient(180deg, #f7f1e8 0%, #efe7dc 100%);
        }

        main {
            max-width: 980px;
            margin: 0 auto;
            padding: 56px 24px;
        }

        .hero {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 32px;
            padding: 32px;
            box-shadow: 0 18px 45px rgba(36, 31, 26, 0.10);
        }

        .eyebrow {
            text-transform: uppercase;
            letter-spacing: .2em;
            color: var(--accent);
            font-weight: 800;
            font-size: 12px;
            margin-bottom: 10px;
        }

        h1 {
            margin: 0;
            font-size: clamp(32px, 5vw, 58px);
            line-height: 1.02;
        }

        p {
            color: var(--muted);
            line-height: 1.6;
            font-size: 16px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-top: 24px;
        }

        .card {
            padding: 18px 20px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid var(--border);
            border-radius: 22px;
        }

        .card strong {
            display: block;
            margin-bottom: 8px;
        }

        @media (max-width: 720px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<main>
    <section class="hero">
        <div class="eyebrow">{{ __('billing::messages.loaded') }}</div>
        <h1>{{ __('billing::web.home_title') }}</h1>
        <p>{{ __('billing::web.home_lead') }}</p>
        <p>{{ __('billing::web.home_summary') }}</p>

        <div class="grid">
            <article class="card">
                <strong>Laravel 13 + PHP 8.3</strong>
                <span>{{ config('billing.features.database') ? __('billing::web.database_mode_enabled') : __('billing::web.database_mode_disabled') }}</span>
            </article>
            <article class="card">
                <strong>API / PDF / Public shares</strong>
                <span>{{ config('billing.api.enabled') ? __('billing::web.api_routes_available') : __('billing::web.api_routes_disabled') }}</span>
            </article>
        </div>
    </section>
</main>
</body>
</html>
