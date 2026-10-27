<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\Roles\UserControllerRole;
use App\Models\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UpdateRolePermissionsTest extends TestCase
{
    public function test_testing_environment_uses_in_memory_sqlite(): void
    {
        $this->assertSame('testing', app()->environment());
        $this->assertSame('sqlite', config('database.default'));

        $this->assertSame(
            ':memory:',
            config('database.connections.sqlite.database')
        );

        $this->assertSame('sqlite', DB::connection()->getDriverName());

        $this->assertSame(
            ':memory:',
            DB::connection()->getDatabaseName()
        );
    }

   
private function createPermissionTables(): void
{
    Schema::create('roles', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('guard_name');
        $table->string('display_name');
        $table->text('description')->nullable();
        $table->string('status', 20)->default('ACTIVE');
        $table->timestamps();
        $table->softDeletes();
        $table->unique(['name', 'guard_name']);
    });

    // إنشاء جدول الوحدات قبل جدول الصلاحيات.
    Schema::create('permission_modules', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->unsignedInteger('sort_order')->default(0);
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('permissions', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('guard_name');
        $table->unsignedBigInteger('module_id');
        $table->string('display_name', 150);
        $table->text('description')->nullable();
        $table->string('status', 20)->default('ACTIVE');
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('module_id')
            ->references('id')
            ->on('permission_modules');

        $table->unique(['name', 'guard_name']);
        $table->index('module_id');
    });

    Schema::create('role_has_permissions', function (Blueprint $table) {
        $table->unsignedBigInteger('permission_id');
        $table->unsignedBigInteger('role_id');

        $table->foreign('permission_id')
            ->references('id')
            ->on('permissions')
            ->onDelete('cascade');

        $table->foreign('role_id')
            ->references('id')
            ->on('roles')
            ->onDelete('cascade');

        $table->primary(['permission_id', 'role_id']);
    });
}


    public function test_required_permission_tables_can_be_created_in_memory(): void
    {
        $this->createPermissionTables();
        $this->assertTrue(Schema::hasTable('permission_modules'));
        $this->assertTrue(Schema::hasTable('roles'));
        $this->assertTrue(Schema::hasTable('permissions'));
        $this->assertTrue(Schema::hasTable('role_has_permissions'));
    }

    public function test_rejects_permissions_outside_the_role_modules_without_changing_assignments(): void
    {
        // حماية إضافية: لا تبدأ الاختبار ما لم يكن الاتصال SQLite في الذاكرة.
        $this->assertSame('sqlite', DB::connection()->getDriverName());
        $this->assertSame(':memory:', DB::connection()->getDatabaseName());

        $this->createPermissionTables();
        DB::table('permission_modules')->insert([
            [
                'id' => 1,
                'name' => 'properties',
                'sort_order' => 1,
            ],
            [
                'id' => 2,
                'name' => 'users',
                'sort_order' => 2,
            ],
        ]);

        $now = now();

        DB::table('roles')->insert([
            'id' => 4,
            'name' => 'employee',
            'guard_name' => 'web',
            'display_name' => 'Employee',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ]);

        DB::table('permissions')->insert([
            [
                'id' => 11,
                'name' => 'properties.view',
                'guard_name' => 'web',
                'module_id' => 1,
                'display_name' => 'View properties',
                'description' => null,
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 12,
                'name' => 'properties.create',
                'guard_name' => 'web',
              'module_id' => 1,
                'display_name' => 'Create properties',
                'description' => null,
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 1,
                'name' => 'users.view',
                'guard_name' => 'web',
                'module_id' => 2,
                'display_name' => 'View users',
                'description' => null,
                'status' => 'ACTIVE',
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        ]);

        DB::table('role_has_permissions')->insert([
            ['role_id' => 4, 'permission_id' => 11],
            ['role_id' => 4, 'permission_id' => 12],
        ]);

        $before = DB::table('role_has_permissions')
            ->where('role_id', 4)
            ->orderBy('permission_id')
            ->pluck('permission_id')
            ->all();

        $request = Request::create(
            '/roles/4/permissions',
            'PUT',
            ['permission_ids' => [11, 12, 1]]
        );

        $request->headers->set('referer', '/roles/4/permissions');

app()->instance('request', $request);

$response = app(UserControllerRole::class)
    ->updatePermissions($request, 4);

$this->assertSame(302, $response->getStatusCode());

$errors = session('errors');

$this->assertNotNull($errors);

$this->assertSame(
    'تتضمن الاختيارات صلاحيات غير نشطة أو غير مسموح بتعديلها.',
    $errors->getBag('default')->first('permission_ids')
);
        $after = DB::table('role_has_permissions')
            ->where('role_id', 4)
            ->orderBy('permission_id')
            ->pluck('permission_id')
            ->all();

        $this->assertSame($before, $after);
        $this->assertSame([11, 12], $after);
    }
}