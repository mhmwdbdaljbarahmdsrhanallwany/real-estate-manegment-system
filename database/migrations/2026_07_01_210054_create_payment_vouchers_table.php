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
        Schema::create('payment_vouchers', function (Blueprint $table) {
            $table->id('payment_voucher_id')
                ->comment('المعرف الأساسي لسند الصرف');

            $table->unsignedBigInteger('cashboxe_id'); 
            $table->unsignedBigInteger('organization_id'); 
        
            $table->foreign('cashboxe_id')->references('cashboxe_id')->on('cashboxes')
                ->cascadeOnDelete()
                ->comment('الصندوق الذي تم منه صرف المبلغ');
        
            $table->foreign('organization_id')->references('organization_id')->on('organizations')
                ->nullable()
                ->cascadeOnDelete()
                ->comment('المؤسسة المرتبطة بالصرف (إن وجد)');
        
            $table->decimal('amount', 12, 2)
                ->comment('قيمة المبلغ المصروف');
        
            $table->enum('destination_type', ['refund', 'expense', 'adjustment'])
                ->comment('نوع وجهة الصرف: استرجاع، مصروف، تعديل');
        
            $table->unsignedBigInteger('destination_id')->nullable()
                ->comment('مرجع العملية المرتبطة (مصروف / مورد / عملية خارجية)');
        
            $table->timestamp('paid_at')
                ->comment('تاريخ ووقت تنفيذ عملية الصرف');
        
            $table->text('description')->nullable()
                ->comment('ملاحظات إضافية حول عملية الصرف');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_vouchers');
    }
};
