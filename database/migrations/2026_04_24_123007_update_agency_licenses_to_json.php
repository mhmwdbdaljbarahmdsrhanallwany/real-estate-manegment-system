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
        Schema::table('agency', function (Blueprint $table) {
             // نحذف الحقول القديمة أو نعدلها
             $table->dropColumn('municipality_license');
             $table->dropColumn('real_estate_broker_license');
 
             // نضيف الحقول الجديدة كـ JSON
             $table->json('municipality_license')->nullable()->comment('رخصة البلدية - ملفات أو صور');
             $table->json('real_estate_broker_license')->nullable()->comment('رخصة الوساطة العقارية - ملفات أو صور');
         });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agency', function (Blueprint $table) {
             // نرجع الحقول كما كانت سابقًا
             $table->string('municipality_license', 50)->unique()->nullable()->comment('رخصة البلدية - تصريح مزاولة النشاط');
             $table->string('real_estate_broker_license', 50)->nullable()->comment('رخصة الوساطة العقارية من الهيئة العامة للعقار');
 
             $table->dropColumn('municipality_license');
             $table->dropColumn('real_estate_broker_license');
         
        });
    }
};
