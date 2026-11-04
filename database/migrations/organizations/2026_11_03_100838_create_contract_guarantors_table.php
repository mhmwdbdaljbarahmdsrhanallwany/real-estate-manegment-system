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
        Schema::create('contract_guarantors', function (Blueprint $table) {

            // ربط العقد بالضامن
            $table->unsignedInteger('contract_id')
                ->comment('معرف العقد');

            $table->unsignedInteger('guarantor_id')
                ->comment('معرف الضامن');

            // العلاقة بين المستأجر والضامن
            $table->string('relationship', 50)
                ->nullable()
                ->comment('العلاقة بين المستأجر والضامن');

            // نسبة المسؤولية المالية
            $table->decimal('responsibility_percentage', 5, 2)
                ->default(100)
                ->comment('نسبة المسؤولية المالية (%)');

            // ملاحظات
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات');

            // تاريخ الإنشاء
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            // المفتاح الأساسي المركب
            $table->primary(
                ['contract_id', 'guarantor_id'],
                'pk_contract_guarantors'
            );

            // العلاقة مع جدول العقود
            $table->foreign('contract_id')
                ->references('contract_id')
                ->on('contracts')
                ->onDelete('cascade');

            // العلاقة مع جدول الضامنين
            $table->foreign('guarantor_id')
                ->references('guarantor_id')
                ->on('guarantors')
                ->onDelete('cascade');

            // وصف الجدول
            $table->comment(
                'ربط الضامنين بالعقود - تحديد الضامنين المسؤولين عن كل عقد'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_guarantors');
    }
};
