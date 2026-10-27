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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');

            $table->unsignedBigInteger('subscription_id');

            $table->foreign('subscription_id')
            ->references('subscription_id')
            ->on('subscriptions')
            ->cascadeOnDelete()->comment('الاشتراك المرتبط بهذه الفاتورة');
        
            $table->string('invoice_number')->unique()
                ->comment('رقم الفاتورة الفريد');
    
            $table->decimal('total_amount', 12, 2)
                ->comment('إجمالي المبلغ قبل الخصم والضرائب');
    
            $table->decimal('tax_amount', 12, 2)->default(0)
                ->comment('قيمة الضريبة المضافة');
    
            $table->decimal('discount_amount', 12, 2)->default(0)
                ->comment('قيمة الخصم');
    
            $table->decimal('net_amount', 12, 2)
                ->comment('صافي المبلغ النهائي بعد الخصم والضرائب');
    
            $table->enum('status', ['pending', 'paid', 'partial', 'overdue'])
                ->default('pending')
                ->comment('حالة الفاتورة: معلقة، مدفوعة، جزئية، متأخرة');
    
            $table->date('issue_date')
                ->comment('تاريخ إصدار الفاتورة');
    
            $table->date('due_date')->nullable()
                ->comment('تاريخ استحقاق الدفع');
    
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
