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
        Schema::create('agency_subscriptions', function (Blueprint $table) {
            $table->id('AgencySubscriptionID'); // المعرّف الأساسي للاشتراك الوسيط

            // العلاقات الأساسية
            $table->unsignedBigInteger('AgencyID'); // معرّف المؤسسة (FK → Agencies)
            $table->unsignedBigInteger('PlanID');   // معرّف الباقة (FK → SubscriptionPlans)

            // تفاصيل الاشتراك
            $table->string('SubscriptionType')->nullable(); // نوع الاشتراك (شهري، سنوي، ربع سنوي...)
            $table->date('StartDate');                      // تاريخ بداية الاشتراك
            $table->date('EndDate')->nullable();            // تاريخ انتهاء الاشتراك
            $table->date('RenewalDate')->nullable();        // تاريخ التجديد القادم
            $table->integer('TrialPeriod')->nullable();     // الفترة التجريبية بالأيام

            // حالة الباقة والتسعير
            $table->string('PlanStatus')->default('pending'); // حالة الباقة (نشطة، معلقة، منتهية...)
            $table->decimal('PlanPrice', 10, 2)->nullable();  // السعر الفعلي للباقة
            $table->decimal('TaxRate', 5, 2)->nullable();     // نسبة الضريبة المضافة

            // الغرامات والمتابعة
            $table->decimal('LateFee', 10, 2)->nullable();    // قيمة غرامة التأخير
            $table->boolean('LateFeePaid')->default(false);   // هل تم دفع الغرامة؟
            $table->date('LateFeePaymentDate')->nullable();   // تاريخ دفع الغرامة
            $table->integer('GracePeriod')->nullable();       // فترة السماح قبل تطبيق الغرامة

            // التحذيرات
            $table->boolean('WarningSent')->default(false);   // هل تم إرسال رسالة تحذير؟
            $table->integer('WarningCount')->default(0);      // عدد مرات إرسال التحذير
            $table->date('LastWarningDate')->nullable();      // آخر تاريخ لإرسال التحذير

            // الخصومات
            $table->decimal('DiscountRate', 5, 2)->nullable();   // نسبة الخصم
            $table->decimal('DiscountAmount', 10, 2)->nullable(); // قيمة الخصم
            $table->string('DiscountReason')->nullable();         // سبب الخصم

            // المبالغ
            $table->decimal('AmountPaid', 10, 2)->default(0);     // المبلغ المدفوع
            $table->decimal('AmountRemaining', 10, 2)->nullable(); // المبلغ المتبقي

            // ملاحظات إدارية
            $table->text('AdminNotes')->nullable(); // ملاحظات إدارية من المسؤولين

            $table->timestamps(); // created_at و updated_at

            // العلاقات الخارجية
            $table->foreign('AgencyID')->references('agency_id')->on('agency')->onDelete('cascade'); // ربط مع جدول الوكالات
            $table->foreign('PlanID')->references('plan_id')->on('subscription_plans')->onDelete('cascade'); // ربط مع جدول الباقات
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_subscriptions');
    }
};
