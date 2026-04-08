# FAQ

## Why use `uuid_identifier` instead of the numeric ID?

To keep HTTP access stable and to make direct record enumeration harder.

## Can I install the package without publishing resources?

Yes.

The installer can skip resource publication, and the package migrations are still loaded from the package itself.

## Can I run the package without a database?

Yes, for document generation.

Disable the database-backed stack and use `InvoiceDocumentData` with the PDF pipeline.

The API and public share links are disabled automatically in that mode.

## How do I generate a PDF without an Eloquent invoice?

Use the fluent document builder to assemble the document step by step, or create a normalized `InvoiceDocumentData` object if you already have all fields ready.

See [Document builder mode](use-cases/document-builder.md).

## Where are invoice files stored?

Storage is configurable through:

- `billing.documents.disk`
- `billing.documents.invoices`
- `billing.pdf.directory`

## Do I have to use Sanctum?

No.

You can use Sanctum, an existing middleware stack, or no auth middleware on a private network.

## Where does Scramble expose the billing docs?

Default path:

```text
docs/api/billing
```

The path is configurable, so it will not overwrite the host application's own documentation.

## Are AI, MCP, and Filament docs part of this package?

No.

They should live in their own package documentation.
