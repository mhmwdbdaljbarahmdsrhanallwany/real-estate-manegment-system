<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {

            $table->id('package_id'); 
            // ملاحظة: الرقم التعريفي الأساسي للباقة (Primary Key)

            $table->string('name');
            // ملاحظة: اسم الباقة الظاهر للمستخدم (مثل Basic / Pro)

            $table->string('code')->unique();
            // ملاحظة: رمز فريد للباقة مثل BASIC أو PRO

            $table->unsignedTinyInteger('level');
            // ملاحظة: مستوى الباقة (1 = أساسية، 2 = متوسطة، 3 = متقدمة)

            $table->integer('sort_order')->default(0);
            // ملاحظة: ترتيب ظهور الباقات في واجهة المستخدم

            $table->text('description')->nullable();
            // ملاحظة: وصف تفصيلي لمميزات الباقة

            $table->decimal('base_price', 10, 2);
            // ملاحظة: السعر الأساسي قبل الخصم والضريبة

            $table->enum('discount_type', ['fixed', 'percent'])->nullable();
            // ملاحظة: نوع الخصم (مبلغ ثابت أو نسبة مئوية)

            $table->decimal('discount_value', 10, 2)->default(0);
            // ملاحظة: قيمة الخصم حسب النوع المحدد

            $table->enum('tax_type', ['fixed', 'percent'])->nullable();
            // ملاحظة: نوع الضريبة (مبلغ ثابت أو نسبة مئوية)

            $table->decimal('tax_value', 10, 2)->default(0);
            // ملاحظة: قيمة الضريبة المضافة على الباقة

            $table->enum('billing_cycle', ['monthly', 'yearly', 'weekly']);
            // ملاحظة: دورة الدفع (شهري، سنوي، أسبوعي)

            $table->integer('duration_days');
            // ملاحظة: مدة الاشتراك بالأيام

            $table->integer('reminder_before_days')->default(0);
            // ملاحظة: عدد الأيام قبل انتهاء الاشتراك لإرسال تنبيه

            $table->integer('reminder_after_expiry_days')->default(0);
            // ملاحظة: عدد الأيام بعد انتهاء الاشتراك لإرسال تنبيه

            $table->integer('grace_period_days')->default(0);
            // ملاحظة: فترة السماح بعد انتهاء الاشتراك قبل الإيقاف

            $table->enum('penalty_type', ['fixed', 'percent'])->nullable();
            // ملاحظة: نوع الغرامة عند التأخير

            $table->decimal('penalty_value', 10, 2)->default(0);
            // ملاحظة: قيمة الغرامة

            $table->enum('penalty_repeat', ['once', 'daily', 'weekly', 'monthly'])->nullable();
            // ملاحظة: تكرار تطبيق الغرامة

            $table->decimal('max_penalty', 10, 2)->default(0);
            // ملاحظة: الحد الأقصى للغرامة

            $table->integer('suspend_after_days')->default(0);
            // ملاحظة: عدد الأيام قبل إيقاف الحساب بعد انتهاء الاشتراك

            $table->boolean('allow_login_after_expiry')->default(false);
            // ملاحظة: السماح بتسجيل الدخول بعد انتهاء الاشتراك

            $table->boolean('allow_readonly_mode')->default(false);
            // ملاحظة: تفعيل وضع القراءة فقط بعد انتهاء الاشتراك

            $table->integer('max_users')->default(0);
            // ملاحظة: الحد الأقصى لعدد المستخدمين

            $table->integer('max_branches')->default(0);
            // ملاحظة: الحد الأقصى للفروع

            $table->integer('max_properties')->default(0);
            // ملاحظة: الحد الأقصى للعقارات

            $table->integer('max_units')->default(0);
            // ملاحظة: الحد الأقصى للوحدات

            $table->integer('storage_limit_mb')->default(0);
            // ملاحظة: مساحة التخزين المسموحة بالميجابايت

            $table->integer('max_api_requests')->default(0);
            // ملاحظة: الحد الأقصى لطلبات API

            $table->boolean('is_featured')->default(false);
            // ملاحظة: تحديد إذا كانت الباقة مميزة في العرض

            $table->boolean('is_active')->default(true);
            // ملاحظة: حالة تفعيل الباقة (نشطة أو غير نشطة)

            $table->text('notes')->nullable();
            // ملاحظة: ملاحظات إضافية حول الباقة
                        // الفهارس الذكية
            $table->index('level');
            // ملاحظة: لتحسين عمليات الترقية والمقارنة بين الباقات

            $table->index('is_active');
            // ملاحظة: لتصفية الباقات النشطة فقط

            $table->index('is_featured');
            // ملاحظة: لتسريع عرض الباقات المميزة في الواجهة

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};