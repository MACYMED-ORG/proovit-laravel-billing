# Billing Database Schema

## Multi-company strategy

- `billing_companies` is the root tenant table.
- Most operational tables carry `company_id` for scoping and partitioning.
- Establishments and bank accounts belong to a company, optionally to an establishment.
- Customer records can be global or company-specific via nullable `company_id`.
- Commercial documents keep snapshots of seller/customer identity to avoid drift after finalization.
- Every user-facing model carries a `uuid_identifier` used for HTTP route binding and public lookups.
- Numbering is isolated through `billing_invoice_series` and `billing_invoice_number_reservations`.
- Quotes can be converted into invoices and the invoice keeps a `quote_id` link to its source quote.

## Tables

- `billing_companies`
- `billing_company_establishments`
- `billing_company_bank_accounts`
- `billing_customers`
- `billing_customer_addresses`
- `billing_products`
- `billing_tax_rates`
- `billing_product_prices`
- `billing_invoice_series`
- `billing_invoice_number_reservations`
- `billing_invoices`
- `billing_invoice_lines`
- `billing_quotes`
- `billing_quote_lines`
- `billing_credit_notes`
- `billing_credit_note_lines`
- `billing_payments`
- `billing_payment_allocations`
- `billing_reminders`
- `billing_document_renders`
- `billing_e_invoice_exports`
- `billing_audit_logs`

## Key design points

- Taxes are normalized into a reusable `billing_tax_rates` table.
- Product pricing is time-bounded through `billing_product_prices`.
- Invoice lines store their own numeric totals for immutable document history.
- Audit entries are append-only.
