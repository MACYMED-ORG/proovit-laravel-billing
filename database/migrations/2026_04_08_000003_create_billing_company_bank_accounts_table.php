<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('billing_company_bank_accounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('billing_companies')->cascadeOnDelete();
            $table->foreignId('establishment_id')->nullable()->constrained('billing_company_establishments')->nullOnDelete();
            $table->string('label');
            $table->string('iban', 34)->nullable()->index();
            $table->string('bic', 11)->nullable()->index();
            $table->string('bank_name')->nullable();
            $table->string('account_holder')->nullable();
            $table->boolean('is_default')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_company_bank_accounts');
    }
};

