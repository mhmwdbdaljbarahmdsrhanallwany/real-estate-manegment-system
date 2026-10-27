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
        Schema::create('receipt_vouchers', function (Blueprint $table) {
            $table->id('receipt_voucher_id')
                ->comment('المعرف الأساسي لسند القبض');
                $table->unsignedBigInteger('cashboxe_id'); // معرّف المؤسسة (FK → Agencies)
                $table->unsignedBigInteger('invoice_id'); 
                $table->unsignedBigInteger('organization_id'); 

            $table->foreign('cashboxe_id')->references('cashboxe_id')->on('cashboxes')
                ->constrained()
                ->cascadeOnDelete()
                ->comment('الصندوق المالي الذي استلم المبلغ');
        
            $table->foreign('invoice_id')->references('invoice_id')->on('invoices')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->comment('الفاتورة المرتبطة بالسند (إن وجد)');
        
            $table->foreign('organization_id')->references('organization_id')->on('organizations')
                ->constrained()
                ->cascadeOnDelete()
                ->comment('المؤسسة التي تم استلام المبلغ منها');
        
            $table->decimal('amount', 12, 2)
                ->comment('قيمة المبلغ المستلم');
        
            $table->enum('source_type', ['invoice', 'manual', 'adjustment'])
                ->comment('مصدر السند: فاتورة، يدوي، أو تعديل');
        
            $table->unsignedBigInteger('source_id')->nullable()
                ->comment('رقم المصدر المرتبط (مثل رقم الفاتورة أو مرجع خارجي)');
        
            $table->timestamp('received_at')
                ->comment('تاريخ ووقت استلام المبلغ فعلياً');
        
            $table->text('description')->nullable()
                ->comment('ملاحظات إضافية حول عملية الاستلام');
        
            $table->timestamps();
        
            $table->index(['cashboxe_id', 'organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_vouchers');
    }
};
