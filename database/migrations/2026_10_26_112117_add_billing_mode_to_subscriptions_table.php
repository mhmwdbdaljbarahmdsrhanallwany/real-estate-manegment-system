<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->enum('billing_mode', [
                'full',
                'periodic',
            ])
                ->default('full')
                ->after('subscription_type')
                ->comment('طريقة الفوترة: كاملة أو دورية');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('billing_mode');
        });
    }
};
