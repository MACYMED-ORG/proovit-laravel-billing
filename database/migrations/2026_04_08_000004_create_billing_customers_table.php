<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('billing_customers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('billing_companies')->nullOnDelete();
            $table->string('legal_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('reference')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('vat_number')->nullable()->index();
            $table->json('billing_address')->nullable();
            $table->json('shipping_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_customers');
    }
};

