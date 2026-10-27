<?php

namespace Database\Seeders;

use App\Models\PermissionModule;
use Illuminate\Database\Seeder;

class PermissionModulesSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'name' => 'users',
                'display_name' => 'المستخدمون',
                'description' => 'إدارة المستخدمين وبياناتهم',
                'sort_order' => 1,
            ],
            [
                'name' => 'roles',
                'display_name' => 'الأدوار',
                'description' => 'إدارة أدوار النظام',
                'sort_order' => 2,
            ],
            [
                'name' => 'permissions',
                'display_name' => 'الصلاحيات',
                'description' => 'إدارة صلاحيات النظام',
                'sort_order' => 3,
            ],
            [
                'name' => 'organizations',
                'display_name' => 'المؤسسات',
                'description' => 'إدارة المؤسسات العقارية',
                'sort_order' => 4,
            ],
            [
                'name' => 'branches',
                'display_name' => 'الفروع',
                'description' => 'إدارة فروع المؤسسات',
                'sort_order' => 5,
            ],
            [
                'name' => 'properties',
                'display_name' => 'العقارات',
                'description' => 'إدارة العقارات',
                'sort_order' => 6,
            ],
            [
                'name' => 'reports',
                'display_name' => 'التقارير',
                'description' => 'عرض تقارير النظام',
                'sort_order' => 7,
            ],
        ];

        foreach ($modules as $module) {
            PermissionModule::withTrashed()
                ->updateOrCreate(
                    ['name' => $module['name']],
                    array_merge($module, [
                        'status' => 'ACTIVE',
                        'deleted_at' => null,
                    ])
                );
        }
    }
}
