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
    {Schema::create('actions', function (Blueprint $table) {

        $table->id('action_id');
        // المعرف الأساسي للإجراء
    
        $table->unsignedBigInteger('action_type_id');
    
        $table->foreign('action_type_id')
            ->references('action_type_id')
            ->on('action_types')
            ->cascadeOnDelete();
        // نوع الإجراء المنفذ
    
    
        $table->unsignedBigInteger('organization_id')
            ->nullable();
    
        $table->foreign('organization_id')
            ->references('organization_id')
            ->on('organizations')
            ->nullOnDelete();
        // المؤسسة المرتبط بها الإجراء (إن وجدت)
    
    
        $table->unsignedBigInteger('user_id')
            ->nullable();
        // المستخدم الذي نفذ الإجراء
        // سيتم ربطه لاحقاً بعد إنشاء جدول المستخدمين
    
    
        $table->string('subject_type')->nullable();
        // اسم الكيان المرتبط بالإجراء
        // مثال: Subscription / Invoice / Property
    
    
        $table->unsignedBigInteger('subject_id')->nullable();
        // رقم الكيان المرتبط بالإجراء
    
    
        $table->string('title');
        // عنوان مختصر للإجراء
    
    
        $table->text('description')->nullable();
        // وصف تفصيلي للإجراء
    
    
        $table->json('old_values')->nullable();
        // البيانات قبل التعديل
    
    
        $table->json('new_values')->nullable();
        // البيانات بعد التعديل
    
    
        $table->json('metadata')->nullable();
        // بيانات إضافية يحتاجها النظام
    
    
        $table->ipAddress('ip_address')->nullable();
        // عنوان IP لمنفذ الإجراء
    
    
        $table->text('user_agent')->nullable();
        // معلومات المتصفح أو التطبيق
    
    
        $table->enum('status', [
            'pending',
            'success',
            'failed',
            'cancelled'
        ])->default('success');
        // حالة تنفيذ الإجراء
    
    
        $table->timestamp('performed_at')->useCurrent();
        // وقت تنفيذ الإجراء
    
    
        $table->timestamps();
    
    
    
        /*
        |--------------------------------------------------------------------------
        | الفهارس
        |--------------------------------------------------------------------------
        */
    
        $table->index('action_type_id');
        // البحث حسب نوع الإجراء
    
        $table->index('organization_id');
        // البحث حسب المؤسسة
    
        $table->index('user_id');
        // البحث حسب المستخدم
    
        $table->index(['subject_type', 'subject_id']);
        // البحث حسب الكيان المرتبط
    
        $table->index('status');
        // البحث حسب حالة التنفيذ
    
        $table->index('performed_at');
        // ترتيب الأحداث زمنياً
    
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
