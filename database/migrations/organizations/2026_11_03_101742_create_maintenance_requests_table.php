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
        Schema::create('maintenance_requests', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('request_id')
                ->comment('معرف الطلب');

            // العلاقات الأساسية
            $table->unsignedInteger('organization_id')
                ->comment('معرف المؤسسة');

            $table->unsignedInteger('unit_id')
                ->comment('معرف الوحدة');

            $table->unsignedInteger('tenant_id')
                ->comment('معرف المستأجر مقدم الطلب');

            // رقم الطلب الفريد
            $table->string('request_number', 50)
                ->comment('رقم الطلب الفريد');

            // تاريخ تقديم الطلب
            $table->dateTime('request_date')
                ->useCurrent()
                ->comment('تاريخ تقديم الطلب');

            // أولوية الطلب
            $table->enum('priority', [
                'low',
                'medium',
                'high',
                'emergency',
            ])
                ->default('medium')
                ->comment('أولوية الطلب');

            // نوع المشكلة
            $table->string('issue_type', 100)
                ->nullable()
                ->comment('نوع المشكلة (كهرباء، سباكة، تكييف، إلخ)');

            // وصف المشكلة
            $table->text('description')
                ->comment('وصف المشكلة');

            // صور المشكلة
            $table->json('images')
                ->nullable()
                ->comment('صور المشكلة');

            // حالة الطلب
            $table->enum('status', [
                'pending',
                'assigned',
                'in_progress',
                'completed',
                'cancelled',
                'rejected',
            ])
                ->default('pending')
                ->comment('حالة الطلب');

            // الفني أو الموظف المكلف
            $table->unsignedInteger('assigned_to')
                ->nullable()
                ->comment('تم التعيين إلى (فني/موظف صيانة)');

            // التكاليف
            $table->decimal('estimated_cost', 12, 2)
                ->nullable()
                ->comment('التكلفة التقديرية');

            $table->decimal('actual_cost', 12, 2)
                ->nullable()
                ->comment('التكلفة الفعلية');

            // تاريخ إكمال الصيانة
            $table->dateTime('completed_date')
                ->nullable()
                ->comment('تاريخ الإكمال');

            // تفاصيل الحل
            $table->text('resolution_notes')
                ->nullable()
                ->comment('ملاحظات الحل');

            // الفاتورة المرتبطة بالصيانة
            $table->unsignedInteger('invoice_id')
                ->nullable()
                ->comment('معرف الفاتورة (إن تمت فوترة الصيانة)');

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

            $table->index('organization_id', 'idx_agency');

            $table->index('unit_id', 'idx_unit');

            $table->index('status', 'idx_status');

            $table->index('priority', 'idx_priority');

            // رقم الطلب فريد
            $table->unique('request_number', 'idx_request_number');


            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */

            // المؤسسة المسؤولة
            $table->foreign('organization_id')
                ->references('organization_id')
                ->on('organizations')
                ->onDelete('cascade');

            // الوحدة العقارية
            $table->foreign('unit_id')
                ->references('unit_id')
                ->on('units')
                ->onDelete('cascade');

            // المستأجر مقدم الطلب
            $table->foreign('tenant_id')
                ->references('tenant_id')
                ->on('tenants')
                ->onDelete('cascade');

            // الموظف أو الفني المكلف
            $table->foreign('assigned_to')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');

            // فاتورة الصيانة
            $table->foreign('invoice_id')
                ->references('invoice_id')
                ->on('invoices')
                ->onDelete('set null');


            // وصف الجدول
            $table->comment(
                'طلبات الصيانة - إدارة طلبات الصيانة المقدمة من المستأجرين وتتبع إنجازها'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
