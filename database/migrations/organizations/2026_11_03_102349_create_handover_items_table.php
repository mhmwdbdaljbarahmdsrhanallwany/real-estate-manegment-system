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
        Schema::create('handover_items', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('item_id')
                ->comment('معرف الغرض');

            // اسم الغرض
            $table->string('name', 100)
                ->comment('اسم الغرض (ثلاجة، غسالة، مكيف، إلخ)');

            // الاسم بالإنجليزية
            $table->string('name_en', 100)
                ->nullable()
                ->comment('الاسم بالإنجليزية');

            // فئة الغرض
            $table->string('category', 50)
                ->nullable()
                ->comment('فئة الغرض (أجهزة، أثاث، سباكة، إلخ)');

            // وصف الغرض
            $table->text('description')
                ->nullable()
                ->comment('وصف الغرض');

            // السعر التقديري
            $table->decimal('estimated_price', 12, 2)
                ->nullable()
                ->comment('السعر التقديري');

            // الماركة والموديل
            $table->string('brand', 100)
                ->nullable()
                ->comment('الماركة');

            $table->string('model', 100)
                ->nullable()
                ->comment('الموديل');

            // حالة الغرض
            $table->boolean('is_active')
                ->default(true)
                ->comment('هل العنصر نشط؟');

            // تاريخ الإنشاء
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            // الفهارس
            $table->index('category', 'idx_category');

            // وصف الجدول
            $table->comment(
                'أنواع الأغراض - قائمة الأجهزة والأثاث والمحتويات التي يمكن تسليمها مع الوحدة'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handover_items');
    }
};
