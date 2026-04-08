# Quotes

Quotes follow their own lifecycle and can be converted into invoices.

## Create a quote

Use the quote API or the quote action classes to create a draft quote from the same billing DTOs used for invoices.

## Convert a quote to an invoice

```php
use Proovit\Billing\Actions\Quotes\ConvertQuoteToInvoiceAction;

$invoice = app(ConvertQuoteToInvoiceAction::class)->handle($quote);
```

The conversion keeps a persistent relationship between the original quote and the resulting invoice.

## What is kept

- the original quote number
- the generated invoice link
- the conversion reference in the API

## What is optional

You do not have to print the quote-to-invoice relation in the PDF by default. It can remain a metadata field in the API and in your internal workflows.
