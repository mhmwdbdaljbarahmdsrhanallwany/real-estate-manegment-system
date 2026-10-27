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
        Schema::create('subscriptions', function (Blueprint $table) {

            // المعرف الفريد للاشتراك
            $table->id('subscription_id')->comment('المعرف الفريد للاشتراك');

            // المؤسسة المالكة للاشتراك
            $table->unsignedBigInteger('organization_id')
            ->comment('معرف المؤسسة');
        
        $table->foreign('organization_id')
            ->references('organization_id')
            ->on('organizations')
            ->cascadeOnDelete();

            // الباقة المشتركة
            $table->unsignedBigInteger('package_id')
            ->comment('معرف الباقة');
        
        $table->foreign('package_id')
            ->references('package_id')
            ->on('packages')
            ->restrictOnDelete();
            
            // مدة الفترة التجريبية بالأيام
            $table->unsignedInteger('trial_period_days')
                ->default(0)
                ->comment('مدة الفترة التجريبية بالأيام');

            // تاريخ انتهاء الفترة التجريبية
            $table->date('trial_end_date')
                ->nullable()
                ->comment('تاريخ انتهاء الفترة التجريبية');

            // تاريخ بداية الاشتراك
            $table->date('start_date')
                ->comment('تاريخ بداية الاشتراك');

            // تاريخ انتهاء الاشتراك
            $table->date('end_date')
                ->comment('تاريخ انتهاء الاشتراك');

            // حالة الاشتراك
            $table->enum('status', [
                'pending',
                'active',
                'expired',
                'cancelled',
            ])
                ->default('pending')
                ->comment('حالة الاشتراك');
            // نوع الاشتراك
            $table->enum('subscription_type', [
                'trial',
                'paid',
            ])->default('trial')
                ->comment('نوع الاشتراك');

            // تاريخ تفعيل الاشتراك
            $table->timestamp('activated_at')
                ->nullable()
                ->comment('تاريخ تفعيل الاشتراك');

            // هل يتم تجديد الاشتراك تلقائياً
            $table->boolean('auto_renew')
                ->default(false)
                ->comment('تجديد الاشتراك تلقائياً');

            // ملاحظات
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات');

            // تاريخ الإنشاء والتعديل
            $table->timestamps();

            // الحذف المنطقي
            $table->softDeletes();

            // الفهارس
            $table->index('organization_id');
            $table->index('package_id');
            $table->index('status');
            $table->index('start_date');
            $table->index('end_date');
            $table->index('deleted_at');

            // فهرس مركب لتسريع البحث عن الاشتراكات الحالية للمؤسسة
            $table->index(['organization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
