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
        Schema::create('unit_inspections', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('inspection_id')
                ->comment('معرف الفحص');

            // العلاقات الأساسية
            $table->unsignedInteger('unit_id')
                ->comment('معرف الوحدة');

            $table->unsignedInteger('contract_id')
                ->comment('معرف العقد');

            $table->unsignedInteger('inspector_id')
                ->nullable()
                ->comment('معرف المفتش');

            // تاريخ الفحص
            $table->date('inspection_date')
                ->comment('تاريخ الفحص');

            // نوع الفحص
            $table->enum('inspection_type', [
                'initial',
                'final',
                'periodic',
                'emergency',
            ])
                ->default('initial')
                ->comment('نوع الفحص: ابتدائي، نهائي، دوري، طارئ');

            // الحالة العامة للوحدة
            $table->enum('overall_condition', [
                'excellent',
                'good',
                'fair',
                'poor',
                'damaged',
            ])
                ->nullable()
                ->comment('الحالة العامة');

            // تقييم النظافة
            $table->enum('cleanliness_rating', [
                'excellent',
                'good',
                'fair',
                'poor',
            ])
                ->nullable()
                ->comment('تقييم النظافة');

            // الملاحظات العامة
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات عامة');

            // صور الفحص
            $table->json('images')
                ->nullable()
                ->comment('صور الفحص');

            // تقرير الفحص
            $table->string('report_path', 500)
                ->nullable()
                ->comment('مسار تقرير الفحص');

            // تواريخ الإنشاء والتحديث
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            $table->dateTime('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate()
                ->comment('تاريخ آخر تحديث');


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('unit_id', 'idx_unit');

            $table->index('contract_id', 'idx_contract');

            $table->index('inspection_date', 'idx_inspection_date');


            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */

            // العلاقة مع الوحدات
            $table->foreign('unit_id')
                ->references('unit_id')
                ->on('units')
                ->onDelete('cascade');

            // العلاقة مع العقود
            $table->foreign('contract_id')
                ->references('contract_id')
                ->on('contracts')
                ->onDelete('cascade');

            // العلاقة مع المستخدم المفتش
            $table->foreign('inspector_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');


            // وصف الجدول
            $table->comment(
                'فحص الوحدات - تسجيل نتائج فحص الوحدات عند التسليم والاستلام وأثناء الإيجار'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_inspections');
    }
};
