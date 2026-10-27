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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id')
                ->comment('المعرف الأساسي للحركة المالية (Ledger Entry)');
                
            $table->unsignedBigInteger('cashboxe_id'); 

            $table->foreign('cashboxe_id')->references('cashboxe_id')->on('cashboxes')
                ->cascadeOnDelete()
                ->comment('الصندوق المالي المرتبط بهذه الحركة');
        
            $table->enum('type', ['in', 'out'])
                ->comment('نوع الحركة: دخول أموال أو خروج أموال');
        
            $table->decimal('amount', 12, 2)
                ->comment('قيمة المبلغ في هذه الحركة');
        
            $table->string('reference_type')
                ->comment('نوع المصدر (Invoice, PaymentVoucher, ReceiptVoucher, etc)');
        
            $table->unsignedBigInteger('reference_id')
                ->comment('رقم السجل المرتبط بالمصدر');
        
            $table->decimal('balance_after', 12, 2)
                ->comment('رصيد الصندوق بعد تنفيذ هذه الحركة');

            $table->string('currency', 10)->default('USD')
                ->comment('عملة العملية المالية');
            
            $table->foreignId('user_id')->nullable()
                ->comment('المستخدم الذي أنشأ الحركة');
        
            $table->text('notes')->nullable()
                ->comment('ملاحظات إضافية حول الحركة المالية');
        
            $table->timestamps();
        
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
