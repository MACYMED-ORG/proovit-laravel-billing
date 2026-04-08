<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('billing_companies', function (Blueprint $table): void {
            $table->id();
            $table->string('legal_name');
            $table->string('display_name')->nullable();
            $table->string('legal_form')->nullable();
            $table->string('registration_country', 2)->default('FR');
            $table->string('siren', 9)->nullable()->index();
            $table->string('siret', 14)->nullable()->index();
            $table->string('vat_number')->nullable()->index();
            $table->string('intracommunity_vat_number')->nullable()->index();
            $table->string('naf_ape')->nullable();
            $table->string('rcs_city')->nullable();
            $table->json('head_office_address')->nullable();
            $table->json('billing_address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->char('default_currency', 3)->default('EUR');
            $table->string('default_locale', 12)->default('fr');
            $table->string('timezone', 64)->default('Europe/Paris');
            $table->unsignedInteger('default_payment_terms')->default(30);
            $table->string('invoice_prefix')->default('INV');
            $table->string('invoice_sequence_pattern')->default('{prefix}-{year}{month}-{sequence}');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_companies');
    }
};

