<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class () extends Migration {
    /**
     * @var array<int, string>
     */
    private array $tables = [
        'billing_companies',
        'billing_company_establishments',
        'billing_company_bank_accounts',
        'billing_customers',
        'billing_customer_addresses',
        'billing_products',
        'billing_product_prices',
        'billing_tax_rates',
        'billing_invoice_series',
        'billing_invoice_number_reservations',
        'billing_invoices',
        'billing_invoice_lines',
        'billing_quotes',
        'billing_quote_lines',
        'billing_credit_notes',
        'billing_credit_note_lines',
        'billing_payments',
        'billing_payment_allocations',
        'billing_reminders',
        'billing_document_renders',
        'billing_e_invoice_exports',
        'billing_audit_logs',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            $this->backfillUuidIdentifiers($table);
        }
    }

    public function down(): void
    {
        //
    }

    private function backfillUuidIdentifiers(string $table): void
    {
        DB::table($table)
            ->whereNull('uuid_identifier')
            ->orderBy('id')
            ->chunkById(100, function ($rows) use ($table): void {
                foreach ($rows as $row) {
                    DB::table($table)
                        ->where('id', $row->id)
                        ->update(['uuid_identifier' => (string) Str::uuid()]);
                }
            });
    }
};
