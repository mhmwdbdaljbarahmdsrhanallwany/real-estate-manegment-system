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
        Schema::table('audit_logs', function (Blueprint $table) {

            $table->unsignedBigInteger('action_id')
                ->nullable()
                ->after('audit_log_id')
                ->comment('الإجراء المرتبط بسجل التدقيق');


            $table->foreign('action_id')
                ->references('action_id')
                ->on('actions')
                ->nullOnDelete()
                ->comment('الإجراء الذي نتج عنه سجل التدقيق');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {

            $table->dropForeign(['action_id']);

            $table->dropColumn('action_id');

        });
    }
};
