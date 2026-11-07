
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buildings', function (Blueprint $table) {

            // =====================================
            // 1. المفتاح الأساسي
            // =====================================

            $table->increments('building_id')
                ->comment('معرف المبنى');

            // =====================================
            // 2. العلاقات الأساسية
            // =====================================

            $table->unsignedBigInteger('owner_id')
                ->comment('معرف المالك');

            $table->unsignedBigInteger('organization_id')
                ->nullable()
                ->comment('معرف المؤسسة المسؤولة');

            $table->unsignedInteger('branch_id')
                ->nullable()
                ->comment('معرف الفرع المسؤول');

            // =====================================
            // 3. البيانات الأساسية
            // =====================================

            $table->string('building_name', 255)
                ->comment('اسم المبنى');

            $table->string('building_number', 50)
                ->nullable()
                ->comment('رقم المبنى الوطني');

            // =====================================
            // 4. العنوان
            // =====================================

            $table->string('address_line1', 255)->nullable();
            $table->string('address_line2', 255)->nullable();

            $table->string('city', 100)->nullable()
                ->comment('المدينة');

            $table->string('district', 100)->nullable()
                ->comment('الحي');

            $table->string('street', 100)->nullable()
                ->comment('الشارع');

            $table->string('postal_code', 20)->nullable()
                ->comment('الرمز البريدي');

            $table->string('country', 50)
                ->default('السعودية')
                ->comment('الدولة');

            // =====================================
            // 5. الموقع الجغرافي
            // =====================================

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // =====================================
            // 6. مواصفات المبنى
            // =====================================

            $table->unsignedInteger('number_of_floors')
                ->nullable();

            $table->unsignedInteger('number_of_units')
                ->default(0);

            $table->unsignedSmallInteger('year_built')
                ->nullable();

            $table->decimal('land_area', 10, 2)->nullable();
            $table->decimal('building_area', 10, 2)->nullable();

            // =====================================
            // 7. نوع العقار
            // =====================================

            $table->enum('property_type', [
                'residential',
                'commercial',
                'industrial',
                'agricultural',
                'mixed',
            ])->default('residential');

            // =====================================
            // 8. الخدمات والمرافق
            // =====================================

            $table->boolean('has_elevator')->default(false);
            $table->boolean('has_parking')->default(false);
            $table->boolean('has_security')->default(false);
            $table->boolean('has_maintenance')->default(false);

            // =====================================
            // 9. الملاحظات والمرفقات
            // =====================================

            $table->text('notes')->nullable();

            $table->json('images')->nullable();
            $table->json('documents')->nullable();

            // =====================================
            // 10. حالة المبنى
            // =====================================

            $table->boolean('is_active')
                ->default(true);

            // =====================================
            // 11. التواريخ
            // =====================================

            $table->dateTime('created_at')
                ->useCurrent();

            $table->dateTime('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();

            $table->dateTime('deleted_at')
                ->nullable();

            // =====================================
            // 12. الفهارس
            // =====================================

            $table->index('owner_id', 'idx_building_owner');
            $table->index('organization_id', 'idx_building_org');
            $table->index('branch_id', 'idx_building_branch');
            $table->index('city', 'idx_building_city');
            $table->index('deleted_at', 'idx_building_deleted');

            // =====================================
            // 13. المفتاح الأجنبي للمالك
            // =====================================

            $table->foreign('owner_id', 'fk_building_owner')
                ->references('owner_id')
                ->on('owners')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // =====================================
            // 14. المفتاح الأجنبي للمؤسسة
            // =====================================

            $table->foreign('organization_id', 'fk_building_organization')
                ->references('organization_id')
                ->on('organizations')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // =====================================
            // 15. المفتاح الأجنبي للفرع
            // =====================================

            $table->foreign('branch_id', 'fk_building_branch')
                ->references('branch_id')
                ->on('agency_branches')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // =====================================
            // 16. وصف الجدول
            // =====================================

            $table->comment(
                'جدول المباني والعمارات - تخزين بيانات المباني والموقع والمواصفات والصور'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
