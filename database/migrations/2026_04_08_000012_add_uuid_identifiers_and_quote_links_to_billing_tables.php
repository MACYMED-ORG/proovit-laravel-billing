<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class () extends Migration {
    /**
     * @var array<string, array{table: string, id_column: string}>
     */
    private array $uuidTables = [
        'billing_companies' => ['table' => 'billing_companies', 'id_column' => 'id'],
        'billing_company_establishments' => ['table' => 'billing_company_establishments', 'id_column' => 'id'],
        'billing_company_bank_accounts' => ['table' => 'billing_company_bank_accounts', 'id_column' => 'id'],
        'billing_customers' => ['table' => 'billing_customers', 'id_column' => 'id'],
        'billing_customer_addresses' => ['table' => 'billing_customer_addresses', 'id_column' => 'id'],
        'billing_products' => ['table' => 'billing_products', 'id_column' => 'id'],
        'billing_product_prices' => ['table' => 'billing_product_prices', 'id_column' => 'id'],
        'billing_tax_rates' => ['table' => 'billing_tax_rates', 'id_column' => 'id'],
        'billing_invoice_series' => ['table' => 'billing_invoice_series', 'id_column' => 'id'],
        'billing_invoice_number_reservations' => ['table' => 'billing_invoice_number_reservations', 'id_column' => 'id'],
        'billing_invoices' => ['table' => 'billing_invoices', 'id_column' => 'id'],
        'billing_invoice_lines' => ['table' => 'billing_invoice_lines', 'id_column' => 'id'],
        'billing_quotes' => ['table' => 'billing_quotes', 'id_column' => 'id'],
        'billing_quote_lines' => ['table' => 'billing_quote_lines', 'id_column' => 'id'],
        'billing_credit_notes' => ['table' => 'billing_credit_notes', 'id_column' => 'id'],
        'billing_credit_note_lines' => ['table' => 'billing_credit_note_lines', 'id_column' => 'id'],
        'billing_payments' => ['table' => 'billing_payments', 'id_column' => 'id'],
        'billing_payment_allocations' => ['table' => 'billing_payment_allocations', 'id_column' => 'id'],
        'billing_reminders' => ['table' => 'billing_reminders', 'id_column' => 'id'],
        'billing_document_renders' => ['table' => 'billing_document_renders', 'id_column' => 'id'],
        'billing_e_invoice_exports' => ['table' => 'billing_e_invoice_exports', 'id_column' => 'id'],
        'billing_audit_logs' => ['table' => 'billing_audit_logs', 'id_column' => 'id'],
    ];

    public function up(): void
    {
        foreach ($this->uuidTables as $table => $config) {
            Schema::table($config['table'], function (Blueprint $table): void {
                $table->uuid('uuid_identifier')->nullable()->after('id')->unique();
            });
        }

        Schema::table('billing_invoices', function (Blueprint $table): void {
            $table->foreignId('quote_id')->nullable()->after('customer_id')->constrained('billing_quotes')->nullOnDelete();
        });

        Schema::table('billing_quotes', function (Blueprint $table): void {
            $table->foreignId('converted_invoice_id')->nullable()->after('customer_id')->constrained('billing_invoices')->nullOnDelete();
        });

        foreach ($this->uuidTables as $config) {
            $this->backfillUuidIdentifiers($config['table']);
        }
    }

    public function down(): void
    {
        Schema::table('billing_quotes', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('converted_invoice_id');
        });

        Schema::table('billing_invoices', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('quote_id');
        });

        foreach ($this->uuidTables as $config) {
            Schema::table($config['table'], function (Blueprint $table): void {
                $table->dropUnique(['uuid_identifier']);
                $table->dropColumn('uuid_identifier');
            });
        }
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
