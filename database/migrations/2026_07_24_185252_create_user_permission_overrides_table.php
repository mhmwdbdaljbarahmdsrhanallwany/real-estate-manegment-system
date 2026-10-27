<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_permission_overrides', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */

            $table->id()
                ->comment('المعرف الأساسي');


            /*
            |--------------------------------------------------------------------------
            | User Role
            |--------------------------------------------------------------------------
            |
            | يحدد المستخدم داخل سياق معين والدور الذي يتم تعديل صلاحياته
            |
            */

            $table->unsignedBigInteger('user_role_id')
                ->comment('معرف دور المستخدم');

            $table->foreign('user_role_id')
                ->references('id')
                ->on('user_roles')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Permission
            |--------------------------------------------------------------------------
            |
            | الصلاحية التي سيتم تعديلها
            |
            */

            $table->unsignedBigInteger('permission_id')
                ->comment('معرف الصلاحية');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Effect
            |--------------------------------------------------------------------------
            |
            | ALLOW  = منح صلاحية إضافية
            | DENY   = منع صلاحية موجودة في الدور
            |
            */

            $table->string('effect', 10)
                ->default('ALLOW')
                ->comment('نوع الاستثناء ALLOW أو DENY');


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->string('status', 20)
                ->default('ACTIVE')
                ->comment('حالة الاستثناء');


            /*
            |--------------------------------------------------------------------------
            | Audit Fields
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->comment('معرف منشئ السجل');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();


            $table->unsignedBigInteger('updated_by')
                ->nullable()
                ->comment('معرف آخر من عدل السجل');

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            $table->softDeletes();


            /*
            |--------------------------------------------------------------------------
            | Constraints
            |--------------------------------------------------------------------------
            */

            // منع تكرار نفس الاستثناء لنفس الدور والصلاحية
            $table->unique([
                'user_role_id',
                'permission_id'
            ]);


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('user_role_id');

            $table->index('permission_id');

            $table->index('effect');

            $table->index('status');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('user_permission_overrides');
    }
};