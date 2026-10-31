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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id')
                ->comment('المعرف الأساسي للدفع');

            $table->unsignedBigInteger('invoice_id'); // معرّف المؤسسة (FK → Agencies)
            $table->unsignedBigInteger('subscription_id'); 
        
            $table->foreign('invoice_id')->references('invoice_id')->on('invoices')
                ->cascadeOnDelete()
                ->comment('الفاتورة المرتبطة بهذا الدفع');
        
                $table->foreign('subscription_id')
                ->references('subscription_id')
                ->on('subscriptions')
                ->cascadeOnDelete()
                ->comment('الاشتراك المرتبط بالدفع (اختياري عند الدفع المباشر)');
        
            $table->decimal('amount', 12, 2)
                ->comment('قيمة المبلغ المدفوع');
        
            $table->enum('payment_method', ['cash', 'bank', 'card', 'online'])
                ->comment('طريقة الدفع: نقدي، بنك، بطاقة، إلكتروني');

            $table->string('transaction_id')->nullable()->comment('رقم العملية البنكية أو بوابة الدفع');
            $table->json('metadata')->nullable()->comment('بيانات إضافية من بوابة الدفع');
        
            $table->string('reference_number')->nullable()
                ->comment('رقم مرجعي لعملية الدفع (تحويل بنكي / عملية خارجية)');
        
            $table->timestamp('paid_at')
                ->comment('تاريخ ووقت تنفيذ عملية الدفع');
        
            $table->enum('status', ['success', 'failed', 'pending'])
                ->default('success')
                ->comment('حالة الدفع: ناجح، فاشل، قيد الانتظار');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
