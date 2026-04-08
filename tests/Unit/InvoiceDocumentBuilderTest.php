<?php

declare(strict_types=1);

use Proovit\Billing\Actions\Invoices\GenerateInvoicePdfAction;
use Proovit\Billing\Builders\Documents\InvoiceDocumentBuilder;

it('builds and renders invoice documents with the fluent builder', function (): void {
    $document = InvoiceDocumentBuilder::make()
        ->withSeller([
            'legal_name' => 'ProovIT SAS',
            'display_name' => 'ProovIT',
            'address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        ])
        ->withCustomer([
            'legal_name' => 'Client SARL',
            'reference' => 'CLI-001',
            'billing_address' => ['line1' => '2 avenue des Tests', 'city' => 'Lyon', 'country' => 'FR'],
        ])
        ->addLine([
            'description' => 'Consulting',
            'quantity' => '1',
            'unit_price' => '250.00',
            'tax_rate' => '20',
        ])
        ->withNumber('INV-2026-0001')
        ->withIssuedAt(now())
        ->withDueAt(now()->addDays(30))
        ->withLocale('fr')
        ->build();

    expect($document->number)->toBe('INV-2026-0001');
    expect($document->currency)->toBe('EUR');

    $html = app(GenerateInvoicePdfAction::class)->handle($document);

    expect($html)->toContain('PDF:billing::pdf.invoice');
    expect($html)->toContain('ProovIT SAS');
    expect($html)->toContain('Client SARL');
});

it('validates that the fluent builder has the required minimum data', function (): void {
    expect(fn () => InvoiceDocumentBuilder::make()->build())
        ->toThrow(LogicException::class);
});
