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
        Schema::table('subscription_plans', function (Blueprint $table) {
            // نوع الباقة: يحدد إذا كانت شهرية أو سنوية أو أطول (مثلاً quarterly, yearly, long_term)
            $table->string('subscription_type')->default('monthly')->after('plan_name');

            // الفترة التجريبية: القيمة (مثلاً 7 أو 14 أو 30)
            $table->integer('trial_period_value')->nullable()->after('subscription_type');

            // الفترة التجريبية: الوحدة الزمنية (days للأيام، weeks للأسابيع، months للأشهر)
            $table->enum('trial_period_unit', ['days','weeks','months'])->default('days')->after('trial_period_value');

            // غرامة التأخير: قيمة رقمية (مثلاً 50.00)
            $table->decimal('late_fee', 10, 2)->nullable()->after('trial_period_unit');

            // فترة السماح: عدد الأيام أو الأسابيع قبل تطبيق الغرامة
            $table->integer('grace_period')->nullable()->after('late_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            // حذف الحقول المضافة عند التراجع عن الميجريشن
            $table->dropColumn([
                'subscription_type',
                'trial_period_value',
                'trial_period_unit',
                'late_fee',
                'grace_period'
            ]);
        });
    }
};
