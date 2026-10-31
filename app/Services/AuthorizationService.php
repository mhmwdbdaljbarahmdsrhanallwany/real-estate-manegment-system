اوثورايز سيرفر
<?php

namespace App\Services;

use App\Models\AccessContext;
use App\Models\Permission;
use App\Models\User;

class AuthorizationService
{
    /**
     * التحقق من امتلاك المستخدم صلاحية داخل سياق معين.
     */
    public function can(
        User $user,
        string $permission,
        AccessContext $context
    ): bool {
        if ($user->trashed()) {
            return false;
        }
        // 1. التحقق من أن سياق الوصول نشط.

        if ($context->status !== 'ACTIVE'  $context->trashed()) {
            return false;
        }

        // 2. التحقق من وجود الصلاحية وأنها نشطة.
        // SoftDeletes يستبعد الصلاحيات المؤرشفة منطقيًا.
        $permissionModel = Permission::query()
            ->where('name', $permission)
            ->where('guard_name', 'web')
            ->where('status', 'ACTIVE')
            ->first();

        if (! $permissionModel) {
            return false;
        }

        // 3. التحقق من وجود تعيين نشط وساري المفعول
        // للمستخدم داخل سياق الوصول المطلوب.
        $assignment = $user->assignments()
            ->where('access_context_id', $context->id)
            ->where('status', 'ACTIVE')
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', today());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', today());
            })
            ->first();

        if (! $assignment) {
            return false;
        }

        // 4. جلب تعيينات الأدوار النشطة مع بيانات الأدوار.
        // SoftDeletes يستبعد السجلات المحذوفة منطقيًا.
        $userRoles = $assignment->userRoles()
            ->where('status', 'ACTIVE')
            ->with('role')
            ->get();

        // 5. فحص DENY على جميع الأدوار النشطة أولًا.
        // وجود منع واحد فعال يعني رفض الصلاحية نهائيًا.
        foreach ($userRoles as $userRole) {
            $role = $userRole->role;

            // تجاهل الدور غير الموجود أو غير النشط.
            if (! $role  $role->status !== 'ACTIVE') {
                continue;
            }

            $hasDeny = $userRole->permissionOverrides()
                ->where('permission_id', $permissionModel->id)
                ->where('status', 'ACTIVE')
                ->where('effect', 'DENY')
                ->exists();

            if ($hasDeny) {
                return false;
            }
        }

        // 6. تجاوز الصلاحيات الممنوحة للأدوار الأخرى بواسطة
        // super_admin داخل SYSTEM فقط، بعد اجتياز DENY.
        if ($context->context_type === 'SYSTEM') {
            foreach ($userRoles as $userRole) {
                $role = $userRole->role;

                if (
                    $role &&
                    $role->status === 'ACTIVE' &&
                    $role->name === 'super_admin'
                ) {
                    return true;
                }
            }
        }

        // 7. فحص منح الصلاحية عبر الأدوار النشطة.
        foreach ($userRoles as $userRole) {
            $role = $userRole->role;
            // تجاهل الدور غير الموجود أو غير النشط.
            if (! $role  $role->status !== 'ACTIVE') {
                continue;
            }

            // 8. فحص الاستثناء النشط الخاص بهذا الدور.
            $override = $userRole->permissionOverrides()
                ->where('permission_id', $permissionModel->id)
                ->where('status', 'ACTIVE')
                ->first();

            // ALLOW يمنح الصلاحية لهذا التعيين.
            // وقد سبق فحص DENY العام لجميع الأدوار النشطة.
            if ($override?->effect === 'ALLOW') {
                return true;
            }

            // 9. عند عدم وجود ALLOW، نفحص الصلاحيات الأصلية للدور.
            $hasPermission = $role->permissions()
                ->where('permissions.id', $permissionModel->id)
                ->where('permissions.status', 'ACTIVE')
                ->exists();

            if ($hasPermission) {
                return true;
            }
        }

        // 10. لا يوجد منح صالح للصلاحية المطلوبة.
        return false;
    }
    public function canEnterContext(
        User $user,
        AccessContext $context
    ): bool {
        if ($user->trashed()) {
            return false;
        }

        if ($context->trashed()  $context->status !== 'ACTIVE') {
            return false;
        }

        $assignment = $user->assignments()
            ->where('access_context_id', $context->id)
            ->where('status', 'ACTIVE')
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', today());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', today());
            })
            ->first();

        if (!$assignment) {
            return false;
        }

        return $assignment->userRoles()
            ->where('status', 'ACTIVE')
            ->whereHas('role', function ($query) {
                $query->where('status', 'ACTIVE');
            })
            ->exists();
    }
}