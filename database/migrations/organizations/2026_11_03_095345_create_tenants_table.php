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
        Schema::create('tenants', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('tenant_id')
                ->comment('معرف المستأجر');

            // البيانات الشخصية
            $table->string('full_name', 255)
                ->comment('الاسم الكامل');

            // معلومات الاتصال
            $table->string('phone', 50)
                ->comment('رقم الجوال (إجباري للتواصل)');

            $table->string('phone2', 50)
                ->nullable()
                ->comment('رقم جوال ثانوي');

            $table->string('email', 100)
                ->nullable()
                ->comment('البريد الإلكتروني');

            // معلومات الهوية
            $table->string('id_number', 50)
                ->comment('رقم الهوية أو الإقامة');

            $table->enum('id_type', [
                'national_id',
                'iqama',
                'passport',
            ])
                ->default('national_id')
                ->comment('نوع الهوية');

            $table->string('id_image', 500)
                ->nullable()
                ->comment('مسار صورة الهوية');

            // معلومات السكن والعمل
            $table->string('address', 255)
                ->nullable()
                ->comment('عنوان السكن الحالي');

            $table->string('work_place', 255)
                ->nullable()
                ->comment('جهة العمل');

            $table->string('job_title', 100)
                ->nullable()
                ->comment('المسمى الوظيفي');

            $table->decimal('monthly_income', 12, 2)
                ->nullable()
                ->comment('الدخل الشهري');

            // بيانات الاتصال في الطوارئ
            $table->string('emergency_contact_name', 255)
                ->nullable()
                ->comment('اسم جهة الاتصال في الطوارئ');

            $table->string('emergency_contact_phone', 50)
                ->nullable()
                ->comment('رقم جهة الاتصال في الطوارئ');

            // البيانات البنكية
            $table->string('bank_name', 100)
                ->nullable()
                ->comment('اسم البنك');

            $table->string('bank_account_number', 50)
                ->nullable()
                ->comment('رقم الحساب البنكي');

            // الملاحظات
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات إضافية');

            // تقييم المستأجر
            $table->decimal('rating', 2, 1)
                ->default(0)
                ->comment('تقييم المستأجر (من 0 إلى 5)');

            // قائمة الحظر
            $table->boolean('is_blacklisted')
                ->default(false)
                ->comment('هل المستأجر محظور؟');

            $table->text('blacklist_reason')
                ->nullable()
                ->comment('سبب الحظر');

            // تواريخ الإنشاء والتحديث
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            $table->dateTime('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate()
                ->comment('تاريخ آخر تحديث');

            // الحذف المنطقي
            $table->dateTime('deleted_at')
                ->nullable()
                ->comment('تاريخ الحذف المنطقي');

            // الفهارس
            $table->index('phone', 'idx_phone');

            $table->index('id_number', 'idx_id_number');

            $table->index('is_blacklisted', 'idx_is_blacklisted');

            $table->index('deleted_at', 'idx_deleted_at');

            // وصف الجدول
            $table->comment(
                'جدول المستأجرين - تخزين بيانات المستأجرين مع توثيق الهوية ومعلومات الاتصال والتقييم'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
