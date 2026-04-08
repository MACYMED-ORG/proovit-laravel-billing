<style>
    :root {
        --paper: #ffffff;
        --ink: #111827;
        --muted: #6b7280;
        --border: #d1d5db;
        --accent: #1d4ed8;
        --accent-soft: #eff6ff;
        --accent-warm: #92400e;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #f3f4f6;
        color: var(--ink);
        font: 13px/1.55 "DejaVu Sans", "Segoe UI", sans-serif;
    }

    .page {
        width: 100%;
        max-width: 980px;
        margin: 0 auto;
        padding: 24px;
    }

    .hero {
        width: 100%;
        padding: 22px 24px;
        color: #fff;
        background: #111827;
        border-radius: 18px;
    }

    .eyebrow {
        display: inline-flex;
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
        font-size: 28px;
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
        width: 100%;
    }

    .card,
    .totals-card,
    .panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
    }

    .meta-card {
        padding: 18px;
        color: #fff;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.14);
    }

    .meta-label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        margin-bottom: 6px;
    }

    .meta-value {
        color: #fff;
        font-size: 18px;
        font-weight: 700;
        word-break: break-word;
    }

    .stack {
        margin-top: 16px;
    }

    .cards-2,
    .summary,
    .totals-layout {
        width: 100%;
        border-collapse: collapse;
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
        width: 100%;
        border-collapse: collapse;
    }

    .field {
        padding: 0 0 10px 0;
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
        margin-bottom: 18px;
    }

    .summary .card {
        background: #fff;
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
        margin-top: 18px;
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
        margin-top: 14px;
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
        margin-top: 18px;
        color: var(--muted);
        font-size: 12px;
    }

    .footnote table {
        width: 100%;
    }

    .footnote td:last-child {
        text-align: right;
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
    }
</style>
