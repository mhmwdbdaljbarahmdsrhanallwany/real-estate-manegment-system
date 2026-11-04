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
        Schema::create('handover_requests', function (Blueprint $table) {

            // المفتاح الأساسي
            $table->increments('request_id')
                ->comment('معرف الطلب');

            // العقد المرتبط بعملية التسليم أو الاستلام
            $table->unsignedInteger('contract_id')
                ->comment('معرف العقد');

            // تاريخ تقديم الطلب
            $table->date('request_date')
                ->comment('تاريخ الطلب');

            // نوع العملية
            $table->enum('handover_type', [
                'delivery',
                'return',
            ])
                ->default('delivery')
                ->comment('نوع الطلب: تسليم أو استلام');

            // تاريخ التنفيذ المقرر والفعلي
            $table->date('scheduled_date')
                ->nullable()
                ->comment('تاريخ التسليم/الاستلام المقرر');

            $table->date('actual_date')
                ->nullable()
                ->comment('التاريخ الفعلي');

            // حالة الطلب
            $table->enum('status', [
                'pending',
                'scheduled',
                'completed',
                'cancelled',
            ])
                ->default('pending')
                ->comment('حالة الطلب');

            // المستخدم الذي أكمل العملية
            $table->unsignedInteger('completed_by')
                ->nullable()
                ->comment('من قام بالإنجاز');

            // حالة الوحدة عند التسليم أو الاستلام
            $table->text('condition_notes')
                ->nullable()
                ->comment('ملاحظات عن حالة الوحدة');

            // صور الوحدة
            $table->json('images')
                ->nullable()
                ->comment('صور للوحدة عند التسليم/الاستلام');

            // ملاحظات إضافية
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات إضافية');

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

            $table->index('status', 'idx_status');

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

            // العلاقة مع المستخدم الذي نفّذ العملية
            $table->foreign('completed_by')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');

            // وصف الجدول
            $table->comment(
                'طلبات التسليم والاستلام - إدارة عمليات تسليم الوحدة للمستأجر واستلامها عند الإخلاء'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handover_requests');
    }
};
