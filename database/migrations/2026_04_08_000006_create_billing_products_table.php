<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('billing_products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('billing_companies')->nullOnDelete();
            $table->string('sku')->nullable()->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->char('currency', 3)->default('EUR');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_products');
    }
};

