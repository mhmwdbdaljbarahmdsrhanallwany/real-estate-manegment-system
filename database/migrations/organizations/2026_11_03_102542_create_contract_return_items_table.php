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
        Schema::create('contract_return_items', function (Blueprint $table) {

            // ربط العقد بالغرض
            $table->unsignedInteger('contract_id')
                ->comment('معرف العقد');

            $table->unsignedInteger('item_id')
                ->comment('معرف الغرض');

            // الكمية المعادة
            $table->integer('quantity')
                ->default(1)
                ->comment('الكمية المعادة');

            // حالة الغرض عند الإرجاع
            $table->enum('condition_at_return', [
                'new',
                'good',
                'fair',
                'poor',
                'damaged',
                'missing',
            ])
                ->default('good')
                ->comment('الحالة عند الإرجاع');

            // هل الغرض مفقود؟
            $table->boolean('missing')
                ->default(false)
                ->comment('هل الغرض مفقود؟');

            // وصف التلف
            $table->text('damage_description')
                ->nullable()
                ->comment('وصف التلف إن وجد');

            // ملاحظات إضافية
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات');

            // تاريخ الإنشاء
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            /*
            |--------------------------------------------------------------------------
            | Composite Primary Key
            |--------------------------------------------------------------------------
            */

            $table->primary(
                ['contract_id', 'item_id'],
                'pk_contract_return_items'
            );

            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */

            // العلاقة مع جدول العقود
            $table->foreign('contract_id')
                ->references('contract_id')
                ->on('contracts')
                ->onDelete('cascade');

            // العلاقة مع جدول الأغراض
            $table->foreign('item_id')
                ->references('item_id')
                ->on('handover_items')
                ->onDelete('cascade');

            // وصف الجدول
            $table->comment(
                'الأغراض المعادة - تسجيل الأجهزة والأثاث المعادة من المستأجر عند انتهاء العقد'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_return_items');
    }
};
