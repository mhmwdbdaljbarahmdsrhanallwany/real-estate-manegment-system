<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authentication_methods', function (Blueprint $table) {
            // المفتاح الأساسي
            $table->id();
            // المستخدم المرتبط بوسيلة المصادقة
            $table->unsignedBigInteger('user_id')
                ->comment('معرف المستخدم');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            // نوع المصادقة
            // PASSWORD / OAUTH / LDAP / SSO
            $table->string('type', 50)
                ->comment('نوع وسيلة المصادقة');
            // مزود المصادقة
            // LOCAL / GOOGLE / MICROSOFT / AZURE
            $table->string('provider', 50)
                ->comment('مزود المصادقة');
            // المعرف لدى المزود
            // email أو provider id
            $table->string('identifier', 255)
                ->comment('المعرف المستخدم للمصادقة');
            // بيانات إضافية مشفرة عند الحاجة
            $table->text('secret')
                ->nullable()
                ->comment('بيانات المصادقة المشفرة');
            // حالة وسيلة المصادقة
            // ACTIVE / DISABLED
            $table->string('status', 20)
                ->default('ACTIVE')
                ->comment('حالة وسيلة المصادقة');
            // آخر استخدام
            $table->timestamp('last_used_at')
                ->nullable()
                ->comment('آخر استخدام');
            $table->timestamps();
            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            // منع تكرار نفس معرف المصادقة لنفس المزود
            $table->unique([
                'provider',
                'identifier'
            ]);
            // البحث عن وسائل المستخدم
            $table->index('user_id');
            // تصفية الوسائل الفعالة
            $table->index('status');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('authentication_methods');
    }
};