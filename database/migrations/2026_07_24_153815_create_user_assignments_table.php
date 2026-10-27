<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_assignments', function (Blueprint $table) {
            // المفتاح الأساسي
            $table->id();
            // المستخدم
            $table->unsignedBigInteger('user_id')
                ->comment('معرف المستخدم');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            // سياق الوصول
            $table->unsignedBigInteger('access_context_id')
                ->comment('معرف سياق الوصول');
            $table->foreign('access_context_id')
                ->references('id')
                ->on('access_contexts')
                ->cascadeOnDelete();
            // حالة التعيين
            // ACTIVE / INACTIVE / EXPIRED
            $table->string('status', 20)
                ->default('ACTIVE')
                ->comment('حالة التعيين');
            // بداية التعيين
            $table->date('start_date')
                ->nullable()
                ->comment('تاريخ بداية التعيين');
            // نهاية التعيين
            $table->date('end_date')
                ->nullable()
                ->comment('تاريخ نهاية التعيين');
            // // هل هو التعيين الأساسي؟
            // $table->boolean('is_primary')
            //     ->default(false)
            //     ->comment('التعيين الأساسي للمستخدم');
            // من أنشأ التعيين
            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->comment('معرف منشئ التعيين');
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            // آخر من عدل
            $table->unsignedBigInteger('updated_by')
                ->nullable()
                ->comment('معرف آخر تعديل');
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            // منع تكرار نفس المستخدم في نفس السياق
            $table->unique([
                'user_id',
                'access_context_id'
            ]);
            // البحث السريع
            $table->index('user_id');
            $table->index('access_context_id');
            $table->index('status');
            // $table->index('is_primary');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('user_assignments');
    }
};