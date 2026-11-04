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
        Schema::create('deposit_rules', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('rule_id')
                ->comment('معرف القاعدة');

            // نطاق قيمة الإيجار
            $table->decimal('min_rent_amount', 12, 2)
                ->nullable()
                ->comment('الحد الأدنى لقيمة الإيجار');

            $table->decimal('max_rent_amount', 12, 2)
                ->nullable()
                ->comment('الحد الأقصى لقيمة الإيجار');

            // إعدادات حساب التأمين
            $table->integer('deposit_months')
                ->nullable()
                ->comment('عدد أشهر التأمين');

            $table->decimal('deposit_percentage', 5, 2)
                ->nullable()
                ->comment('نسبة من الإيجار (بدلاً من الأشهر)');

            // طريقة الحساب
            $table->enum('calculation_method', [
                'months',
                'percentage',
                'fixed',
            ])
                ->default('months')
                ->comment('طريقة الحساب');

            // المبلغ الثابت
            $table->decimal('fixed_amount', 12, 2)
                ->nullable()
                ->comment('مبلغ ثابت (إن كانت الطريقة fixed)');

            // حالة القاعدة
            $table->boolean('is_active')
                ->default(true)
                ->comment('هل القاعدة مفعلة؟');

            // أولوية تطبيق القاعدة
            $table->integer('priority')
                ->default(0)
                ->comment('أولوية تطبيق القاعدة');

            // تواريخ الإنشاء والتحديث
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            $table->dateTime('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate()
                ->comment('تاريخ آخر تحديث');

            // وصف الجدول
            $table->comment(
                'سياسات التأمين - قواعد حساب مبلغ التأمين المستحق بناءً على قيمة الإيجار'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_rules');
    }
};
