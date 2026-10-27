<?php

namespace Tests\Feature;

use App\Models\AccessContext;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\UserRole;
use App\Services\AuthorizationService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuthorizationServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // حماية: الاختبار يجب أن يستخدم SQLite داخل الذاكرة فقط.
        $this->assertSame('testing', app()->environment());
        $this->assertSame('sqlite', DB::connection()->getDriverName());
        $this->assertSame(
            ':memory:',
            DB::connection()->getDatabaseName()
        );

        $this->createAuthorizationTables();
    }

    private function createAuthorizationTables(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('access_contexts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('context_type', 50);
            $table->string('domain');
            $table->string('subdomain')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->string('status', 20)->default('ACTIVE');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->string('display_name', 150);
            $table->text('description')->nullable();
            $table->string('status', 20)->default('ACTIVE');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->unsignedBigInteger('module_id')->nullable();
            $table->string('display_name', 150);
            $table->text('description')->nullable();
            $table->string('status', 20)->default('ACTIVE');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['name', 'guard_name']);
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('user_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('access_context_id');
            $table->string('status', 20)->default('ACTIVE');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_assignment_id');
            $table->unsignedBigInteger('role_id');
            $table->string('status', 20)->default('ACTIVE');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_permission_overrides', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_role_id');
            $table->unsignedBigInteger('permission_id');
            $table->string('effect', 10)->default('ALLOW');
            $table->string('status', 20)->default('ACTIVE');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function test_active_role_grant_allows_permission_in_active_context(): void
    {
        $now = now();

        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'authorization-test@example.com',
            'password' => '',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('access_contexts')->insert([
            'id' => 1,
            'name' => 'Test Organization',
            'context_type' => 'ORGANIZATION',
            'domain' => 'test.local',
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'employee',
            'guard_name' => 'web',
            'display_name' => 'Employee',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('permissions')->insert([
            'id' => 1,
            'name' => 'properties.create',
            'guard_name' => 'web',
            'module_id' => null,
            'display_name' => 'Create property',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('user_assignments')->insert([
            'id' => 1,
            'user_id' => 1,
            'access_context_id' => 1,
            'status' => 'ACTIVE',
            'start_date' => null,
            'end_date' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('user_roles')->insert([
            'id' => 1,
            'user_assignment_id' => 1,
            'role_id' => 1,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('role_has_permissions')->insert([
            'role_id' => 1,
            'permission_id' => 1,
        ]);

        $user = User::findOrFail(1);
        $context = AccessContext::findOrFail(1);

        $service = app(AuthorizationService::class);

        $this->assertTrue(
            $service->can($user, 'properties.create', $context)
        );
    }
    
public function test_denies_permission_when_no_role_grants_it(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'no-grant@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    // لا نضيف منحًا في role_has_permissions.

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_denies_inactive_permission_even_when_role_has_it(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'inactive-permission@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'INACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_denies_soft_deleted_permission_even_when_role_has_it(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deleted-permission@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_denies_permission_when_context_is_inactive(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'inactive-context@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'INACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_denies_permission_when_assignment_is_inactive(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'inactive-assignment@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'INACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_denies_permission_when_assignment_has_expired(): void
{
    $now = now();
    $yesterday = today()->subDay()->toDateString();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'expired-assignment@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => $yesterday,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_denies_permission_when_user_role_assignment_is_inactive(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'inactive-user-role@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'INACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_denies_permission_when_role_is_inactive(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'inactive-role@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'INACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_denies_permission_when_role_is_soft_deleted(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deleted-role@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_denies_permission_when_user_role_assignment_is_soft_deleted(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deleted-user-role@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_denies_permission_when_assignment_is_soft_deleted(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deleted-assignment@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_denies_permission_when_context_is_soft_deleted(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deleted-context@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::withTrashed()->findOrFail(1)
        )
    );
}

public function test_denies_permission_when_assignment_starts_in_future(): void
{
    $now = now();
    $tomorrow = today()->addDay()->toDateString();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'future-assignment@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => $tomorrow,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_active_allow_override_grants_permission_without_role_grant(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'allow-override@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 1,
        'permission_id' => 1,
        'effect' => 'ALLOW',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertTrue(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_deny_override_blocks_permission_granted_by_role(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deny-override@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 1,
        'permission_id' => 1,
        'effect' => 'DENY',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_deny_on_one_role_overrides_grant_from_another_role(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'multi-role-deny@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'test.local',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        [
            'id' => 1,
            'name' => 'employee',
            'guard_name' => 'web',
            'display_name' => 'Employee',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'id' => 2,
            'name' => 'supervisor',
            'guard_name' => 'web',
            'display_name' => 'Supervisor',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        [
            'id' => 1,
            'user_assignment_id' => 1,
            'role_id' => 1,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'id' => 2,
            'user_assignment_id' => 1,
            'role_id' => 2,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);

    // الدور الأول يمنح الصلاحية.
    DB::table('role_has_permissions')->insert([
        'role_id' => 1,
        'permission_id' => 1,
    ]);

    // الدور الثاني يمنع الصلاحية.
    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 2,
        'permission_id' => 1,
        'effect' => 'DENY',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_deny_override_blocks_super_admin_in_system_context(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test Admin',
        'email' => 'super-admin-deny@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'System',
        'context_type' => 'SYSTEM',
        'domain' => 'system.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'super_admin',
        'guard_name' => 'web',
        'display_name' => 'Super Administrator',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 1,
        'permission_id' => 1,
        'effect' => 'DENY',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_super_admin_allows_permission_in_system_context(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test Admin',
        'email' => 'super-admin-system@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'System',
        'context_type' => 'SYSTEM',
        'domain' => 'system.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'super_admin',
        'guard_name' => 'web',
        'display_name' => 'Super Administrator',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    // لا نضيف الصلاحية إلى role_has_permissions عمدًا.

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertTrue(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_super_admin_does_not_bypass_permissions_in_organization_context(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test Admin',
        'email' => 'super-admin-org@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Test Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'super_admin',
        'guard_name' => 'web',
        'display_name' => 'Super Administrator',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    // لا يوجد منح مباشر ولا استثناء ALLOW.

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_super_admin_cannot_use_inactive_permission_in_system_context(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test Admin',
        'email' => 'super-admin-inactive-permission@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'System',
        'context_type' => 'SYSTEM',
        'domain' => 'system.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'super_admin',
        'guard_name' => 'web',
        'display_name' => 'Super Administrator',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'INACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_super_admin_cannot_bypass_inactive_assignment(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test Admin',
        'email' => 'super-admin-inactive-assignment@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'System',
        'context_type' => 'SYSTEM',
        'domain' => 'system.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'super_admin',
        'guard_name' => 'web',
        'display_name' => 'Super Administrator',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'INACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_inactive_allow_override_does_not_grant_permission(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'inactive-allow@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 1,
        'permission_id' => 1,
        'effect' => 'ALLOW',
        'status' => 'INACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_soft_deleted_allow_override_does_not_grant_permission(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deleted-allow@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 1,
        'permission_id' => 1,
        'effect' => 'ALLOW',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_inactive_deny_override_does_not_block_role_grant(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'inactive-deny@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'permission_id' => 1,
        'role_id' => 1,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 1,
        'permission_id' => 1,
        'effect' => 'DENY',
        'status' => 'INACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertTrue(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_soft_deleted_deny_override_does_not_block_role_grant(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deleted-deny@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'permission_id' => 1,
        'role_id' => 1,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 1,
        'permission_id' => 1,
        'effect' => 'DENY',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertTrue(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_deny_override_on_one_role_blocks_allow_override_on_another_role(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deny-overrides-allow@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        [
            'id' => 1,
            'name' => 'role_allow',
            'guard_name' => 'web',
            'display_name' => 'Allow Role',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'id' => 2,
            'name' => 'role_deny',
            'guard_name' => 'web',
            'display_name' => 'Deny Role',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        [
            'id' => 1,
            'user_assignment_id' => 1,
            'role_id' => 1,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'id' => 2,
            'user_assignment_id' => 1,
            'role_id' => 2,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);

    DB::table('user_permission_overrides')->insert([
        [
            'id' => 1,
            'user_role_id' => 1,
            'permission_id' => 1,
            'effect' => 'ALLOW',
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'id' => 2,
            'user_role_id' => 2,
            'permission_id' => 1,
            'effect' => 'DENY',
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_permission_with_different_guard_is_denied(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'api-guard@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'api',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_soft_deleted_user_is_denied_permission(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Deleted User',
        'email' => 'deleted-user@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'employee',
        'guard_name' => 'web',
        'display_name' => 'Employee',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'permission_id' => 1,
        'role_id' => 1,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    // نحاكي وصول كائن المستخدم المؤرشف إلى الخدمة مباشرة.
    $user = new \App\Models\User();
    $user->setRawAttributes([
        'id' => 1,
        'name' => 'Deleted User',
        'email' => 'deleted-user@example.com',
        'deleted_at' => $now,
    ], true);
    $user->exists = true;

    $context = \App\Models\AccessContext::findOrFail(1);
    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can($user, 'properties.create', $context)
    );
}

public function test_super_admin_cannot_bypass_expired_assignment(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test Admin',
        'email' => 'super-admin-expired-assignment@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'System',
        'context_type' => 'SYSTEM',
        'domain' => 'system.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'super_admin',
        'guard_name' => 'web',
        'display_name' => 'Super Administrator',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => today()->subDay()->toDateString(),
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_super_admin_cannot_bypass_soft_deleted_assignment(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test Admin',
        'email' => 'super-admin-deleted-assignment@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'System',
        'context_type' => 'SYSTEM',
        'domain' => 'system.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'super_admin',
        'guard_name' => 'web',
        'display_name' => 'Super Administrator',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_deny_override_blocks_direct_role_grant_from_another_role(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deny-direct-grant@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        [
            'id' => 1,
            'name' => 'role_grant',
            'guard_name' => 'web',
            'display_name' => 'Grant Role',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'id' => 2,
            'name' => 'role_deny',
            'guard_name' => 'web',
            'display_name' => 'Deny Role',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'permission_id' => 1,
        'role_id' => 1,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        [
            'id' => 1,
            'user_assignment_id' => 1,
            'role_id' => 1,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'id' => 2,
            'user_assignment_id' => 1,
            'role_id' => 2,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);

    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 2,
        'permission_id' => 1,
        'effect' => 'DENY',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_deny_override_on_inactive_role_does_not_block_role_grant(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'inactive-deny-role@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        [
            'id' => 1,
            'name' => 'role_grant',
            'guard_name' => 'web',
            'display_name' => 'Grant Role',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'id' => 2,
            'name' => 'role_inactive_deny',
            'guard_name' => 'web',
            'display_name' => 'Inactive Deny Role',
            'description' => null,
            'status' => 'INACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'permission_id' => 1,
        'role_id' => 1,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        [
            'id' => 1,
            'user_assignment_id' => 1,
            'role_id' => 1,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'id' => 2,
            'user_assignment_id' => 1,
            'role_id' => 2,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);

    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 2,
        'permission_id' => 1,
        'effect' => 'DENY',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertTrue(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_deny_override_on_soft_deleted_user_role_does_not_block_role_grant(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deleted-user-role-deny@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        [
            'id' => 1,
            'name' => 'role_grant',
            'guard_name' => 'web',
            'display_name' => 'Grant Role',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
        [
            'id' => 2,
            'name' => 'role_deleted_deny',
            'guard_name' => 'web',
            'display_name' => 'Deleted Deny Role',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
        ],
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('role_has_permissions')->insert([
        'permission_id' => 1,
        'role_id' => 1,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('user_roles')->insert([
        [
            'id' => 1,
            'user_assignment_id' => 1,
            'role_id' => 1,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ],
        [
            'id' => 2,
            'user_assignment_id' => 1,
            'role_id' => 2,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => $now,
        ],
    ]);
    

    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 2,
        'permission_id' => 1,
        'effect' => 'DENY',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertTrue(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_allow_override_on_soft_deleted_user_role_does_not_grant_permission(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deleted-role-allow@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        [
            'id' => 1,
            'name' => 'role_without_permission',
            'guard_name' => 'web',
            'display_name' => 'Role Without Permission',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ],
        [
            'id' => 2,
            'name' => 'role_archived',
            'guard_name' => 'web',
            'display_name' => 'Archived Role',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ],
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    DB::table('user_roles')->insert([
        [
            'id' => 1,
            'user_assignment_id' => 1,
            'role_id' => 1,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ],
        [
            'id' => 2,
            'user_assignment_id' => 1,
            'role_id' => 2,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => $now,
        ],
    ]);

    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 2,
        'permission_id' => 1,
        'effect' => 'ALLOW',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_super_admin_cannot_bypass_soft_deleted_user_role(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test Admin',
        'email' => 'super-admin-deleted-role@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'System',
        'context_type' => 'SYSTEM',
        'domain' => 'system.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'super_admin',
        'guard_name' => 'web',
        'display_name' => 'Super Administrator',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => $now,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_deny_override_on_soft_deleted_user_role_does_not_block_active_role_grant(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test User',
        'email' => 'deleted-role-deny-grant@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'Organization',
        'context_type' => 'ORGANIZATION',
        'domain' => 'organization.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('roles')->insert([
        [
            'id' => 1,
            'name' => 'role_grant',
            'guard_name' => 'web',
            'display_name' => 'Grant Role',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ],
        [
            'id' => 2,
            'name' => 'role_archived',
            'guard_name' => 'web',
            'display_name' => 'Archived Role',
            'description' => null,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ],
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    DB::table('role_has_permissions')->insert([
        'permission_id' => 1,
        'role_id' => 1,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    DB::table('user_roles')->insert([
        [
            'id' => 1,
            'user_assignment_id' => 1,
            'role_id' => 1,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
        ],
        [
            'id' => 2,
            'user_assignment_id' => 1,
            'role_id' => 2,
            'status' => 'ACTIVE',
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => $now,
        ],
    ]);

    DB::table('user_permission_overrides')->insert([
        'id' => 1,
        'user_role_id' => 2,
        'permission_id' => 1,
        'effect' => 'DENY',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertTrue(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

public function test_soft_deleted_super_admin_role_does_not_bypass_permission_checks(): void
{
    $now = now();

    DB::table('users')->insert([
        'id' => 1,
        'name' => 'Test Admin',
        'email' => 'deleted-super-admin-role@example.com',
        'password' => '',
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    DB::table('access_contexts')->insert([
        'id' => 1,
        'name' => 'System',
        'context_type' => 'SYSTEM',
        'domain' => 'system.test',
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'super_admin',
        'guard_name' => 'web',
        'display_name' => 'Super Administrator',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => $now,
    ]);

    DB::table('permissions')->insert([
        'id' => 1,
        'name' => 'properties.create',
        'guard_name' => 'web',
        'module_id' => null,
        'display_name' => 'Create property',
        'description' => null,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    DB::table('user_assignments')->insert([
        'id' => 1,
        'user_id' => 1,
        'access_context_id' => 1,
        'status' => 'ACTIVE',
        'start_date' => null,
        'end_date' => null,
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    DB::table('user_roles')->insert([
        'id' => 1,
        'user_assignment_id' => 1,
        'role_id' => 1,
        'status' => 'ACTIVE',
        'created_at' => $now,
        'updated_at' => $now,
        'deleted_at' => null,
    ]);

    $service = app(\App\Services\AuthorizationService::class);

    $this->assertFalse(
        $service->can(
            \App\Models\User::findOrFail(1),
            'properties.create',
            \App\Models\AccessContext::findOrFail(1)
        )
    );
}

}
