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
            $table->enum('currency', ['USD','EUR','SAR','GBP','YER'])
                  ->default('USD')
                  ->comment('العملة المستخدمة في الاشتراك')
                  ->change();
        });
    }
    
    public function down()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->enum('currency', ['USD','EUR','SAR','GBP'])
                  ->default('USD')
                  ->comment('العملة المستخدمة في الاشتراك')
                  ->change();
        });
    }
    
};
