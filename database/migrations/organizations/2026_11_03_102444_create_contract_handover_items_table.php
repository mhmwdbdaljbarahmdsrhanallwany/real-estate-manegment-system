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
        Schema::create('contract_handover_items', function (Blueprint $table) {

            // ربط العقد بالغرض
            $table->unsignedInteger('contract_id')
                ->comment('معرف العقد');

            $table->unsignedInteger('item_id')
                ->comment('معرف الغرض');

            // كمية الأغراض المسلّمة
            $table->integer('quantity')
                ->default(1)
                ->comment('الكمية');

            // حالة الغرض عند التسليم
            $table->enum('condition_at_delivery', [
                'new',
                'good',
                'fair',
                'poor',
                'damaged',
            ])
                ->default('good')
                ->comment('الحالة عند التسليم');

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
                'pk_contract_handover_items'
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

            // العلاقة مع جدول أنواع الأغراض
            $table->foreign('item_id')
                ->references('item_id')
                ->on('handover_items')
                ->onDelete('cascade');

            // وصف الجدول
            $table->comment(
                'الأغراض المسلمة - تسجيل الأجهزة والأثاث المسلمة للمستأجر عند بدء العقد'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_handover_items');
    }
};
