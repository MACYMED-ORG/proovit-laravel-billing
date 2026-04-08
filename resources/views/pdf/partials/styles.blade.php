<style>
    :root {
        --paper: #ffffff;
        --ink: #111111;
        --muted: #666666;
        --border: #d8d8d8;
        --rule: #2b2b2b;
        --accent: #111827;
        --accent-soft: #f4f4f5;
        --accent-warm: #8a4b16;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #ffffff;
        color: var(--ink);
        font: 12px/1.45 "DejaVu Sans", Arial, sans-serif;
    }

    .page {
        width: 100%;
        max-width: 860px;
        margin: 0 auto;
        padding: 18px 18px 20px;
    }

    .invoice-shell {
        background: var(--paper);
        padding: 0;
        border: 0;
    }

    .hero {
        width: 100%;
        padding: 0 0 12px;
    }

    .header-grid {
        width: 100%;
        border-collapse: collapse;
    }

    .header-brand {
        width: 48%;
        vertical-align: bottom;
        padding-right: 12px;
    }

    .brand-row {
        display: table;
        width: 100%;
    }

    .brand-mark {
        display: table-cell;
        width: 38px;
        vertical-align: top;
    }

    .brand-mark-inner {
        display: block;
        width: 28px;
        height: 28px;
        border: 2px solid var(--ink);
        position: relative;
        margin-top: 2px;
    }

    .brand-mark-inner::before,
    .brand-mark-inner::after {
        content: '';
        position: absolute;
        border: 2px solid #d7a7a2;
        width: 12px;
        height: 12px;
    }

    .brand-mark-inner::before {
        top: -5px;
        right: -5px;
    }

    .brand-mark-inner::after {
        bottom: -5px;
        left: -5px;
    }

    .brand-copy {
        display: table-cell;
        vertical-align: bottom;
        padding-left: 12px;
    }

    .brand-name {
        font-size: 24px;
        line-height: 1.02;
        font-weight: 800;
        letter-spacing: -0.02em;
        text-transform: lowercase;
    }

    .brand-subtitle {
        margin-top: 3px;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.18em;
        font-size: 11px;
        font-weight: 700;
    }

    .header-title {
        width: 52%;
        text-align: right;
        vertical-align: bottom;
        line-height: 0.95;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .document-title {
        font-size: 34px;
        font-weight: 900;
    }

    .document-number {
        margin-top: 2px;
        font-size: 20px;
        font-weight: 800;
    }

    .invoice-meta {
        width: 100%;
        margin-top: 12px;
        border-top: 2px solid var(--rule);
        border-bottom: 1px solid var(--border);
        padding: 8px 0;
        border-collapse: collapse;
    }

    .invoice-meta td {
        width: 25%;
        padding: 0 10px 0 0;
        vertical-align: top;
    }

    .meta-label {
        color: var(--muted);
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        font-weight: 800;
    }

    .meta-value {
        margin-top: 4px;
        font-size: 13px;
        font-weight: 700;
    }

    .stack {
        width: 100%;
    }

    .invoice-section {
        margin-top: 12px;
    }

    .section-title {
        margin: 0 0 10px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        font-weight: 800;
        color: var(--ink);
    }

    .party-grid,
    .totals-layout,
    .notes-grid {
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
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .party-cell {
        width: 50%;
        vertical-align: top;
        padding-right: 10px;
    }

    .party-cell:last-child {
        padding-right: 0;
        padding-left: 10px;
    }

    .card,
    .totals-card,
    .panel,
    .details-box,
    .notes-box,
    .payments-box {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 0;
    }

    .card {
        padding: 14px 16px;
    }

    .party-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        font-weight: 800;
        color: var(--muted);
        margin-bottom: 8px;
    }

    .party-name {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .detail-list {
        width: 100%;
        border-collapse: collapse;
    }

    .detail-list td {
        padding: 0 0 4px;
        vertical-align: top;
    }

    .detail-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
        font-weight: 700;
        width: 36%;
        padding-right: 10px;
    }

    .detail-value {
        font-weight: 600;
        white-space: pre-line;
    }

    .line-items {
        width: 100%;
        border-collapse: collapse;
        margin-top: 12px;
        table-layout: fixed;
    }

    .line-items thead th {
        padding: 8px 6px;
        border-top: 2px solid var(--rule);
        border-bottom: 1px solid var(--border);
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
    }

    .line-items tbody td {
        padding: 8px 6px;
        border-bottom: 1px solid var(--border);
        vertical-align: top;
    }

    .line-items tbody tr:last-child td {
        border-bottom: 2px solid var(--rule);
    }

    .line-description {
        font-weight: 700;
    }

    .line-sku {
        margin-top: 2px;
        color: var(--muted);
        font-size: 11px;
    }

    .currency-right {
        text-align: right;
    }

    .summary {
        margin-top: 12px;
    }

    .totals-layout {
        margin-top: 12px;
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
    }

    .totals-card {
        padding: 12px 14px;
    }

    .totals-grid {
        width: 100%;
        border-collapse: collapse;
    }

    .totals-label {
        width: 72%;
        text-align: right;
        padding: 5px 10px 5px 0;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .totals-value {
        width: 28%;
        text-align: right;
        padding: 5px 0;
        font-weight: 800;
    }

    .totals-total {
        border-top: 2px solid var(--rule);
        border-bottom: 2px solid var(--rule);
    }

    .totals-total .totals-label,
    .totals-total .totals-value {
        font-size: 15px;
    }

    .payments-grid {
        width: 100%;
        border-collapse: collapse;
        margin-top: 18px;
    }

    .notes-cell {
        width: 50%;
        vertical-align: top;
        padding-right: 10px;
    }

    .notes-cell:last-child {
        padding-right: 0;
        padding-left: 10px;
    }

    .payments-box,
    .notes-box,
    .details-box {
        padding: 12px 14px;
    }

    .box-title {
        margin: 0 0 10px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--muted);
        font-weight: 800;
    }

    .empty-state {
        color: var(--muted);
        border: 1px dashed var(--border);
        padding: 10px;
    }

    .payment-row {
        width: 100%;
        border-collapse: collapse;
    }

    .payment-row td {
        padding: 6px 0;
        border-bottom: 1px solid #ececec;
    }

    .payment-row tr:last-child td {
        border-bottom: 0;
    }

    .muted {
        color: var(--muted);
    }

    .legal-list {
        margin: 0;
        padding-left: 18px;
    }

    .chips {
        margin-top: 10px;
    }

    .chip {
        display: inline-block;
        padding: 5px 9px;
        margin: 0 6px 6px 0;
        border: 1px solid var(--border);
        border-radius: 999px;
        background: var(--accent-soft);
        color: var(--accent);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .footnote {
        margin-top: 16px;
        border-top: 1px solid var(--border);
        padding-top: 10px;
        color: var(--muted);
        font-size: 11px;
    }

    .footnote table {
        width: 100%;
        border-collapse: collapse;
    }

    .footnote td:last-child {
        text-align: right;
    }

    @media print {
        body {
            background: #fff;
        }

        .page {
            padding: 0;
            max-width: none;
        }

        .invoice-shell {
            border: 0;
            padding: 0;
        }
    }

    .line-section h2 {
        margin: 0 0 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        font-weight: 800;
    }

    .line-desc {
        font-weight: 700;
    }

    .line-meta {
        margin-top: 2px;
        color: var(--muted);
        font-size: 11px;
    }

    .line-separator {
        display: inline-block;
        margin: 0 6px;
    }

    .line-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .line-table thead th {
        padding: 8px 6px;
        border-top: 2px solid var(--rule);
        border-bottom: 1px solid var(--border);
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
    }

    .line-table tbody td {
        padding: 8px 6px;
        border-bottom: 1px solid var(--border);
        vertical-align: top;
    }

    .line-table tbody tr:last-child td {
        border-bottom: 2px solid var(--rule);
    }

    .line-col-index {
        width: 4%;
    }

    .line-col-description {
        width: 50%;
    }

    .line-col-quantity {
        width: 10%;
    }

    .line-col-unit {
        width: 18%;
    }

    .line-col-total {
        width: 18%;
    }
</style>
