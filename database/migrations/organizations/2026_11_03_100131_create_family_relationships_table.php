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
        Schema::create('family_relationships', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('relationship_id')
                ->comment('معرف العلاقة');

            // اسم العلاقة
            $table->string('name', 50)
                ->comment('اسم العلاقة (رب الأسرة، زوجة، ابن، ابنة)');

            // الاسم بالإنجليزية
            $table->string('name_en', 50)
                ->nullable()
                ->comment('اسم العلاقة بالإنجليزية');

            // وصف العلاقة
            $table->text('description')
                ->nullable()
                ->comment('وصف العلاقة');

            // ترتيب العرض
            $table->integer('sort_order')
                ->default(0)
                ->comment('ترتيب العرض');

            // تاريخ الإنشاء
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            // وصف الجدول
            $table->comment(
                'صلات القرابة - تعريف علاقات أفراد الأسرة المقيمين مع المستأجر الرئيسي'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_relationships');
    }
};
