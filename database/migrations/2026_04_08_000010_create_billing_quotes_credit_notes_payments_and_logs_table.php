<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('billing_quotes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('billing_companies')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('billing_customers')->nullOnDelete();
            $table->string('status', 32)->default('draft')->index();
            $table->string('number')->nullable()->unique();
            $table->json('seller_snapshot')->nullable();
            $table->json('customer_snapshot')->nullable();
            $table->decimal('subtotal_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('billing_credit_notes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('billing_companies')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('billing_invoices')->nullOnDelete();
            $table->string('status', 32)->default('draft')->index();
            $table->string('number')->nullable()->unique();
            $table->json('seller_snapshot')->nullable();
            $table->json('customer_snapshot')->nullable();
            $table->decimal('subtotal_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('billing_payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('billing_companies')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('billing_customers')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('billing_invoices')->nullOnDelete();
            $table->string('status', 32)->default('pending')->index();
            $table->string('method', 32)->nullable()->index();
            $table->char('currency', 3)->default('EUR');
            $table->decimal('amount', 18, 2);
            $table->date('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('billing_payment_allocations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('payment_id')->constrained('billing_payments')->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained('billing_invoices')->cascadeOnDelete();
            $table->decimal('amount', 18, 2);
            $table->timestamps();
        });

        Schema::create('billing_reminders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('billing_companies')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('billing_invoices')->nullOnDelete();
            $table->string('channel', 32)->index();
            $table->string('status', 32)->default('draft')->index();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('billing_document_renders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('billing_companies')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('billing_invoices')->nullOnDelete();
            $table->string('document_type', 32)->index();
            $table->string('render_type', 32)->index();
            $table->string('disk')->nullable();
            $table->string('path')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->timestamps();
        });

        Schema::create('billing_e_invoice_exports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('billing_companies')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('billing_invoices')->nullOnDelete();
            $table->string('format', 32)->index();
            $table->string('status', 32)->default('pending')->index();
            $table->string('disk')->nullable();
            $table->string('path')->nullable();
            $table->timestamps();
        });

        Schema::create('billing_audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('billing_companies')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('auditable_type')->nullable()->index();
            $table->unsignedBigInteger('auditable_id')->nullable()->index();
            $table->string('event')->index();
            $table->json('context')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_audit_logs');
        Schema::dropIfExists('billing_e_invoice_exports');
        Schema::dropIfExists('billing_document_renders');
        Schema::dropIfExists('billing_reminders');
        Schema::dropIfExists('billing_payment_allocations');
        Schema::dropIfExists('billing_payments');
        Schema::dropIfExists('billing_credit_notes');
        Schema::dropIfExists('billing_quotes');
    }
};

