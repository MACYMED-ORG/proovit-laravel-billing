<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('billing_invoices', function (Blueprint $table): void {
            $table->string('public_share_token', 128)->nullable()->unique()->after('quote_id');
            $table->timestamp('public_shared_at')->nullable()->after('public_share_token');
            $table->timestamp('public_share_expires_at')->nullable()->after('public_shared_at');
        });
    }

    public function down(): void
    {
        Schema::table('billing_invoices', function (Blueprint $table): void {
            $table->dropUnique(['public_share_token']);
            $table->dropColumn(['public_share_token', 'public_shared_at', 'public_share_expires_at']);
        });
    }
};
