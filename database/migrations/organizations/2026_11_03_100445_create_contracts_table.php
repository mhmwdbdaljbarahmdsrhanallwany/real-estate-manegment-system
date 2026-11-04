<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{ /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('contract_id')
                ->comment('معرف العقد');

            // رقم العقد الفريد
            $table->string('contract_number', 50)
                ->comment('رقم العقد الفريد');

            // المستأجر والوحدة
            $table->unsignedInteger('tenant_id')
                ->comment('معرف المستأجر');

            $table->unsignedInteger('unit_id')
                ->comment('معرف الوحدة');

            // المؤسسة والفرع المسؤولان
            $table->unsignedInteger('organization_id')
                ->nullable()
                ->comment('معرف المؤسسة المسؤولة');

            $table->unsignedInteger('branch_id')
                ->nullable()
                ->comment('معرف الفرع المسؤول');

            // المستخدمون المسؤولون
            $table->unsignedInteger('created_by')
                ->nullable()
                ->comment('من قام بإنشاء العقد');

            // مدة العقد
            $table->date('start_date')
                ->comment('تاريخ بدء العقد');

            $table->date('end_date')
                ->comment('تاريخ انتهاء العقد');

            $table->date('actual_end_date')
                ->nullable()
                ->comment('تاريخ الانتهاء الفعلي (إن انتهى مبكراً)');

            // المبالغ المالية
            $table->decimal('rent_amount', 12, 2)
                ->comment('قيمة الإيجار الشهرية');

            $table->integer('payment_day')
                ->default(1)
                ->comment('يوم الدفع من كل شهر (1-31)');

            // تكرار الدفع
            $table->enum('payment_frequency', [
                'monthly',
                'quarterly',
                'half_yearly',
                'yearly',
            ])
                ->default('monthly')
                ->comment('تكرار الدفع');

            // فترة السماح وغرامات التأخير
            $table->integer('grace_period_days')
                ->default(5)
                ->comment('فترة السماح (أيام)');

            $table->decimal('late_fee_per_day', 12, 2)
                ->default(0)
                ->comment('غرامة التأخير لكل يوم');

            $table->decimal('late_fee_fixed', 12, 2)
                ->default(0)
                ->comment('غرامة تأخير ثابتة');

            // مبلغ التأمين
            $table->decimal('deposit_amount', 12, 2)
                ->default(0)
                ->comment('مبلغ التأمين');

            $table->boolean('deposit_paid')
                ->default(false)
                ->comment('هل تم دفع التأمين؟');

            // ملفات العقد
            $table->string('contract_image', 500)
                ->nullable()
                ->comment('مسار صورة العقد');

            $table->string('contract_pdf', 500)
                ->nullable()
                ->comment('مسار ملف PDF للعقد');

            // حالة العقد
            $table->enum('status', [
                'draft',
                'active',
                'terminated',
                'expired',
                'renewed',
                'cancelled',
            ])
                ->default('draft')
                ->comment('حالة العقد');

            // بيانات إنهاء العقد
            $table->text('termination_reason')
                ->nullable()
                ->comment('سبب الإنهاء (إن وجد)');

            $table->dateTime('terminated_at')
                ->nullable()
                ->comment('تاريخ الإنهاء');

            $table->unsignedInteger('terminated_by')
                ->nullable()
                ->comment('من قام بالإنهاء');

            // بيانات التجديد
            $table->unsignedInteger('renewed_from')
                ->nullable()
                ->comment('معرف العقد الذي تم التجديد منه');

            $table->integer('renewal_count')
                ->default(0)
                ->comment('عدد مرات التجديد');

            // الملاحظات والشروط
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات إضافية');

            $table->text('terms_and_conditions')
                ->nullable()
                ->comment('الشروط والأحكام');

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

            $table->index('tenant_id', 'idx_tenant');

            $table->index('unit_id', 'idx_unit');

            $table->index('status', 'idx_status');

            $table->index(
                ['start_date', 'end_date'],
                'idx_dates'
            );

            $table->index('deleted_at', 'idx_deleted_at');

            // رقم العقد فريد، وهذا الفهرس يحقق أيضاً وظيفة UNIQUE
            $table->unique('contract_number', 'idx_contract_number');


            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */

            // المستأجر
            $table->foreign('tenant_id')
                ->references('tenant_id')
                ->on('tenants')
                ->onDelete('cascade');

            // الوحدة العقارية
            $table->foreign('unit_id')
                ->references('unit_id')
                ->on('units')
                ->onDelete('cascade');

            // المؤسسة
            $table->foreign('organization_id')
                ->references('organization_id')
                ->on('organizations')
                ->onDelete('set null');

            // الفرع
            $table->foreign('branch_id')
                ->references('branch_id')
                ->on('agency_branches')
                ->onDelete('set null');

            // المستخدم الذي أنشأ العقد
            $table->foreign('created_by')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');

            // المستخدم الذي أنهى العقد
            $table->foreign('terminated_by')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');

            // العقد السابق الذي تم التجديد منه
            $table->foreign('renewed_from')
                ->references('contract_id')
                ->on('contracts')
                ->onDelete('set null');


            // وصف الجدول
            $table->comment(
                'عقود الإيجار - تسجيل جميع عقود الإيجار مع تفاصيل المدة والمبالغ والحالات'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
