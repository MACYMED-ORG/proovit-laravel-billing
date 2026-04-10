<style>
    :root {
        --paper: #ffffff;
        --ink: #1f2937;
        --ink-soft: #4b5563;
        --muted: #6b7280;
        --muted-light: #9ca3af;
        --line: #e5e7eb;
        --line-strong: #d1d5db;
        --surface: #f8fafc;
        --surface-soft: #f9fafb;
        --accent: #334155;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #ffffff;
        color: var(--ink);
        font: 11px/1.5 "DejaVu Sans", Arial, sans-serif;
    }

    .page {
        width: 100%;
        max-width: 860px;
        margin: 0 auto;
        padding: 22px 24px 18px;
    }

    .invoice-shell {
        width: 100%;
        background: var(--paper);
    }

    .stack {
        width: 100%;
    }

    .invoice-section {
        margin-top: 18px;
    }

    .section-title {
        margin: 0 0 10px;
        font-size: 10px;
        line-height: 1.2;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    /* ===== HEADER ===== */

    .hero {
        width: 100%;
        padding-bottom: 10px;
    }

    .header-grid {
        width: 100%;
        border-collapse: collapse;
    }

    .header-grid td {
        vertical-align: top;
    }

    .header-brand {
        width: 54%;
        padding-right: 12px;
    }

    .header-title {
        width: 46%;
        text-align: right;
    }

    .brand-row {
        width: 100%;
        border-collapse: collapse;
    }

    .brand-mark {
        display: inline-block;
        width: 12px;
        height: 12px;
        background: var(--accent);
        margin-right: 8px;
        vertical-align: middle;
    }

    .brand-copy {
        display: inline-block;
        vertical-align: middle;
    }

    .brand-name {
        font-size: 22px;
        line-height: 1.05;
        font-weight: 700;
        letter-spacing: -0.01em;
        color: var(--ink);
    }

    .brand-subtitle {
        margin-top: 3px;
        font-size: 10px;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 600;
    }

    .document-title {
        font-size: 28px;
        line-height: 1;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -0.01em;
    }

    .document-number {
        margin-top: 6px;
        font-size: 11px;
        line-height: 1.3;
        color: var(--muted);
        font-weight: 500;
    }

    .invoice-meta {
        width: 100%;
        margin-top: 18px;
        border-collapse: collapse;
        border-top: 1px solid var(--line-strong);
        border-bottom: 1px solid var(--line);
    }

    .invoice-meta td {
        width: 25%;
        padding: 10px 12px 10px 0;
        vertical-align: top;
    }

    .invoice-meta td:last-child {
        padding-right: 0;
    }

    .meta-label {
        font-size: 9px;
        line-height: 1.2;
        color: var(--muted-light);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
    }

    .meta-value {
        margin-top: 4px;
        font-size: 11px;
        line-height: 1.35;
        color: var(--ink);
        font-weight: 600;
    }

    /* ===== CARDS / BLOCS ===== */

    .card,
    .totals-card,
    .notes-box,
    .payments-box {
        background: #ffffff;
        border: 1px solid var(--line);
    }

    .card {
        padding: 14px 16px;
    }

    .payments-box,
    .notes-box,
    .totals-card {
        padding: 14px 16px;
    }

    .box-title {
        margin: 0 0 10px;
        font-size: 10px;
        line-height: 1.2;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
        font-weight: 700;
    }

    .empty-state {
        padding: 10px 0 0;
        color: var(--muted);
        font-size: 10px;
        line-height: 1.5;
    }

    /* ===== PARTIES ===== */

    .party-grid {
        width: 100%;
        border-collapse: collapse;
    }

    .party-grid,
    .line-section,
    .totals-layout,
    .notes-grid,
    .payments-box,
    .notes-box,
    .totals-card,
    .card {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .party-cell {
        width: 50%;
        vertical-align: top;
        padding-right: 8px;
    }

    .party-cell:last-child {
        padding-right: 0;
        padding-left: 8px;
    }

    .party-label {
        margin-bottom: 6px;
        font-size: 9px;
        line-height: 1.2;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted-light);
        font-weight: 700;
    }

    .party-name {
        margin-bottom: 10px;
        font-size: 15px;
        line-height: 1.3;
        font-weight: 700;
        color: var(--ink);
    }

    .detail-list {
        width: 100%;
        border-collapse: collapse;
    }

    .detail-list td {
        padding: 0 0 6px;
        vertical-align: top;
    }

    .detail-label {
        width: 34%;
        padding-right: 8px;
        font-size: 9px;
        line-height: 1.35;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 600;
    }

    .detail-value {
        font-size: 10.5px;
        line-height: 1.45;
        color: var(--ink);
        font-weight: 500;
        white-space: pre-line;
    }

    /* ===== LIGNES ===== */

    .line-section h2 {
        margin: 0 0 10px;
        font-size: 10px;
        line-height: 1.2;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
        font-weight: 700;
    }

    .line-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .line-col-index {
        width: 5%;
    }

    .line-col-description {
        width: 47%;
    }

    .line-col-quantity {
        width: 12%;
    }

    .line-col-unit {
        width: 17%;
    }

    .line-col-total {
        width: 19%;
    }

    .line-table thead th {
        padding: 10px 8px;
        text-align: left;
        border-top: 1px solid var(--line-strong);
        border-bottom: 1px solid var(--line-strong);
        font-size: 9px;
        line-height: 1.2;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-weight: 700;
        background: var(--surface-soft);
    }

    .line-table tbody td {
        padding: 11px 8px;
        border-bottom: 1px solid #eef2f7;
        vertical-align: top;
        font-size: 10.5px;
        line-height: 1.45;
        color: var(--ink);
        font-weight: 400;
    }

    .line-table tbody tr:last-child td {
        border-bottom: 1px solid var(--line-strong);
    }

    .line-desc {
        font-size: 10.5px;
        line-height: 1.45;
        color: var(--ink);
        font-weight: 600;
    }

    .line-meta {
        margin-top: 3px;
        font-size: 9.5px;
        line-height: 1.4;
        color: var(--muted);
        font-weight: 400;
    }

    .line-separator {
        display: inline-block;
        margin: 0 5px;
        color: var(--muted-light);
    }

    .currency-right {
        text-align: right;
    }

    /* ===== PAYMENTS + TOTALS ===== */

    .totals-layout {
        width: 100%;
        margin-top: 2px;
        border-collapse: collapse;
    }

    .payment-cell {
        vertical-align: top;
    }

    .payment-cell.left {
        width: 58%;
        padding-right: 8px;
    }

    .payment-cell.right {
        width: 42%;
        padding-left: 8px;
    }

    .payment-row {
        width: 100%;
        border-collapse: collapse;
    }

    .payment-row td {
        padding: 7px 0;
        border-bottom: 1px solid #eef2f7;
        vertical-align: top;
    }

    .payment-row tr:last-child td {
        border-bottom: 0;
    }

    .muted {
        color: var(--muted);
        font-size: 9.5px;
        line-height: 1.4;
        font-weight: 400;
    }

    .totals-card {
        background: var(--surface);
        border: 1px solid #dbe2ea;
    }

    .totals-grid {
        width: 100%;
        border-collapse: collapse;
    }

    .totals-grid td {
        padding: 6px 0;
        vertical-align: top;
    }

    .totals-label {
        width: 68%;
        text-align: left;
        color: var(--muted);
        font-size: 10px;
        line-height: 1.35;
        font-weight: 600;
    }

    .totals-value {
        width: 32%;
        text-align: right;
        color: var(--ink);
        font-size: 10.5px;
        line-height: 1.35;
        font-weight: 700;
    }

    .totals-total td {
        padding-top: 9px;
        border-top: 1px solid #cbd5e1;
    }

    .totals-total .totals-label,
    .totals-total .totals-value {
        font-size: 14px;
        line-height: 1.3;
        color: var(--ink);
        font-weight: 700;
    }

    /* ===== NOTES / LEGAL ===== */

    .notes-grid {
        width: 100%;
        border-collapse: collapse;
    }

    .notes-cell {
        width: 50%;
        vertical-align: top;
        padding-right: 8px;
    }

    .notes-cell:last-child {
        padding-right: 0;
        padding-left: 8px;
    }

    .value {
        font-size: 10.5px;
        line-height: 1.55;
        color: var(--ink);
        font-weight: 400;
        white-space: pre-line;
    }

    .legal-list {
        margin: 0;
        padding-left: 16px;
    }

    .legal-list li {
        margin: 0 0 4px;
        font-size: 10px;
        line-height: 1.5;
        color: var(--ink-soft);
        font-weight: 400;
    }

    .chips {
        margin-top: 10px;
    }

    .chip {
        display: inline-block;
        padding: 3px 7px;
        margin: 0 4px 4px 0;
        border: 1px solid var(--line);
        background: #ffffff;
        color: var(--muted);
        font-size: 8.5px;
        line-height: 1.2;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    /* ===== FOOTER ===== */

    .footnote {
        margin-top: 16px;
        padding-top: 10px;
        border-top: 1px solid var(--line);
        color: var(--muted);
        font-size: 9.5px;
        line-height: 1.4;
    }

    .footnote table {
        width: 100%;
        border-collapse: collapse;
    }

    .footnote td {
        vertical-align: top;
    }

    .footnote td:last-child {
        text-align: right;
    }

    /* ===== PRINT ===== */

    @media print {
        body {
            background: #ffffff;
        }

        .page {
            max-width: none;
            padding: 0;
        }

        .invoice-shell {
            border: 0;
        }
    }
</style>