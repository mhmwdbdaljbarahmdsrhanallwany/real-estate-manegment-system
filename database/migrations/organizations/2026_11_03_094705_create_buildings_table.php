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
        Schema::create('buildings', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('building_id')
                ->comment('معرف المبنى');

            // العلاقات الأساسية
            $table->unsignedInteger('owner_id')
                ->comment('معرف المالك');

            $table->unsignedInteger('organization_id')
                ->nullable()
                ->comment('معرف المؤسسة المسؤولة');

            $table->unsignedInteger('branch_id')
                ->nullable()
                ->comment('معرف الفرع المسؤول');

            // البيانات الأساسية للمبنى
            $table->string('building_name', 255)
                ->comment('اسم المبنى أو العمارة');

            $table->string('building_number', 50)
                ->nullable()
                ->comment('رقم المبنى الوطني');

            // العنوان
            $table->string('address_line1', 255)
                ->nullable()
                ->comment('السطر الأول من العنوان');

            $table->string('address_line2', 255)
                ->nullable()
                ->comment('السطر الثاني من العنوان');

            $table->string('city', 100)
                ->nullable()
                ->comment('المدينة');

            $table->string('district', 100)
                ->nullable()
                ->comment('الحي');

            $table->string('street', 100)
                ->nullable()
                ->comment('الشارع');

            $table->string('postal_code', 20)
                ->nullable()
                ->comment('الرمز البريدي');

            $table->string('country', 50)
                ->default('السعودية')
                ->comment('الدولة');

            // الإحداثيات الجغرافية
            $table->decimal('latitude', 10, 7)
                ->nullable()
                ->comment('خط العرض');

            $table->decimal('longitude', 10, 7)
                ->nullable()
                ->comment('خط الطول');

            // مواصفات المبنى
            $table->integer('number_of_floors')
                ->nullable()
                ->comment('عدد الأدوار');

            $table->integer('number_of_units')
                ->default(0)
                ->comment('عدد الوحدات (يتم تحديثه تلقائياً)');

            $table->integer('year_built')
                ->nullable()
                ->comment('سنة البناء');

            $table->decimal('land_area', 10, 2)
                ->nullable()
                ->comment('مساحة الأرض (م²)');

            $table->decimal('building_area', 10, 2)
                ->nullable()
                ->comment('مساحة المبنى (م²)');

            // نوع العقار
            $table->enum('property_type', [
                'residential',
                'commercial',
                'industrial',
                'agricultural',
                'mixed',
            ])
                ->default('residential')
                ->comment('نوع العقار');

            // الخدمات والمرافق
            $table->boolean('has_elevator')
                ->default(false)
                ->comment('يوجد مصعد');

            $table->boolean('has_parking')
                ->default(false)
                ->comment('يوجد موقف سيارات');

            $table->boolean('has_security')
                ->default(false)
                ->comment('يوجد حراسة');

            $table->boolean('has_maintenance')
                ->default(false)
                ->comment('يوجد صيانة');

            // الملاحظات
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات إضافية');

            // الصور والمستندات
            $table->json('images')
                ->nullable()
                ->comment('صور المبنى');

            $table->json('documents')
                ->nullable()
                ->comment('مستندات المبنى (رخصة بناء، صك ملكية، إلخ)');

            // حالة المبنى
            $table->boolean('is_active')
                ->default(true)
                ->comment('هل المبنى نشط؟');

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
            $table->index('owner_id', 'idx_owner');
            $table->index('organization_id', 'idx_agency');
            $table->index('city', 'idx_city');
            $table->index('deleted_at', 'idx_deleted_at');

            // العلاقات الخارجية
            $table->foreign('owner_id')
                ->references('owner_id')
                ->on('owners')
                ->onDelete('cascade');

            $table->foreign('organization_id')
                ->references('organization_id')
                ->on('organizations')
                ->onDelete('set null');

            $table->foreign('branch_id')
                ->references('branch_id')
                ->on('agency_branches')
                ->onDelete('set null');

            // وصف الجدول
            $table->comment(
                'جدول المباني والعمارات - تخزين بيانات المباني بما فيها الموقع والمواصفات والصور'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
