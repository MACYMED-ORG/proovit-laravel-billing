<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('billing_company_establishments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('billing_companies')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable()->index();
            $table->json('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_default')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_company_establishments');
    }
};

