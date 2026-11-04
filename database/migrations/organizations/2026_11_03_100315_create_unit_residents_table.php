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
        Schema::create('unit_residents', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('resident_id')
                ->comment('معرف القاطن');

            // العلاقات الأساسية
            $table->unsignedInteger('unit_id')
                ->comment('معرف الوحدة');

            $table->unsignedInteger('tenant_id')
                ->comment('معرف المستأجر الرئيسي (رب الأسرة)');

            $table->unsignedInteger('relationship_id')
                ->comment('معرف علاقة القرابة');

            // البيانات الشخصية
            $table->string('full_name', 255)
                ->comment('الاسم الكامل');

            $table->enum('gender', [
                'male',
                'female',
            ])
                ->nullable()
                ->comment('الجنس');

            $table->date('birth_date')
                ->nullable()
                ->comment('تاريخ الميلاد');

            // العمر يُحسب عند الاستعلام اعتماداً على تاريخ الميلاد

            $table->string('id_number', 50)
                ->nullable()
                ->comment('رقم الهوية');

            $table->string('phone', 50)
                ->nullable()
                ->comment('رقم الجوال');

            // الجنسية
            $table->boolean('is_saudi')
                ->default(true)
                ->comment('هل هو سعودي؟');

            // الملاحظات
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات');

            // حالة الإقامة
            $table->boolean('is_active')
                ->default(true)
                ->comment('هل لا يزال مقيماً؟');

            $table->date('moved_out_date')
                ->nullable()
                ->comment('تاريخ الانتقال (إن خرج)');

            // تواريخ الإنشاء والتحديث
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            $table->dateTime('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate()
                ->comment('تاريخ آخر تحديث');

            // الفهارس
            $table->index('unit_id', 'idx_unit');
            $table->index('tenant_id', 'idx_tenant');

            // العلاقة مع الوحدات
            $table->foreign('unit_id')
                ->references('unit_id')
                ->on('units')
                ->onDelete('cascade');

            // العلاقة مع المستأجر الرئيسي
            $table->foreign('tenant_id')
                ->references('tenant_id')
                ->on('tenants')
                ->onDelete('cascade');

            // العلاقة مع جدول صلات القرابة
            $table->foreign('relationship_id')
                ->references('relationship_id')
                ->on('family_relationships')
                ->onDelete('cascade');

            // وصف الجدول
            $table->comment(
                'السكان - تسجيل أفراد الأسرة المقيمين مع المستأجر في الوحدة السكنية'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_residents');
    }
};
