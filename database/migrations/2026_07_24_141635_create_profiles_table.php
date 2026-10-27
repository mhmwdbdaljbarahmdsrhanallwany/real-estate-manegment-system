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
        Schema::create('profiles', function (Blueprint $table) {
            // المفتاح الأساسي لجدول الملفات الشخصية
            $table->id('profile_id');
            // ربط الملف الشخصي بحساب المستخدم في جدول users
            // علاقة واحد إلى واحد بين users و profiles
            $table->unsignedBigInteger('user_id')
                ->comment('معرف المستخدم المرتبط بالملف الشخصي');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            // منع إنشاء أكثر من ملف شخصي لنفس المستخدم
            $table->unique('user_id');
            // الاسم الأول للمستخدم
            $table->string('first_name', 100)
                ->comment('الاسم الأول');
            // اسم العائلة للمستخدم
            $table->string('last_name', 100)
                ->comment('اسم العائلة');
            // رقم الهاتف الأساسي
            $table->string('phone', 30)
                ->nullable()
                ->comment('رقم الهاتف');
            // رقم هاتف إضافي
            $table->string('alternate_phone', 30)
                ->nullable()
                ->comment('رقم هاتف احتياطي');
            // مسار صورة المستخدم
            $table->string('avatar')
                ->nullable()
                ->comment('صورة المستخدم');
            // جنس المستخدم
            // Male / Female
            $table->string('gender', 20)
                ->nullable()
                ->comment('الجنس');
            // تاريخ الميلاد
            $table->date('birth_date')
                ->nullable()
                ->comment('تاريخ الميلاد');
            // حالة الملف الشخصي
            // Active / Inactive / Suspended / Archived
            $table->string('status', 20)
                ->default('Active')
                ->comment('حالة المستخدم');
            // ملاحظات إدارية
            $table->text('notes')
                ->nullable()
                ->comment('ملاحظات');
            // المستخدم الذي قام بإنشاء السجل
            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->comment('معرف المستخدم الذي أنشأ السجل');
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            // المستخدم الذي قام بآخر تعديل
            $table->unsignedBigInteger('updated_by')
                ->nullable()
                ->comment('معرف المستخدم الذي قام بآخر تعديل');
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            // تاريخ الإنشاء والتحديث
            $table->timestamps();
            // الحذف المنطقي
            $table->softDeletes();
            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */
            // البحث بواسطة رقم الهاتف
            $table->index('phone');
            // تصفية المستخدمين حسب الحالة
            $table->index('status');
            // تحسين استعلامات الحذف المنطقي
            $table->index('deleted_at');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};