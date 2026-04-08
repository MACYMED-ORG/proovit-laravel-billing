<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('billing_tax_rates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('billing_companies')->nullOnDelete();
            $table->string('name');
            $table->decimal('rate', 8, 4);
            $table->string('country', 2)->default('FR');
            $table->boolean('is_default')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('billing_product_prices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('billing_products')->cascadeOnDelete();
            $table->foreignId('tax_rate_id')->nullable()->constrained('billing_tax_rates')->nullOnDelete();
            $table->char('currency', 3)->default('EUR');
            $table->decimal('amount', 18, 2);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_product_prices');
        Schema::dropIfExists('billing_tax_rates');
    }
};

