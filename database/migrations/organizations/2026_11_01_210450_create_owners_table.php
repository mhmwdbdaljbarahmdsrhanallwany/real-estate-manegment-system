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
        Schema::create('owners', function (Blueprint $table) {

            // ==============================
            // المفتاح الأساسي
            // ==============================

            $table->id('owner_id')
                ->comment('معرف المالك');

            // ==============================
            // العلاقات
            // ==============================

            $table->unsignedBigInteger('organization_id')
                ->nullable()
                ->comment('معرف المؤسسة المسؤولة');

            // ==============================
            // بيانات المالك
            // ==============================

            $table->string('full_name', 255)
                ->comment('الاسم الكامل للمالك');

            $table->string('phone', 50)
                ->nullable()
                ->comment('رقم الجوال');

            $table->string('phone2', 50)
                ->nullable()
                ->comment('رقم جوال ثانوي');

            $table->string('email', 100)
                ->nullable()
                ->comment('البريد الإلكتروني');

            $table->string('address', 255)
                ->nullable()
                ->comment('العنوان');

            $table->string('id_number', 50)
                ->nullable()
                ->comment('رقم الهوية الوطنية أو الإقامة');

            $table->enum('id_type', [
                'national_id',
                'iqama',
                'passport',
                'commercial_reg'
            ])
                ->default('national_id')
                ->comment('نوع الهوية');

            $table->string('id_image', 500)
                ->nullable()
                ->comment('مسار صورة الهوية');

            $table->string('nationality', 50)
                ->default('سعودي')
                ->comment('الجنسية');

            // ==============================
            // البيانات البنكية
            // ==============================

            $table->string('bank_name', 100)
                ->nullable()
                ->comment('اسم البنك');

            $table->string('bank_account_name', 255)
                ->nullable()
                ->comment('اسم صاحب الحساب');

            $table->string('bank_account_number', 50)
                ->nullable()
                ->comment('رقم الحساب');

            $table->string('bank_iban', 50)
                ->nullable()
                ->comment('رقم الآيبان');

            $table->enum('preferred_payment_method', [
                'bank_transfer',
                'cheque',
                'cash'
            ])
                ->default('bank_transfer')
                ->comment('طريقة الدفع المفضلة');

            // ==============================
            // ملاحظات
            // ==============================

            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات إضافية');

            // ==============================
            // حالة المالك
            // ==============================

            $table->boolean('is_active')
                ->default(true)
                ->comment('هل المالك نشط؟');

            // ==============================
            // الطوابع الزمنية
            // ==============================

            $table->timestamp('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            $table->timestamp('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate()
                ->comment('تاريخ آخر تحديث');

            $table->softDeletes('deleted_at')
                ->comment('تاريخ الحذف المنطقي');

            // ==============================
            // الفهارس
            // ==============================

            $table->index('organization_id', 'idx_organization_id');
            $table->index('phone', 'idx_phone');
            $table->index('id_number', 'idx_id_number');
            $table->index('deleted_at', 'idx_deleted_at');

            // ==============================
            // العلاقات
            // ==============================

            $table->foreign('organization_id')
                ->references('organization_id')
                ->on('organizations')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
