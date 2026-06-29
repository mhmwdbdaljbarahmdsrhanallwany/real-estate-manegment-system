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
        Schema::table('agency_subscriptions', function (Blueprint $table) {
            $table->enum('PlanStatus', ['trial', 'active', 'expired', 'grace_period'])
                  ->default('trial')
                  ->comment('حالة الاشتراك: تجريبي، نشط، منتهي، فترة سماح')
                  ->change();
        });
    }
    
    public function down()
    {
        Schema::table('agency_subscriptions', function (Blueprint $table) {
            $table->enum('PlanStatus', ['pending','active','suspended','blocked','expired'])
                  ->default('pending')
                  ->comment('الحالة القديمة')
                  ->change();
        });
    }
    
};
