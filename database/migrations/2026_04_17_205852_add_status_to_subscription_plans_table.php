<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->enum('status', [
                'pending_approval',
                'active',
                'suspended',
                'blocked',
                'expired'
            ])->default('pending_approval')->comment('حالة الباقة');
        });
    }
    
    public function down()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

};