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

            // المفتاح الأساسي
            $table->increments('invoice_id')
                ->comment('معرف الفاتورة');

            // العلاقات الأساسية
            $table->unsignedInteger('contract_id')
                ->nullable()
                ->comment('معرف العقد (للفواتير المرتبطة بعقد)');

            $table->unsignedInteger('tenant_id')
                ->nullable()
                ->comment('معرف المستأجر');

            $table->unsignedInteger('organization_id')
                ->nullable()
                ->comment('معرف المؤسسة');

            // رقم الفاتورة الفريد
            $table->string('invoice_number', 50)
                ->comment('رقم الفاتورة الفريد');

            // نوع الفاتورة
            $table->enum('invoice_type', [
                'rent',
                'deposit',
                'maintenance',
                'late_fee',
                'water',
                'electricity',
                'gas',
                'service',
                'penalty',
            ])
                ->comment('نوع الفاتورة');

            // فترة الفاتورة
            $table->date('period_start')
                ->nullable()
                ->comment('بداية فترة الفاتورة (لإيجار شهري)');

            $table->date('period_end')
                ->nullable()
                ->comment('نهاية فترة الفاتورة');

            // المبالغ المالية
            $table->decimal('amount', 12, 2)
                ->comment('المبلغ الأساسي');

            $table->decimal('tax_rate', 5, 2)
                ->default(15.00)
                ->comment('نسبة الضريبة (15% افتراضياً)');

            $table->decimal('tax_amount', 12, 2)
                ->default(0)
                ->comment('قيمة الضريبة');

            $table->decimal('discount_amount', 12, 2)
                ->default(0)
                ->comment('قيمة الخصم');

            $table->string('discount_reason', 255)
                ->nullable()
                ->comment('سبب الخصم');

            // الإجمالي النهائي: محسوب تلقائياً
            $table->decimal('total_amount', 12, 2)
                ->storedAs('amount + tax_amount - discount_amount')
                ->comment('الإجمالي النهائي');

            // تواريخ الفاتورة
            $table->date('issue_date')
                ->comment('تاريخ إصدار الفاتورة');

            $table->date('due_date')
                ->comment('تاريخ الاستحقاق');

            // حالة الفاتورة
            $table->enum('status', [
                'draft',
                'issued',
                'paid',
                'overdue',
                'cancelled',
                'partial',
            ])
                ->default('draft')
                ->comment('حالة الفاتورة');

            // طريقة الدفع
            $table->enum('payment_method', [
                'cash',
                'bank',
                'online',
                'cheque',
            ])
                ->nullable()
                ->comment('طريقة الدفع');

            $table->string('cheque_number', 50)
                ->nullable()
                ->comment('رقم الشيك (إن وجد)');

            $table->string('bank_name', 100)
                ->nullable()
                ->comment('اسم البنك');

            $table->string('transaction_id', 255)
                ->nullable()
                ->comment('معرف المعاملة البنكية');

            // رمز QR وملف الفاتورة
            $table->string('qr_code', 500)
                ->nullable()
                ->comment('رمز QR للدفع');

            $table->string('pdf_path', 500)
                ->nullable()
                ->comment('مسار ملف PDF للفاتورة');

            // ملاحظات
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات');

            // المستخدم الذي أنشأ الفاتورة
            $table->unsignedInteger('created_by')
                ->nullable()
                ->comment('من قام بإنشاء الفاتورة');

            // تواريخ الإنشاء والتحديث
            $table->dateTime('created_at')
                ->useCurrent()
                ->comment('تاريخ الإنشاء');

            $table->dateTime('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate()
                ->comment('تاريخ آخر تحديث');

            // الحذف المنطقي
            $table->dateTime('deleted_at')
                ->nullable()
                ->comment('تاريخ الحذف المنطقي');


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('contract_id', 'idx_contract');

            $table->index('tenant_id', 'idx_tenant');

            $table->index('status', 'idx_status');

            $table->index('due_date', 'idx_due_date');

            // رقم الفاتورة فريد، ويؤدي الفهرس الفريد وظيفة الفهرس العادي أيضاً
            $table->unique('invoice_number', 'idx_invoice_number');

            $table->index('deleted_at', 'idx_deleted_at');


            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */

            // العلاقة مع العقود
            $table->foreign('contract_id')
                ->references('contract_id')
                ->on('contracts')
                ->onDelete('set null');

            // العلاقة مع المستأجرين
            $table->foreign('tenant_id')
                ->references('tenant_id')
                ->on('tenants')
                ->onDelete('set null');

            // العلاقة مع المؤسسات
            $table->foreign('organization_id')
                ->references('organization_id')
                ->on('organizations')
                ->onDelete('set null');

            // العلاقة مع المستخدم الذي أنشأ الفاتورة
            $table->foreign('created_by')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');


            // وصف الجدول
            $table->comment(
                'الفواتير - إدارة جميع فواتير الإيجار والخدمات والغرامات والصيانة'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
