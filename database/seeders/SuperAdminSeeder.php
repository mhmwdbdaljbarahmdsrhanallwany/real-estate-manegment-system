<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Profile;
use App\Models\AccessContext;
use App\Models\UserAssignment;
use App\Models\UserRole;
use App\Models\Role;
class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {

        /*
        |--------------------------------------------------------------------------
        | Create Super Admin User
        |--------------------------------------------------------------------------
        */

        $user = User::updateOrCreate(
            [
                'email' => 'admin@example.com',
            ],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Create Profile
        |--------------------------------------------------------------------------
        */

        Profile::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'status' => 'Active',
                'created_by' => null,
                'updated_by' => null,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Create SYSTEM Access Context
        |--------------------------------------------------------------------------
        */

        $systemContext = AccessContext::updateOrCreate(
            [
                'context_type' => 'SYSTEM',
                'domain' => 'smartestate.com',
                'subdomain' => null,
            ],
            [
                'name' => 'النظام الرئيسي',
                'status' => 'ACTIVE',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Create User Assignment
        |--------------------------------------------------------------------------
        */

        $assignment = UserAssignment::updateOrCreate(
            [
                'user_id' => $user->id,
                'access_context_id' => $systemContext->id,
            ],
            [
                'status' => 'ACTIVE',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Assign Super Admin Role
        |--------------------------------------------------------------------------
        */

        $role = Role::where('name', 'super_admin')->first();


        UserRole::updateOrCreate(
            [
                'user_assignment_id' => $assignment->id,
                'role_id' => $role->id,
            ],
            [
                'status' => 'ACTIVE',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Output
        |--------------------------------------------------------------------------
        */

        $this->command->info('Super Admin created successfully.');

    }
}
