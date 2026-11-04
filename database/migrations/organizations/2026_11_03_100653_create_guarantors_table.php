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
        Schema::create('guarantors', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('guarantor_id')
                ->comment('معرف الضامن');

            // البيانات الشخصية
            $table->string('full_name', 255)
                ->comment('الاسم الكامل للضامن');

            $table->string('phone', 50)
                ->comment('رقم الجوال');

            $table->string('email', 100)
                ->nullable()
                ->comment('البريد الإلكتروني');

            $table->string('address', 255)
                ->nullable()
                ->comment('العنوان');

            // نوع الضامن
            $table->enum('guarantor_type', [
                'person',
                'company',
            ])
                ->default('person')
                ->comment('نوع الضامن (شخص أو شركة)');

            // الهوية أو السجل التجاري
            $table->string('id_number', 50)
                ->nullable()
                ->comment('رقم الهوية أو السجل التجاري');

            $table->string('id_image', 500)
                ->nullable()
                ->comment('صورة الهوية');

            // المعلومات المالية والمهنية
            $table->decimal('monthly_income', 12, 2)
                ->nullable()
                ->comment('الدخل الشهري (للأشخاص)');

            $table->string('work_place', 255)
                ->nullable()
                ->comment('جهة العمل');

            // الملاحظات
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات');

            // حالة الضامن
            $table->boolean('is_active')
                ->default(true)
                ->comment('هل الضامن نشط؟');

            // تواريخ الإنشاء والتحديث
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            $table->dateTime('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate()
                ->comment('تاريخ آخر تحديث');

            // الفهرس
            $table->index('phone', 'idx_phone');

            // وصف الجدول
            $table->comment(
                'الضامنين - تسجيل بيانات الأشخاص أو الشركات الضامنة للعقود'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantors');
    }
};
