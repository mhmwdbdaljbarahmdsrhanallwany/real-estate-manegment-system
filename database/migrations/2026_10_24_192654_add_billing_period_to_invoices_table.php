<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->date('billing_period_start')->nullable()->index();
            $table->date('billing_period_end')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['billing_period_start']);
            $table->dropIndex(['billing_period_end']);
        
            $table->dropColumn([
                'billing_period_start',
                'billing_period_end',
            ]);
        });
    }
};
