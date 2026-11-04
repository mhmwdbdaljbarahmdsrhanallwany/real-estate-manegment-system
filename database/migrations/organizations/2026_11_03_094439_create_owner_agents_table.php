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
        Schema::create('owner_agents', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('agent_id')
                ->comment('معرف الوكيل');

            // ربط الوكيل بالمالك الأصلي
            $table->unsignedBigInteger('owner_id')
                ->comment('معرف المالك الأصلي');

            // البيانات الشخصية للوكيل
            $table->string('full_name', 255)
                ->comment('الاسم الكامل للوكيل');

            $table->string('phone', 50)
                ->nullable()
                ->comment('رقم الجوال');

            $table->string('email', 100)
                ->nullable()
                ->comment('البريد الإلكتروني');

            // دور الوكيل
            $table->enum('role', [
                'administrative',
                'legal',
                'property_manager',
            ])
                ->default('administrative')
                ->comment('دور الوكيل');

            // ملف التوكيل الرسمي
            $table->string('power_of_attorney_file', 500)
                ->nullable()
                ->comment('ملف التوكيل الرسمي');

            // فترة التفويض
            $table->date('authority_start_date')
                ->nullable()
                ->comment('تاريخ بداية التفويض');

            $table->date('authority_end_date')
                ->nullable()
                ->comment('تاريخ نهاية التفويض');

            // ملاحظات إضافية
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات');

            // حالة الوكيل
            $table->boolean('is_active')
                ->default(true)
                ->comment('هل الوكيل نشط؟');

            // تواريخ الإنشاء والتحديث
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            $table->dateTime('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate()
                ->comment('تاريخ آخر تحديث');

            // الفهرس
            $table->index('owner_id', 'idx_owner');

            // المفتاح الخارجي
            $table->foreign('owner_id')
                ->references('owner_id')
                ->on('owners')
                ->onDelete('cascade');

            // وصف الجدول
            $table->comment(
                'وكلاء المالك - الأشخاص المخولين بإدارة العقار نيابة عن المالك'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_agents');
    }
};
