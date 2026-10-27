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
        Schema::create('user_roles', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */
            $table->id()
                ->comment('المعرف الأساسي');
            /*
            |--------------------------------------------------------------------------
            | User Assignment
            |--------------------------------------------------------------------------
            */
            $table->unsignedBigInteger('user_assignment_id')
                ->comment('معرف تعيين المستخدم');

            $table->foreign('user_assignment_id')
                ->references('id')
                ->on('user_assignments')
                ->cascadeOnDelete();
            /*
            |--------------------------------------------------------------------------
            | Role
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('role_id')
                ->comment('معرف الدور');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            |
            | ACTIVE
            | INACTIVE
            | SUSPENDED
            |
            */

            $table->string('status', 20)
                ->default('ACTIVE')
                ->comment('حالة إسناد الدور');

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

            // يمنع إسناد نفس الدور أكثر من مرة لنفس التعيين
            $table->unique([
                'user_assignment_id',
                'role_id'
            ]);

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('user_assignment_id');

            $table->index('role_id');

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};