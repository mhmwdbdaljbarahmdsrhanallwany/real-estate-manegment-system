<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('access_contexts', function (Blueprint $table) {
            // المفتاح الأساسي
            $table->id();
            // اسم سياق الوصول
            $table->string('name', 150)
                ->comment('اسم سياق الوصول');
            // نوع السياق
            // SYSTEM / ORGANIZATION / BRANCH
            $table->string('context_type', 50)
                ->comment('نوع سياق الوصول');
            // الدومين الأساسي
            $table->string('domain', 255)
                ->comment('الدومين الأساسي');
            // النطاق الفرعي
            $table->string('subdomain', 100)
                ->nullable()
                ->comment('النطاق الفرعي');
            // المؤسسة المرتبطة (اختياري)
            $table->unsignedBigInteger('organization_id')
                ->nullable()
                ->comment('معرف المؤسسة');
            $table->foreign('organization_id')
                ->references('organization_id')
                ->on('organizations')
                ->nullOnDelete();
            // الفرع المرتبط (اختياري)
            // $table->unsignedBigInteger('branch_id')
            //     ->nullable()
            //     ->comment('معرف الفرع');
            // $table->foreign('branch_id')
            //     ->references('branch_id')
            //     ->on('branches')
            //     ->nullOnDelete();
            // حالة الرابط
            $table->string('status', 20)
                ->default('ACTIVE')
                ->comment('حالة سياق الوصول');
            // من أنشأ الرابط
            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->comment('معرف المستخدم المنشئ');
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            // آخر من عدل
            $table->unsignedBigInteger('updated_by')
                ->nullable()
                ->comment('معرف آخر مستخدم عدل');
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
            // البحث عن طريق الدومين
            $table->index('domain');
            // البحث عن طريق النوع
            $table->index('context_type');
            // البحث عن السياقات الفعالة
            $table->index('status');
            // منع تكرار نفس الرابط
            $table->unique([
                'domain',
                'subdomain'
            ]);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('access_contexts');
    }
};