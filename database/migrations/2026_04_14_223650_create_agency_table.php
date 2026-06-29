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
        Schema::create('agency', function (Blueprint $table) {
            $table->id('agency_id')->comment('المعرف الفريد للمؤسسة');
            $table->string('agency_name', 255)->comment('اسم المؤسسة العقارية');
            $table->string('commercial_registration', 50)->unique()->nullable()->comment('السجل التجاري - متطلب حكومي أساسي');
            $table->string('tax_number', 50)->unique()->nullable()->comment('الرقم الضريبي - لمخاطبة هيئة الزكاة والضريبة');
            $table->string('municipality_license', 50)->unique()->nullable()->comment('رخصة البلدية - تصريح مزاولة النشاط');
            $table->string('real_estate_broker_license', 50)->nullable()->comment('رخصة الوساطة العقارية من الهيئة العامة للعقار');
            $table->string('unified_national_number', 20)->nullable()->comment('الرقم الموحد - للمنشآت');
            $table->string('phone', 50)->nullable()->comment('رقم هاتف المؤسسة');
            $table->string('mobile', 50)->nullable()->comment('جوال المؤسسة');
            $table->string('email', 100)->nullable()->comment('البريد الإلكتروني الرسمي للمؤسسة');
            $table->string('website', 255)->nullable()->comment('الموقع الإلكتروني');
            $table->string('address', 255)->nullable()->comment('عنوان المقر الرئيسي');
            $table->string('city', 100)->nullable()->comment('المدينة');
            $table->string('district', 100)->nullable()->comment('الحي');
            $table->string('postal_code', 20)->nullable()->comment('الرمز البريدي');
            $table->string('po_box', 20)->nullable()->comment('صندوق البريد');
            $table->string('legal_representative_name', 255)->nullable()->comment('اسم الممثل القانوني');
            $table->string('legal_representative_id', 50)->nullable()->comment('رقم هوية الممثل القانوني');
            $table->string('legal_representative_phone', 50)->nullable()->comment('جوال الممثل القانوني');
            $table->string('bank_name', 100)->nullable()->comment('اسم البنك');
            $table->string('bank_account_name', 255)->nullable()->comment('اسم صاحب الحساب');
            $table->string('bank_account_number', 50)->nullable()->comment('رقم الحساب البنكي');
            $table->string('bank_iban', 50)->nullable()->comment('رقم الآيبان الدولي');

            $table->enum('status', ['pending_approval', 'active', 'suspended', 'blocked', 'expired'])
                  ->default('pending_approval')->comment('حالة المؤسسة: قيد الموافقة، نشط، موقف، محظور، منتهي الاشتراك');
            $table->date('subscription_expiry')->nullable()->comment('تاريخ انتهاء الاشتراك الحالي');
            $table->enum('subscription_status', ['trial', 'active', 'expired', 'grace_period'])
                  ->default('trial')->comment('حالة الاشتراك');
            $table->date('trial_expiry')->nullable()->comment('تاريخ انتهاء الفترة التجريبية');
            $table->boolean('access_blocked')->default(false)->comment('هل تم حظر الوصول؟');
            $table->text('block_reason')->nullable()->comment('سبب الحظر إن وجد');
            $table->dateTime('last_payment_check')->nullable()->comment('آخر تاريخ لفحص الدفع');

            $table->integer('max_branches')->default(1)->comment('الحد الأقصى لعدد الفروع');
            $table->integer('max_users')->default(5)->comment('الحد الأقصى لعدد المستخدمين');
            $table->integer('max_units')->default(100)->comment('الحد الأقصى لعدد الوحدات');

            $table->string('api_key', 255)->unique()->nullable()->comment('مفتاح API للمؤسسة');
            $table->boolean('api_enabled')->default(true)->comment('هل API مفعل؟');
            $table->integer('grace_period_days')->default(7)->comment('فترة السماح بعد انتهاء الاشتراك');
            $table->boolean('auto_suspend')->default(true)->comment('هل يتم الإيقاف التلقائي عند التأخير؟');

            $table->json('documents_path')->nullable()->comment('روابط المستندات المرفوعة (السجل التجاري، الرخصة، إلخ)');
            $table->dateTime('verification_date')->nullable()->comment('تاريخ التحقق من المستندات');
            $table->unsignedBigInteger('verified_by')->nullable()->comment('من قام بالتحقق (مدير النظام)');
            $table->text('rejection_reason')->nullable()->comment('سبب الرفض إن وجد');

            $table->timestamps(); // created_at و updated_at
            $table->softDeletes()->comment('تاريخ الحذف المنطقي (Soft Delete)');

            // الفهارس
            $table->index('status', 'idx_status');
            $table->index('commercial_registration', 'idx_commercial_reg');
            $table->index('subscription_expiry', 'idx_subscription_expiry');
            $table->index('deleted_at', 'idx_deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency');
    }
};
