
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_requests', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('request_id')
                ->comment('معرف الطلب');

            // العلاقات الأساسية
            // organizations.organization_id = BIGINT UNSIGNED
            $table->unsignedBigInteger('organization_id')
                ->comment('معرف المؤسسة');

            // يفترض أن المفاتيح الأساسية في هذه الجداول INT UNSIGNED
            $table->unsignedInteger('unit_id')
                ->comment('معرف الوحدة');

            $table->unsignedInteger('tenant_id')
                ->comment('معرف المستأجر مقدم الطلب');

            // رقم الطلب
            $table->string('request_number', 50)
                ->unique()
                ->comment('رقم الطلب الفريد');

            // تاريخ تقديم الطلب
            $table->dateTime('request_date')
                ->useCurrent()
                ->comment('تاريخ تقديم الطلب');

            // الأولوية
            $table->enum('priority', [
                'low',
                'medium',
                'high',
                'emergency',
            ])->default('medium')
              ->comment('أولوية الطلب');

            // نوع المشكلة
            $table->string('issue_type', 100)
                ->nullable()
                ->comment('نوع المشكلة');

            // وصف المشكلة
            $table->text('description')
                ->comment('وصف المشكلة');

            // الصور
            $table->json('images')
                ->nullable()
                ->comment('صور المشكلة');

            // الحالة
            $table->enum('status', [
                'pending',
                'assigned',
                'in_progress',
                'completed',
                'cancelled',
                'rejected',
            ])->default('pending')
              ->comment('حالة الطلب');

            // الموظف أو الفني المكلف
            // users.id = BIGINT UNSIGNED
            $table->unsignedBigInteger('assigned_to')
                ->nullable()
                ->comment('معرف الموظف أو الفني المكلف');

            // التكاليف
            $table->decimal('estimated_cost', 12, 2)
                ->nullable()
                ->comment('التكلفة التقديرية');

            $table->decimal('actual_cost', 12, 2)
                ->nullable()
                ->comment('التكلفة الفعلية');

            // تاريخ الإكمال
            $table->dateTime('completed_date')
                ->nullable()
                ->comment('تاريخ الإكمال');

            // ملاحظات الحل
            $table->text('resolution_notes')
                ->nullable()
                ->comment('ملاحظات الحل');

            // الفاتورة
               $table->unsignedBigInteger('invoice_id')
    ->nullable()
    ->comment('معرف الفاتورة');

            // التواريخ
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
            $table->index('tenant_id', 'idx_tenant');
            $table->index('status', 'idx_status');
            $table->index('priority', 'idx_priority');
            $table->index('assigned_to', 'idx_assigned_to');

            /*
            |--------------------------------------------------------------------------
            | Foreign Keys
            |--------------------------------------------------------------------------
            */

            // المؤسسة
            $table->foreign('organization_id')
                ->references('organization_id')
                ->on('organizations')
                ->cascadeOnDelete();

            // الوحدة
            $table->foreign('unit_id')
                ->references('unit_id')
                ->on('units')
                ->cascadeOnDelete();

            // المستأجر
            $table->foreign('tenant_id')
                ->references('tenant_id')
                ->on('tenants')
                ->cascadeOnDelete();

            // الموظف أو الفني
            $table->foreign('assigned_to')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

         

            // وصف الجدول
            $table->comment(
                'طلبات الصيانة - إدارة طلبات الصيانة المقدمة من المستأجرين وتتبع إنجازها'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
