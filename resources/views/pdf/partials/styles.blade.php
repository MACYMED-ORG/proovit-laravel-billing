<style>
    :root {
        --bg: #f4efe7;
        --paper: #ffffff;
        --ink: #0f172a;
        --muted: #64748b;
        --border: #d7d2c7;
        --accent: #1d4ed8;
        --accent-soft: #dbeafe;
        --accent-warm: #b45309;
        --success: #166534;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background:
            radial-gradient(circle at top right, rgba(29, 78, 216, 0.12), transparent 28%),
            linear-gradient(180deg, #faf7f2 0%, var(--bg) 100%);
        color: var(--ink);
        font: 14px/1.55 "Inter", "Segoe UI", sans-serif;
    }

    .page {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 32px;
    }

    .hero {
        display: grid;
        grid-template-columns: minmax(0, 1.7fr) minmax(280px, 0.9fr);
        gap: 24px;
        padding: 28px;
        color: #fff;
        background:
            linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(30, 41, 59, 0.92)),
            radial-gradient(circle at top left, rgba(29, 78, 216, 0.7), transparent 32%);
        border-radius: 28px;
        box-shadow: 0 32px 80px rgba(15, 23, 42, 0.22);
    }

    .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        text-transform: uppercase;
        letter-spacing: 0.16em;
        font-size: 11px;
        font-weight: 700;
    }

    .hero h1 {
        margin: 16px 0 6px;
        font-size: 36px;
        line-height: 1.05;
        letter-spacing: -0.03em;
    }

    .hero h1 span {
        color: #dbeafe;
    }

    .hero .subtitle {
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
        max-width: 56ch;
    }

    .meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .meta-card,
    .card,
    .totals-card,
    .panel {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(215, 210, 199, 0.95);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
    }

    .meta-card {
        padding: 18px;
    }

    .meta-label {
        color: rgba(255, 255, 255, 0.72);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        margin-bottom: 6px;
    }

    .meta-value {
        font-size: 18px;
        font-weight: 700;
        word-break: break-word;
    }

    .stack {
        display: grid;
        gap: 18px;
        margin-top: 18px;
    }

    .cards-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .card {
        padding: 22px;
        background: var(--paper);
    }

    .card h2,
    .panel h2,
    .totals-card h2 {
        margin: 0 0 14px;
        font-size: 18px;
        letter-spacing: -0.02em;
    }

    .section-label {
        margin-bottom: 10px;
        color: var(--muted);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        font-weight: 700;
    }

    .identity-grid {
        display: grid;
        gap: 12px;
    }

    .field {
        display: grid;
        gap: 2px;
    }

    .field .label {
        color: var(--muted);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .field .value {
        white-space: pre-line;
        font-weight: 600;
    }

    .summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .summary .card {
        background: linear-gradient(180deg, #fff, #fbfaf8);
    }

    .summary .metric {
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -0.03em;
        margin-top: 8px;
    }

    .summary .hint {
        color: var(--muted);
        font-size: 12px;
    }

    .table-wrap {
        overflow: hidden;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead th {
        background: #f8fafc;
        color: var(--muted);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        text-align: left;
        padding: 14px 12px;
        border-bottom: 1px solid var(--border);
    }

    tbody td {
        padding: 16px 12px;
        border-bottom: 1px solid #ebe7df;
        vertical-align: top;
    }

    tbody tr:last-child td {
        border-bottom: 0;
    }

    .line-desc {
        font-weight: 700;
    }

    .line-meta {
        margin-top: 4px;
        color: var(--muted);
        font-size: 12px;
    }

    .totals-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.3fr) minmax(320px, 0.7fr);
        gap: 18px;
        align-items: start;
    }

    .totals-card {
        padding: 22px;
    }

    .totals-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #ebe7df;
    }

    .totals-row:last-child {
        border-bottom: 0;
    }

    .totals-row strong {
        font-size: 18px;
    }

    .balance {
        color: var(--accent-warm);
    }

    .panel {
        padding: 22px;
    }

    .badge-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: var(--accent-soft);
        color: #1d4ed8;
        font-weight: 700;
        font-size: 12px;
    }

    .legal-list {
        margin: 0;
        padding-left: 18px;
        color: var(--muted);
    }

    .footnote {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        margin-top: 18px;
        color: var(--muted);
        font-size: 12px;
    }

    .muted {
        color: var(--muted);
    }

    .empty-state {
        padding: 18px;
        border: 1px dashed var(--border);
        border-radius: 16px;
        color: var(--muted);
        background: #fcfbf8;
    }

    @media print {
        body {
            background: #fff;
        }

        .page {
            padding: 0;
            max-width: none;
        }

        .hero,
        .card,
        .totals-card,
        .panel,
        .meta-card {
            box-shadow: none;
        }
    }
</style>
