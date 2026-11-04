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
        Schema::create('tenant_deposits', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('deposit_id')
                ->comment('معرف التأمين');

            // ربط التأمين بالعقد
            $table->unsignedInteger('contract_id')
                ->comment('معرف العقد');

            // بيانات الإيجار والتأمين
            $table->decimal('monthly_rent', 12, 2)
                ->nullable()
                ->comment('قيمة الإيجار الشهرية (نسخة محفوظة)');

            $table->integer('deposit_months')
                ->nullable()
                ->comment('عدد أشهر التأمين');

            $table->decimal('deposit_amount', 12, 2)
                ->comment('مبلغ التأمين الإجمالي');

            $table->decimal('paid_amount', 12, 2)
                ->default(0)
                ->comment('المبلغ المدفوع');

            // المبلغ المتبقي: محسوب تلقائياً
            $table->decimal('remaining_amount', 12, 2)
                ->storedAs('deposit_amount - paid_amount')
                ->comment('المبلغ المتبقي');

            // حالة التأمين
            $table->enum('status', [
                'unpaid',
                'partial',
                'paid',
                'refunded',
                'deducted',
            ])
                ->default('unpaid')
                ->comment('حالة التأمين');

            // بيانات الدفع
            $table->date('paid_date')
                ->nullable()
                ->comment('تاريخ الدفع');

            $table->enum('payment_method', [
                'cash',
                'bank',
                'cheque',
            ])
                ->nullable()
                ->comment('طريقة الدفع');

            $table->string('cheque_number', 50)
                ->nullable()
                ->comment('رقم الشيك (إن وجد)');

            // بيانات استرداد التأمين
            $table->date('refund_date')
                ->nullable()
                ->comment('تاريخ الاسترداد');

            $table->decimal('refund_amount', 12, 2)
                ->default(0)
                ->comment('المبلغ المسترد');

            // المبالغ المستقطعة
            $table->decimal('deduction_amount', 12, 2)
                ->default(0)
                ->comment('المبلغ المستقطع (لأضرار)');

            $table->text('deduction_reason')
                ->nullable()
                ->comment('سبب الاستقطاع');

            // ملاحظات
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات');

            // تواريخ الإنشاء والتحديث
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            $table->dateTime('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate()
                ->comment('تاريخ آخر تحديث');

            // الفهارس
            $table->index('contract_id', 'idx_contract');
            $table->index('status', 'idx_status');

            // العلاقة مع جدول العقود
            $table->foreign('contract_id')
                ->references('contract_id')
                ->on('contracts')
                ->onDelete('cascade');

            // وصف الجدول
            $table->comment(
                'التأمينات - تسجيل مبالغ التأمين المدفوعة والمستردة للمستأجرين'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_deposits');
    }
};
