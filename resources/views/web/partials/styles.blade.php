<style>
    :root {
        color-scheme: light;
        --bg: #f3efe8;
        --panel: #fffaf3;
        --panel-alt: #f8f2e8;
        --text: #241f1a;
        --muted: #6f665d;
        --accent: #2a6f68;
        --accent-soft: rgba(42, 111, 104, 0.12);
        --border: rgba(36, 31, 26, 0.12);
        --shadow: 0 18px 45px rgba(36, 31, 26, 0.10);
    }

    * { box-sizing: border-box; }

    body {
        margin: 0;
        font-family: Inter, ui-sans-serif, system-ui, sans-serif;
        color: var(--text);
        background:
            radial-gradient(circle at top left, rgba(42, 111, 104, 0.12), transparent 28%),
            linear-gradient(180deg, #f6f0e7 0%, #efe8de 100%);
        min-height: 100vh;
    }

    .app-shell {
        max-width: 1180px;
        margin: 0 auto;
        padding: 40px 24px 72px;
    }

    .hero {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        align-items: flex-end;
        margin-bottom: 24px;
    }

    .eyebrow {
        text-transform: uppercase;
        letter-spacing: .18em;
        font-size: 12px;
        color: var(--accent);
        margin-bottom: 8px;
        font-weight: 700;
    }

    h1 {
        margin: 0;
        font-size: clamp(30px, 4vw, 52px);
        line-height: 1.02;
    }

    h1 span, h2 {
        font-weight: 700;
    }

    p { line-height: 1.6; }

    .hero-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 16px;
        border-radius: 999px;
        border: 1px solid var(--accent);
        background: var(--accent);
        color: white;
        text-decoration: none;
        font-weight: 700;
        box-shadow: var(--shadow);
    }

    .button.secondary {
        background: transparent;
        color: var(--accent);
        box-shadow: none;
    }

    .status-grid,
    .content-grid,
    .totals-grid {
        display: grid;
        gap: 16px;
        margin-bottom: 16px;
    }

    .status-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .content-grid,
    .totals-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .card,
    .panel {
        background: color-mix(in srgb, var(--panel) 94%, white 6%);
        border: 1px solid var(--border);
        border-radius: 24px;
        box-shadow: var(--shadow);
    }

    .card {
        padding: 18px 20px;
    }

    .card-label,
    .panel-title {
        display: block;
        color: var(--muted);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .12em;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .panel {
        padding: 24px;
    }

    .muted {
        color: var(--muted);
        margin: 0 0 6px;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        text-align: left;
        padding: 14px 12px;
        border-bottom: 1px solid var(--border);
        vertical-align: top;
    }

    th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--muted);
    }

    .empty {
        text-align: center;
        color: var(--muted);
        padding: 28px 12px;
    }

    .totals-list {
        display: grid;
        gap: 10px;
        margin: 0;
    }

    .totals-list > div {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
    }

    .totals-list dt {
        color: var(--muted);
    }

    .totals-list dd {
        margin: 0;
        font-weight: 700;
    }

    .event-list {
        margin: 0;
        padding: 0;
        list-style: none;
        display: grid;
        gap: 12px;
    }

    .event-list li {
        padding: 14px 16px;
        background: var(--panel-alt);
        border-radius: 16px;
        border: 1px solid var(--border);
    }

    .event-list strong,
    .event-list span,
    .event-list small {
        display: block;
    }

    .event-list small {
        color: var(--muted);
        margin-top: 4px;
    }

    @media (max-width: 960px) {
        .status-grid,
        .content-grid,
        .totals-grid {
            grid-template-columns: 1fr;
        }

        .hero {
            flex-direction: column;
            align-items: stretch;
        }
    }

    @media print {
        body {
            background: white;
        }

        .app-shell {
            max-width: none;
            padding: 0;
        }

        .hero-actions {
            display: none;
        }

        .card,
        .panel {
            box-shadow: none;
        }
    }
</style>
