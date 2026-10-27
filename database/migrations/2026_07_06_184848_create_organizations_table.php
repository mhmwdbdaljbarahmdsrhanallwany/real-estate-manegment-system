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
        Schema::create('organizations', function (Blueprint $table) {

            // المعرف الفريد للمؤسسة
            $table->id('organization_id')->comment('المعرف الفريد للمؤسسة');
        
            // اسم المؤسسة العقارية
            $table->string('name', 255)
                ->comment('اسم المؤسسة العقارية');
        
            // الاسم التجاري
            $table->string('commercial_name', 255)
                ->nullable()
                ->comment('الاسم التجاري');
        
            // السجل التجاري
            $table->string('commercial_registration', 50)
                ->unique()
                ->nullable()
                ->comment('رقم السجل التجاري');
        
            // الرقم الضريبي
            $table->string('tax_number', 50)
                ->unique()
                ->nullable()
                ->comment('الرقم الضريبي');
        
            // رخصة البلدية
            $table->string('municipality_license', 255)
                ->nullable()
                ->comment('ملف رخصة البلدية');
        
            // رخصة الوساطة العقارية
            $table->string('real_estate_broker_license', 255)
                ->nullable()
                ->comment('ملف رخصة الوساطة العقارية');
        
            // الرقم الوطني الموحد
            $table->string('unified_national_number', 20)
                ->nullable()
                ->comment('الرقم الوطني الموحد');
        
            // رقم الهاتف
            $table->string('phone', 30)
                ->nullable()
                ->comment('رقم الهاتف');
        
            // رقم الجوال
            $table->string('mobile', 30)
                ->nullable()
                ->comment('رقم الجوال');
        
            // البريد الإلكتروني
            $table->string('email', 100)
                ->nullable()
                ->comment('البريد الإلكتروني الرسمي');
        
            // الموقع الإلكتروني
            $table->string('website')
                ->nullable()
                ->comment('الموقع الإلكتروني');
        
            // شعار المؤسسة
            $table->string('logo')
                ->nullable()
                ->comment('شعار المؤسسة');
        
            // الدولة
            $table->string('country', 100)
                ->nullable()
                ->comment('الدولة');
        
            // المحافظة / المنطقة
            $table->string('state', 100)
                ->nullable()
                ->comment('المحافظة أو المنطقة');
        
            // المدينة
            $table->string('city', 100)
                ->nullable()
                ->comment('المدينة');
        
            // الحي
            $table->string('district', 100)
                ->nullable()
                ->comment('الحي');
        
            // الشارع
            $table->string('street', 150)
                ->nullable()
                ->comment('الشارع');
        
            // العنوان التفصيلي
            $table->string('address')
                ->nullable()
                ->comment('العنوان التفصيلي');
        
            // الرمز البريدي
            $table->string('postal_code', 20)
                ->nullable()
                ->comment('الرمز البريدي');
        
            // صندوق البريد
            $table->string('po_box', 20)
                ->nullable()
                ->comment('صندوق البريد');
        
            // خط العرض
            $table->decimal('latitude', 10, 7)
                ->nullable()
                ->comment('خط العرض');
        
            // خط الطول
            $table->decimal('longitude', 10, 7)
                ->nullable()
                ->comment('خط الطول');
        
            // رابط الموقع على الخريطة
            $table->string('map_url')
                ->nullable()
                ->comment('رابط الموقع على الخريطة');
        
            // اسم الممثل القانوني
            $table->string('legal_representative_name')
                ->nullable()
                ->comment('اسم الممثل القانوني');
        
            // رقم هوية الممثل القانوني
            $table->string('legal_representative_id', 50)
                ->nullable()
                ->comment('رقم هوية الممثل القانوني');
        
            // جوال الممثل القانوني
            $table->string('legal_representative_phone', 30)
                ->nullable()
                ->comment('جوال الممثل القانوني');
        
            // اسم البنك
            $table->string('bank_name')
                ->nullable()
                ->comment('اسم البنك');
        
            // اسم صاحب الحساب
            $table->string('bank_account_name')
                ->nullable()
                ->comment('اسم صاحب الحساب');
        
            // رقم الحساب البنكي
            $table->string('bank_account_number', 50)
                ->nullable()
                ->comment('رقم الحساب البنكي');
        
            // رقم الآيبان
            $table->string('bank_iban', 50)
                ->nullable()
                ->comment('رقم الآيبان');
        
            // حالة المؤسسة
            $table->enum('status', [
                'pending_approval',
                'active',
                'suspended',
                'blocked',
                'expired'
            ])->default('pending_approval')
              ->comment('حالة المؤسسة');
        
            // مفتاح API
            $table->string('api_key')
                ->unique()
                ->nullable()
                ->comment('مفتاح API');
        
            // هل خدمة API مفعلة
            $table->boolean('api_enabled')
                ->default(true)
                ->comment('تفعيل واجهة API');
        
            // ملاحظات
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات');
        
            // تواريخ الإنشاء والتعديل
            $table->timestamps();
        
            // الحذف المنطقي
            $table->softDeletes();
        
            // الفهارس
            $table->index('name');
            $table->index('commercial_registration');
            $table->index('tax_number');
            $table->index('status');
            $table->index('country');
            $table->index('state');
            $table->index('city');
            $table->index('district');
            $table->index('mobile');
            $table->index('email');
            $table->index('deleted_at');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
