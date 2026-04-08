<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('billing_invoice_series', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('billing_companies')->cascadeOnDelete();
            $table->foreignId('establishment_id')->nullable()->constrained('billing_company_establishments')->nullOnDelete();
            $table->string('document_type', 32)->index();
            $table->string('name');
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->string('pattern')->default('{prefix}-{year}{month}-{sequence}');
            $table->unsignedInteger('padding')->default(6);
            $table->string('reset_policy', 32)->default('annual');
            $table->unsignedBigInteger('current_sequence')->default(0);
            $table->boolean('is_default')->default(false)->index();
            $table->timestamps();
            $table->unique(['company_id', 'establishment_id', 'document_type', 'name'], 'billing_series_unique');
        });

        Schema::create('billing_invoice_number_reservations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_series_id')->constrained('billing_invoice_series')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('billing_companies')->cascadeOnDelete();
            $table->unsignedBigInteger('invoice_id')->nullable()->index();
            $table->string('document_type', 32)->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('sequence');
            $table->timestamp('reserved_at');
            $table->timestamp('consumed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_invoice_number_reservations');
        Schema::dropIfExists('billing_invoice_series');
    }
};
