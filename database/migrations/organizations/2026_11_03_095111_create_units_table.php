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
        Schema::create('units', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('unit_id')
                ->comment('معرف الوحدة');

            // العلاقات
            $table->unsignedInteger('building_id')
                ->comment('معرف المبنى');

            $table->unsignedInteger('type_id')
                ->nullable()
                ->comment('معرف نوع الوحدة');

            // البيانات الأساسية للوحدة
            $table->string('unit_number', 50)
                ->nullable()
                ->comment('رقم الوحدة');

            $table->integer('floor')
                ->nullable()
                ->comment('رقم الدور');

            $table->decimal('size', 10, 2)
                ->nullable()
                ->comment('المساحة (م²)');

            // تفاصيل الوحدة
            $table->integer('bedrooms')
                ->default(0)
                ->comment('عدد غرف النوم');

            $table->integer('bathrooms')
                ->default(0)
                ->comment('عدد دورات المياه');

            $table->integer('halls')
                ->default(0)
                ->comment('عدد الصالات');

            // المرافق والتجهيزات
            $table->boolean('kitchen')
                ->default(true)
                ->comment('يوجد مطبخ');

            $table->boolean('balcony')
                ->default(false)
                ->comment('يوجد شرفة');

            $table->boolean('furnished')
                ->default(false)
                ->comment('مفروشة');

            // القيمة الإيجارية
            $table->decimal('rent_price', 12, 2)
                ->nullable()
                ->comment('قيمة الإيجار الشهرية الحالية');

            // حالة الوحدة
            $table->enum('status', [
                'available',
                'occupied',
                'maintenance',
                'reserved',
                'closed',
            ])
                ->default('available')
                ->comment('حالة الوحدة (متاحة، مستأجرة، صيانة، محجوزة، مغلقة)');

            // الملاحظات
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات إضافية');

            // الصور والمستندات
            $table->json('images')
                ->nullable()
                ->comment('صور الوحدة');

            $table->json('documents')
                ->nullable()
                ->comment('مستندات الوحدة');

            // تواريخ الصيانة
            $table->date('last_maintenance_date')
                ->nullable()
                ->comment('آخر تاريخ صيانة');

            $table->date('next_maintenance_date')
                ->nullable()
                ->comment('تاريخ الصيانة القادم');

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
            $table->index('building_id', 'idx_building');
            $table->index('status', 'idx_status');
            $table->index('type_id', 'idx_type');
            $table->index('deleted_at', 'idx_deleted_at');

            // العلاقات الخارجية

            // الوحدة تتبع مبنى معين
            $table->foreign('building_id')
                ->references('building_id')
                ->on('buildings')
                ->onDelete('cascade');

            // نوع الوحدة اختياري
            $table->foreign('type_id')
                ->references('type_id')
                ->on('unit_types')
                ->onDelete('set null');

            // وصف الجدول
            $table->comment(
                'جدول الوحدات - تخزين بيانات الوحدات الفردية داخل المباني (شقق، مكاتب، محلات)'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
