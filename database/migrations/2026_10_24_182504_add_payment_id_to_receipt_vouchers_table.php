<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('receipt_vouchers', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_id')
                ->nullable()
                ->after('invoice_id')
                ->comment('معرف الدفعة المرتبطة بسند القبض');

            $table->foreign('payment_id')
                ->references('payment_id')
                ->on('payments')
                ->restrictOnDelete();

            $table->unique('payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('receipt_vouchers', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
            $table->dropUnique(['payment_id']);
            $table->dropColumn('payment_id');
        });
    }
};