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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('audit_log_id')
                ->comment('المعرف الأساسي لسجل التدقيق');

            $table->unsignedBigInteger('user_id'); 

        
            $table->foreign('user_id')->references('id')->on('users')
                ->nullable()
                ->cascadeOnDelete()
                ->comment('المستخدم الذي قام بالعملية (إن وجد)');
        
            $table->string('action')
                ->comment('نوع العملية: create, update, delete, login, approve');
        
            $table->string('model_type')
                ->comment('اسم الكلاس أو الكيان (مثال: App\\Models\\Invoice)');
        
            $table->unsignedBigInteger('model_id')
                ->comment('رقم السجل الذي تم التعديل عليه');
        
            $table->json('old_values')->nullable()
                ->comment('القيم القديمة قبل التعديل');
        
            $table->json('new_values')->nullable()
                ->comment('القيم الجديدة بعد التعديل');
        
            $table->string('ip_address')->nullable()
                ->comment('عنوان IP الخاص بالمستخدم');
        
            $table->text('user_agent')->nullable()
                ->comment('معلومات المتصفح أو الجهاز');
        
            $table->timestamps();
        
            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
