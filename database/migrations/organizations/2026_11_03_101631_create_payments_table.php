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

            // المفتاح الأساسي
            $table->increments('payment_id')
                ->comment('معرف الدفعة');

            // العلاقات الأساسية
            $table->unsignedInteger('contract_id')
                ->comment('معرف العقد');

            $table->unsignedInteger('invoice_id')
                ->nullable()
                ->comment('معرف الفاتورة (التي تسدد هذه الدفعة)');

            // رقم الدفعة الفريد
            $table->string('payment_number', 50)
                ->comment('رقم الدفعة الفريد');

            // بيانات الدفع
            $table->date('payment_date')
                ->comment('تاريخ الدفع');

            $table->decimal('amount', 12, 2)
                ->comment('المبلغ المدفوع');

            // طريقة الدفع
            $table->enum('method', [
                'cash',
                'bank',
                'online',
                'cheque',
            ])
                ->default('cash')
                ->comment('طريقة الدفع');

            // بيانات الشيك
            $table->string('cheque_number', 50)
                ->nullable()
                ->comment('رقم الشيك (إن وجد)');

            $table->string('cheque_bank', 100)
                ->nullable()
                ->comment('بنك الشيك');

            $table->date('cheque_date')
                ->nullable()
                ->comment('تاريخ الشيك');

            // بيانات التحويل البنكي
            $table->string('bank_name', 100)
                ->nullable()
                ->comment('اسم البنك (للتحويل)');

            $table->string('transaction_id', 255)
                ->nullable()
                ->comment('معرف المعاملة البنكية');

            // إيصال الدفع
            $table->string('receipt_path', 500)
                ->nullable()
                ->comment('مسار إيصال الدفع');

            // الملاحظات
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات');

            // الموظف الذي استلم الدفعة
            $table->unsignedInteger('received_by')
                ->nullable()
                ->comment('من قام باستلام الدفعة');

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

            $table->index('contract_id', 'idx_contract');

            $table->index('invoice_id', 'idx_invoice');

            $table->index('payment_date', 'idx_payment_date');

            // رقم الدفعة فريد
            $table->unique('payment_number', 'idx_payment_number');


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

            // العلاقة مع جدول الفواتير
            $table->foreign('invoice_id')
                ->references('invoice_id')
                ->on('invoices')
                ->onDelete('set null');

            // العلاقة مع جدول المستخدمين
            $table->foreign('received_by')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');


            // وصف الجدول
            $table->comment(
                'الدفعات - تسجيل جميع المدفوعات المستلمة من المستأجرين وربطها بالفواتير'
            );
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
