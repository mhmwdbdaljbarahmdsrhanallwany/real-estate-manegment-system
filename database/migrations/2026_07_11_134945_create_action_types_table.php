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
        Schema::create('action_types', function (Blueprint $table) {

            $table->id('action_type_id');
            // المعرف الأساسي لنوع الإجراء
        
            $table->string('name');
            // اسم الإجراء (إضافة اشتراك، حذف مؤسسة...)
        
            $table->string('code')->unique();
            // كود فريد يستخدم برمجياً مثل subscription_created
        
            $table->string('category')->nullable();
            // تصنيف الإجراء (اشتراكات، فواتير، مستخدمون...)
        
            $table->string('icon')->nullable();
            // أيقونة FontAwesome الخاصة بالإجراء
        
            $table->string('color')->default('primary');
            // لون عرض الإجراء داخل النظام
        
            $table->enum('level', [
                'info',
                'success',
                'warning',
                'danger'
            ])->default('info');
            // مستوى أهمية الإجراء
        
            $table->boolean('send_notification')->default(false);
            // هل يتم إرسال إشعار عند تنفيذ هذا الإجراء؟
        
            $table->boolean('write_audit_log')->default(true);
            // هل يتم تسجيل هذا الإجراء في سجل المراجعة؟
        
            $table->boolean('is_active')->default(true);
            // هل نوع الإجراء مفعل؟
        
            $table->text('description')->nullable();
            // وصف مختصر للإجراء
           
            $table->unsignedInteger('sort_order')->default(0);
            // ترتيب ظهور نوع الإجراء داخل لوحة الإدارة
        
            $table->timestamps();
        
        
        
            /*
            |--------------------------------------------------------------------------
            | الفهارس
            |--------------------------------------------------------------------------
            */
        
            $table->index('category');
            // البحث حسب التصنيف
        
            $table->index('level');
            // البحث حسب مستوى الأهمية
        
            $table->index('is_active');
            // عرض الأنواع المفعلة فقط
        
            $table->index('send_notification');
            // البحث عن الإجراءات التي ترسل إشعارات
            $table->index('sort_order');
            // ترتيب عرض أنواع الإجراءات
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_types');
    }
};
