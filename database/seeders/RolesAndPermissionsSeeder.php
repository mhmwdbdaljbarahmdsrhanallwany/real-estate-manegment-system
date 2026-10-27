<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionModule;
use RuntimeException;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Users Management
            [
                'name' => 'users.view',
                'module' => 'users',
                'display_name' => 'عرض المستخدمين',
                'description' => 'عرض قائمة المستخدمين',
            ],

            [
                'name' => 'users.create',
                'module' => 'users',
                'display_name' => 'إنشاء مستخدم',
                'description' => 'إنشاء مستخدم جديد',
            ],

            [
                'name' => 'users.update',
                'module' => 'users',
                'display_name' => 'تعديل المستخدم',
                'description' => 'تعديل بيانات المستخدم',
            ],

            [
                'name' => 'users.delete',
                'module' => 'users',
                'display_name' => 'حذف المستخدم',
                'description' => 'حذف المستخدم',
            ],

            [
                'name' => 'users.assign_role',
                'module' => 'users',
                'display_name' => 'تعيين دور للمستخدم',
                'description' => 'إسناد دور وصلاحيات للمستخدم',
            ],


            // Roles & Permissions
            [
                'name' => 'roles.manage',
                'module' => 'roles',
                'display_name' => 'إدارة الأدوار',
                'description' => 'إنشاء وتعديل الأدوار',
            ],

            [
                'name' => 'permissions.manage',
                'module' => 'permissions',
                'display_name' => 'إدارة الصلاحيات',
                'description' => 'إدارة صلاحيات النظام',
            ],


            // Organizations
            [
                'name' => 'organizations.view',
                'module' => 'organizations',
                'display_name' => 'عرض المؤسسات',
                'description' => 'عرض المؤسسات',
            ],

            [
                'name' => 'organizations.manage',
                'module' => 'organizations',
                'display_name' => 'إدارة المؤسسات',
                'description' => 'إدارة المؤسسات',
            ],


            // Branches
            [
                'name' => 'branches.manage',
                'module' => 'branches',
                'display_name' => 'إدارة الفروع',
                'description' => 'إدارة فروع المؤسسة',
            ],


            // Properties
            [
                'name' => 'properties.view',
                'module' => 'properties',
                'display_name' => 'عرض العقارات',
                'description' => 'عرض العقارات',
            ],

            [
                'name' => 'properties.create',
                'module' => 'properties',
                'display_name' => 'إضافة عقار',
                'description' => 'إضافة عقار جديد',
            ],

            [
                'name' => 'properties.update',
                'module' => 'properties',
                'display_name' => 'تعديل عقار',
                'description' => 'تعديل بيانات العقار',
            ],

            [
                'name' => 'properties.delete',
                'module' => 'properties',
                'display_name' => 'حذف عقار',
                'description' => 'حذف العقارات',
            ],


            // Reports
            [
                'name' => 'reports.view',
                'module' => 'reports',
                'display_name' => 'عرض التقارير',
                'description' => 'عرض تقارير النظام',
            ],

        ];

        foreach ($permissions as $permissionData) {

            // الاحتفاظ باسم الوحدة قبل حذف المفتاح القديم من بيانات الصلاحية
            $moduleName = $permissionData['module'];
        
            // البحث عن الوحدة في جدول permission_modules
            $moduleId = PermissionModule::where('name', $moduleName)
                ->value('id');
        
            // إيقاف التنفيذ إذا كانت الوحدة غير موجودة
            if (!$moduleId) {
                throw new RuntimeException(
                    "الوحدة [{$moduleName}] غير موجودة في جدول permission_modules."
                );
            }
        
            // حذف المفتاح الذي لم يعد يمثل عمودًا في جدول permissions
            unset($permissionData['module']);
        
            // إنشاء الصلاحية أو تحديثها وربطها بالوحدة
            Permission::updateOrCreate(
                [
                    'name' => $permissionData['name'],
                    'guard_name' => 'web',
                ],
                array_merge($permissionData, [
                    'module_id' => $moduleId,
                    'guard_name' => 'web',
                    'status' => 'ACTIVE',
                ])
            );
        }



        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $roles = [

            [
                'name' => 'super_admin',
                'display_name' => 'مدير النظام',
                'description' => 'إدارة النظام بالكامل',
            ],

            [
                'name' => 'organization_admin',
                'display_name' => 'مدير المؤسسة',
                'description' => 'إدارة مؤسسة محددة',
            ],

            [
                'name' => 'manager',
                'display_name' => 'مدير',
                'description' => 'إدارة حسب الصلاحيات الممنوحة',
            ],

            [
                'name' => 'employee',
                'display_name' => 'موظف',
                'description' => 'تنفيذ المهام المصرح بها',
            ],

        ];


        foreach ($roles as $role) {

            Role::updateOrCreate(
                [
                    'name' => $role['name'],
                    'guard_name' => 'web',
                ],
                array_merge($role, [
                    'guard_name' => 'web',
                    'status' => 'ACTIVE',
                ])
            );

        }



        /*
        |--------------------------------------------------------------------------
        | Assign Permissions To Roles
        |--------------------------------------------------------------------------
        */
        // مدير النظام
        Role::findByName('super_admin')
            ->syncPermissions(
                Permission::all()
            );
        // مدير المؤسسة
        Role::findByName('organization_admin')
            ->syncPermissions([
                'users.view',
                'users.create',
                'users.update',
                'users.assign_role',
                'branches.manage',
                'properties.view',
                'properties.create',
                'properties.update',
                'properties.delete',
                'reports.view',
            ]);
        // المدير
        Role::findByName('manager')
            ->syncPermissions([
                'users.view',
                'users.create',
                'properties.view',
                'properties.create',
                'properties.update',
                'reports.view',

            ]);

        // الموظف
        Role::findByName('employee')
            ->syncPermissions([
                'properties.view',
                'properties.create',
            ]);

            
    }
}