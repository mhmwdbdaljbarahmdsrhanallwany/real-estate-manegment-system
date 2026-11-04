<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{ /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('unit_damages', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('damage_id')
                ->comment('معرف الضرر');

            // الفحص الذي اكتشف الضرر
            $table->unsignedInteger('inspection_id')
                ->comment('معرف الفحص الذي اكتشف الضرر');

            // وصف الضرر
            $table->text('description')
                ->comment('وصف الضرر');

            // درجة خطورة الضرر
            $table->enum('severity', [
                'minor',
                'moderate',
                'severe',
                'critical',
            ])
                ->default('minor')
                ->comment('خطورة الضرر');

            // موقع الضرر
            $table->string('location', 255)
                ->nullable()
                ->comment('موقع الضرر في الوحدة');

            // تكاليف الإصلاح
            $table->decimal('estimated_cost', 12, 2)
                ->nullable()
                ->comment('التكلفة التقديرية للإصلاح');

            $table->decimal('actual_cost', 12, 2)
                ->nullable()
                ->comment('التكلفة الفعلية للإصلاح');

            // حالة الإصلاح
            $table->boolean('repaired')
                ->default(false)
                ->comment('هل تم الإصلاح؟');

            $table->date('repaired_date')
                ->nullable()
                ->comment('تاريخ الإصلاح');

            $table->string('repaired_by', 255)
                ->nullable()
                ->comment('من قام بالإصلاح');

            // الاستقطاع من التأمين
            $table->boolean('deduction_from_deposit')
                ->default(false)
                ->comment('هل تم الاستقطاع من التأمين؟');

            // الملاحظات
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات');

            // صور الضرر
            $table->json('images')
                ->nullable()
                ->comment('صور الضرر');

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

            $table->index('inspection_id', 'idx_inspection');

            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */

            // العلاقة مع جدول فحص الوحدات
            $table->foreign('inspection_id')
                ->references('inspection_id')
                ->on('unit_inspections')
                ->onDelete('cascade');

            // وصف الجدول
            $table->comment(
                'الأضرار - تسجيل الأضرار المكتشفة في الوحدات وتكاليف إصلاحها'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_damages');
    }
};
