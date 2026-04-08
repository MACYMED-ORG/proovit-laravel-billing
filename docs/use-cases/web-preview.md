# Web preview and print

The package exposes browser-based invoice rendering in addition to the PDF output.

## What it is for

- quick review of an invoice in a browser
- print-friendly rendering
- a public signed share page for clients

## Routes

- `billing.home` for the package landing page
- `billing.invoices.preview` for the internal preview page
- `billing.invoices.print` for the print view
- `billing.public.invoices.show` for signed public links

## Example

```php
$invoice = \Proovit\Billing\Models\Invoice::query()
    ->with(['company', 'customer', 'lines', 'payments'])
    ->firstOrFail();

return redirect()->route('billing.invoices.preview', $invoice);
```

## Notes

- The preview and print pages are built from the same billing document data as the PDF.
- Routes are only registered when `billing.web.enabled` is `true`.
- Public sharing uses a signed URL and a non-guessable token.
