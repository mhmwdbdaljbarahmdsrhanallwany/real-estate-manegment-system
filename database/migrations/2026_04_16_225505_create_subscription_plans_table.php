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
        Schema::create('subscription_plans', function (Blueprint $table) {
             // المفتاح الأساسي باسم الجدول
             $table->bigIncrements('plan_id')
             ->comment('المعرّف الأساسي لجدول الباقات');

       $table->string('plan_name', 100)
             ->comment('اسم الباقة مثل: أساسية، متوسطة، متقدمة');

       $table->enum('plan_tier', ['Basic','Standard','Premium','Enterprise'])
             ->default('Basic')
             ->comment('مستوى الباقة: أساسية، قياسية، متقدمة، مؤسسات');

       $table->decimal('plan_price', 10, 2)
             ->default(0)
             ->comment('السعر الأساسي للباقة قبل الخصومات والضرائب');

       $table->enum('currency', ['USD','EUR','SAR','GBP'])
             ->default('USD')
             ->comment('العملة المستخدمة في الاشتراك');

       $table->decimal('tax_rate', 5, 2)
             ->default(0)
             ->comment('نسبة الضريبة المضافة على الاشتراك');

       $table->decimal('discount_rate', 5, 2)
             ->default(0)
             ->nullable()
             ->comment('نسبة الخصم المطبقة على الباقة');

       $table->decimal('discount_amount', 10, 2)
             ->default(0)
             ->nullable()
             ->comment('قيمة الخصم المحسوبة من السعر الأساسي');

       $table->integer('max_users')
             ->default(1)
             ->comment('عدد المستخدمين المسموح لهم ضمن الباقة');

       $table->integer('max_branches')
             ->default(1)
             ->comment('عدد الفروع المسموح بها ضمن الباقة');

       $table->decimal('storage_space', 10, 2)
             ->default(0)
             ->comment('مساحة التخزين المخصصة بالـ GB أو MB');

       $table->text('features')
             ->nullable()
             ->comment('قائمة الميزات المفعلة في هذه الباقة');

       $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
