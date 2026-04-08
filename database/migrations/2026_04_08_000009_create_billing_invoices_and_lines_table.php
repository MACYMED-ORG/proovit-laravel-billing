<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('billing_invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('billing_companies')->cascadeOnDelete();
            $table->foreignId('establishment_id')->nullable()->constrained('billing_company_establishments')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('billing_customers')->nullOnDelete();
            $table->foreignId('invoice_series_id')->nullable()->constrained('billing_invoice_series')->nullOnDelete();
            $table->foreignId('invoice_number_reservation_id')->nullable()->constrained('billing_invoice_number_reservations')->nullOnDelete();
            $table->string('document_type', 32)->default('invoice')->index();
            $table->string('status', 32)->default('draft')->index();
            $table->string('number')->nullable()->unique();
            $table->char('currency', 3)->default('EUR');
            $table->date('issued_at')->nullable();
            $table->date('due_at')->nullable();
            $table->json('seller_snapshot')->nullable();
            $table->json('customer_snapshot')->nullable();
            $table->decimal('subtotal_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('finalized_at')->nullable()->index();
            $table->timestamp('cancelled_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('billing_invoice_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained('billing_invoices')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('billing_products')->nullOnDelete();
            $table->foreignId('tax_rate_id')->nullable()->constrained('billing_tax_rates')->nullOnDelete();
            $table->string('description');
            $table->decimal('quantity', 18, 4)->default(1);
            $table->decimal('unit_price', 18, 2)->default(0);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('tax_rate', 8, 4)->default(0);
            $table->decimal('subtotal_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_invoice_lines');
        Schema::dropIfExists('billing_invoices');
    }
};

