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
        Schema::create('unit_types', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('type_id')
                ->comment('معرف النوع');

            // اسم النوع
            $table->string('name', 100)
                ->comment('اسم النوع (شقة، فيلا، مكتب، محل، إلخ)');

            $table->string('name_en', 100)
                ->nullable()
                ->comment('الاسم بالإنجليزية');

            // وصف النوع
            $table->text('description')
                ->nullable()
                ->comment('وصف النوع');

            // أيقونة النوع
            $table->string('icon', 50)
                ->nullable()
                ->comment('أيقونة للنوع');

            // ترتيب العرض
            $table->integer('sort_order')
                ->default(0)
                ->comment('ترتيب العرض');

            // حالة النوع
            $table->boolean('is_active')
                ->default(true)
                ->comment('هل النوع مفعل؟');

            // تاريخ الإنشاء
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            // الفهرس
            $table->index('is_active', 'idx_active');

            // وصف الجدول
            $table->comment(
                'أنواع الوحدات - تصنيف الوحدات إلى شقق، فلل، مكاتب، محلات تجارية، إلخ'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_types');
    }
};
