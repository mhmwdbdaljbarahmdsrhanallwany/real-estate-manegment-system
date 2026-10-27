<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class UserControllerRole extends Controller
{
    
   
public function index()
{
    $roles = Role::query()
        ->withCount([
            'userRoles as user_roles_count' => function ($query) {
                $query->withTrashed();
            },
        ])
        ->orderByDesc('id')
        ->paginate(10);

    return view('admin.users_roles.roles.index', compact('roles'));
}

public function ajax_roles(Request $request)
{
    $tab = $request->input('tab', 'current');

    if (!in_array($tab, ['current', 'deleted'], true)) {
        $tab = 'current';
    }

    $sortBy = $request->input('sortBy', 'id');
    $sortOrder = $request->input('sort', 'desc');

    $allowedSorts = [
        'id',
        'name',
        'display_name',
        'created_at',
    ];

    if (!in_array($sortBy, $allowedSorts, true)) {
        $sortBy = 'id';
    }

    if (!in_array($sortOrder, ['asc', 'desc'], true)) {
        $sortOrder = 'desc';
    }

    $query = $this->rolesQueryForTab($tab);

    if ($request->filled('searchName')) {
        $search = trim($request->input('searchName'));

        $query->where(function ($q) use ($search) {
            $q->where('display_name', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%");
        });
    }

    $statusFilter = $request->input('statusFilter');

    if (in_array($statusFilter, ['ACTIVE', 'INACTIVE'], true)) {
        $query->where('status', $statusFilter);
    }

    $roles = $query
        ->orderBy($sortBy, $sortOrder)
        ->paginate(10)
        ->appends(array_merge(
            $request->except('_token', 'page'),
            ['tab' => $tab]
        ));

    return view(
        'admin.users_roles.roles.ajax_roles',
        compact('roles', 'tab')
    );
}

private function rolesQueryForTab(string $tab)
{
    $query = Role::query();

    if ($tab === 'deleted') {
        $query->onlyTrashed();
    }

    return $query->withCount([
        'userRoles as user_roles_count' => function ($query) {
            $query->withTrashed();
        },
    ]);
}

/**
 * عرض صفحة إنشاء دور جديد.
 */
public function createRole()
{
    return view('admin.users_roles.roles.create');
}

/**
 * حفظ دور جديد.
 */
public function storeRole(Request $request)
{
    $guardName = config('auth.defaults.guard', 'web');

    $validated = $request->validate([
        'display_name' => [
            'required',
            'string',
            'max:255',
        ],

        'name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-Z][a-zA-Z0-9_]*$/',
            function ($attribute, $value, $fail) use ($guardName) {
                $exists = Role::withTrashed()
                    ->where('name', $value)
                    ->where('guard_name', $guardName)
                    ->exists();

                if ($exists) {
                    $fail(
                        'الاسم البرمجي مستخدم مسبقًا، بما في ذلك الأدوار المحذوفة منطقيًا.'
                    );
                }
            },
        ],

        'description' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ], [
        'display_name.required' => 'اسم الدور مطلوب.',
        'display_name.string' => 'اسم الدور يجب أن يكون نصًا.',
        'display_name.max' => 'اسم الدور يجب ألا يتجاوز 255 حرفًا.',

        'name.required' => 'الاسم البرمجي مطلوب.',
        'name.string' => 'الاسم البرمجي يجب أن يكون نصًا.',
        'name.max' => 'الاسم البرمجي يجب ألا يتجاوز 255 حرفًا.',
        'name.regex' => 'الاسم البرمجي يجب أن يبدأ بحرف إنجليزي، ويحتوي على أحرف إنجليزية وأرقام وشرطة سفلية فقط.',

        'description.string' => 'وصف الدور يجب أن يكون نصًا.',
        'description.max' => 'وصف الدور يجب ألا يتجاوز 1000 حرف.',
    ]);

    DB::transaction(function () use ($validated, $guardName) {
        Role::create([
            'display_name' => $validated['display_name'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'guard_name' => $guardName,
            'status' => 'ACTIVE',
        ]);
    });

    return redirect()
        ->route('admin.roles.index')
        ->with('success', 'تم إنشاء الدور بنجاح.');
}
/**```php
/**
 * عرض تفاصيل الدور.
 */
public function show($id, $tab = 'info')
{
    $roleQuery = Role::query();

    // السماح بعرض الدور المحذوف منطقيًا عند طلبه صراحةً.
    if (request()->query('deleted') === '1') {
        $roleQuery->onlyTrashed();
    }

    $role = $roleQuery->findOrFail($id);

    $data = [
        'role' => $role,
        'tab' => $tab,
    ];

    // تحميل الصلاحيات عند فتح تبويب الصلاحيات فقط.
    
if ($tab === 'permissions') {

    // استخراج الوحدات المرتبطة بالصلاحيات المسندة إلى الدور
    $modules = DB::table('role_has_permissions')
        ->join(
            'permissions',
            'permissions.id',
            '=',
            'role_has_permissions.permission_id'
        )
        ->join(
            'permission_modules',
            'permission_modules.id',
            '=',
            'permissions.module_id'
        )
        ->where('role_has_permissions.role_id', $role->id)
        ->where('permissions.guard_name', $role->guard_name)
        ->distinct()
        ->orderBy('permission_modules.sort_order')
        ->pluck('permission_modules.name');

    // جلب الصلاحيات النشطة ضمن وحدات الدور
    $permissions = collect();

    if ($modules->isNotEmpty()) {
        $permissions = Permission::query()
            ->with('permissionModule')
            ->whereHas('permissionModule', function ($query) use ($modules) {
                $query->whereIn('name', $modules);
            })
            ->where('guard_name', $role->guard_name)
            ->where('status', 'ACTIVE')
            ->whereNull('deleted_at')
            ->get()
            ->sortBy([
                ['permissionModule.sort_order', 'asc'],
                ['display_name', 'asc'],
            ])
            ->groupBy(
                fn ($permission) =>
                    $permission->permissionModule?->name ?? '__unassigned'
            );
    }

    // معرّفات الصلاحيات الظاهرة والمسندة حاليًا إلى الدور
    $visiblePermissionIds = $permissions
        ->flatten(1)
        ->pluck('id')
        ->map(fn ($id) => (int) $id)
        ->values();

    $assignedPermissionIds = DB::table('role_has_permissions')
        ->where('role_id', $role->id)
        ->whereIn('permission_id', $visiblePermissionIds)
        ->pluck('permission_id')
        ->map(fn ($id) => (int) $id)
        ->all();

    $data['permissions'] = $permissions;
    $data['assignedPermissionIds'] = $assignedPermissionIds;
    $data['modules'] = $modules;
}


    // تحميل إسنادات الدور والمستخدمين عند فتح تبويب المستخدمين.
    if ($tab === 'users') {
        $role->load([
            'permissions' => function ($query) {
                $query
                    ->where('permissions.status', 'ACTIVE')
                    ->whereNull('permissions.deleted_at');
            },
            'userRoles' => function ($query) {
                $query->where('status', 'ACTIVE');
            },
            'userRoles.userAssignment' => function ($query) {
                $query->where('status', 'ACTIVE');
            },
            'userRoles.userAssignment.user',
            'userRoles.userAssignment.accessContext',
        ]);
    
        // يجب أن يحتوي الدور على صلاحية نشطة واحدة على الأقل.
        $hasActivePermissions = $role->permissions->isNotEmpty();
    
        $users = collect();
    
        if ($hasActivePermissions) {
            $users = $role->userRoles
                ->filter(function ($userRole) {
                    $assignment = $userRole->userAssignment;
    
                    return $assignment
                        && $assignment->user
                        && $assignment->accessContext
                        && $assignment->accessContext->status === 'ACTIVE';
                })
                ->values();
        }
    
        $data['users'] = $users;
    }

    // إنشاء روابط تبويبات التفاصيل.
    $links = app(\App\Services\MenuService::class)
        ->getLinks($role);

    $data['links'] = $links;

    return view('admin.users_roles.roles.show', $data);
}

/**
 * الحذف المنطقي للدور مع التحقق من الإسنادات.
 */
public function destroy($id)
{
    $role = Role::findOrFail($id);

    if ($role->userRoles()->exists()) {
        $message = 'لا يمكن حذف هذا الدور لوجود إسنادات لم تُحذف منطقيًا.';

        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }

        return redirect()->back()->with('error', $message);
    }

    DB::transaction(function () use ($role) {
        $role->delete();
    });

    $message = 'تم حذف الدور منطقيًا بنجاح.';

    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    return redirect()->back()->with('success', $message);
}
/**
 * عرض صفحة تعديل بيانات الدور.
 */
public function editRole($id)
{
   
    $role = Role::findOrFail($id);

    return view(
        'admin.users_roles.roles.edit',
        compact('role')
    );
}

/**
 * تحديث بيانات الدور.
 */
public function updateRole(Request $request, $id)
{
    $role = Role::findOrFail($id);

    $validated = $request->validate([
        'display_name' => [
            'required',
            'string',
            'max:255',
        ],

        'description' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ], [
        'display_name.required' => 'اسم الدور مطلوب.',
        'display_name.string' => 'اسم الدور يجب أن يكون نصًا.',
        'display_name.max' => 'اسم الدور يجب ألا يتجاوز 255 حرفًا.',

        'description.string' => 'وصف الدور يجب أن يكون نصًا.',
        'description.max' => 'وصف الدور يجب ألا يتجاوز 1000 حرف.',
    ]);

    DB::transaction(function () use ($role, $validated) {
        $role->update([
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
        ]);
    });

    return redirect()
        ->route('admin.roles.show', [
            'id' => $role->id,
            'tab' => 'info',
        ])
        ->with('success', 'تم تحديث بيانات الدور بنجاح.');
}
/**
 * تغيير حالة الدور.
 */
public function changeStatus(Request $request, $id)
{
    $validated = $request->validate([
        'status' => [
            'required',
            'in:ACTIVE,INACTIVE',
        ],
    ], [
        'status.required' => 'حالة الدور مطلوبة.',
        'status.in' => 'الحالة المحددة غير صالحة.',
    ]);

    $role = Role::findOrFail($id);

    $role->update([
        'status' => $validated['status'],
    ]);

    $message = $validated['status'] === 'ACTIVE'
        ? 'تم تفعيل الدور بنجاح.'
        : 'تم تعطيل الدور بنجاح.';

    return response()->json([
        'status' => true,
        'message' => $message,
    ]);
}
public function restoreRole($id)
{
    $role = Role::onlyTrashed()->findOrFail($id);

    DB::transaction(function () use ($role) {
        $role->restore();
    });

    return response()->json([
        'status' => true,
        'message' => 'تمت استعادة الدور بنجاح.',
    ]);
}
/**
 * الحذف النهائي للدور بعد التحقق من عدم وجود إسنادات مرتبطة به.
 */
public function forceDestroy($id)
{
    $role = Role::onlyTrashed()->findOrFail($id);

    // التحقق من جميع الإسنادات، بما فيها المحذوفة منطقيًا.
    if ($role->userRoles()->withTrashed()->exists()) {
        $message = 'لا يمكن حذف الدور نهائيًا لوجود إسنادات مرتبطة به، بما فيها الإسنادات المحذوفة منطقيًا.';

        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }

        return redirect()->back()->with('error', $message);
    }

    DB::transaction(function () use ($role) {
        $role->forceDelete();
    });

    $message = 'تم حذف الدور نهائيًا بنجاح.';

    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    return redirect()->back()->with('success', $message);
}
public function updatePermissions(Request $request, $id)
{
    $role = Role::findOrFail($id);

    $validated = $request->validate([
        'permission_ids' => ['nullable', 'array'],
        'permission_ids.*' => ['required', 'integer', 'distinct'],
    ], [
        'permission_ids.array' => 'تنسيق الصلاحيات المحددة غير صحيح.',
        'permission_ids.*.integer' => 'معرّف الصلاحية غير صحيح.',
        'permission_ids.*.distinct' => 'تم تحديد صلاحية مكررة.',
    ]);

    $permissionIds = collect($validated['permission_ids'] ?? [])
        ->map(fn ($permissionId) => (int) $permissionId)
        ->unique()
        ->values();

    // استخراج الوحدات المرتبطة بالصلاحيات المسندة إلى الدور.
    $modules = DB::table('role_has_permissions')
        ->join(
            'permissions',
            'permissions.id',
            '=',
            'role_has_permissions.permission_id'
        )
        ->join(
            'permission_modules',
            'permission_modules.id',
            '=',
            'permissions.module_id'
        )
        ->where('role_has_permissions.role_id', $role->id)
        ->where('permissions.guard_name', $role->guard_name)
        ->distinct()
        ->pluck('permission_modules.name');

    if ($modules->isEmpty()) {
        return back()->withErrors([
            'permission_ids' =>
                'لا توجد وحدات مرتبطة بهذا الدور يمكن إدارة صلاحياتها.',
        ]);
    }

    // جلب الصلاحيات النشطة ضمن الوحدات المسموح بإدارتها.
    $visiblePermissionIds = Permission::query()
        ->whereHas('permissionModule', function ($query) use ($modules) {
            $query->whereIn('name', $modules);
        })
        ->where('guard_name', $role->guard_name)
        ->where('status', 'ACTIVE')
        ->whereNull('deleted_at')
        ->pluck('id')
        ->map(fn ($permissionId) => (int) $permissionId)
        ->values();

    // رفض أي صلاحية مرسلة خارج النطاق المسموح.
    if ($permissionIds->diff($visiblePermissionIds)->isNotEmpty()) {
        return back()->withErrors([
            'permission_ids' =>
                'تتضمن الاختيارات صلاحيات غير نشطة أو غير مسموح بتعديلها.',
        ]);
    }

    // إلغاء إسناد الصلاحيات التي أزيل تحديدها فقط.
    $permissionsToDetach = $visiblePermissionIds
        ->diff($permissionIds)
        ->values()
        ->all();

    DB::transaction(function () use (
        $role,
        $permissionIds,
        $permissionsToDetach
    ) {
        if (!empty($permissionsToDetach)) {
            $role->permissions()->detach($permissionsToDetach);
        }

        if ($permissionIds->isNotEmpty()) {
            $role->permissions()->syncWithoutDetaching(
                $permissionIds->all()
            );
        }
    });

    app(\Spatie\Permission\PermissionRegistrar::class)
        ->forgetCachedPermissions();

    return redirect()
        ->route('admin.roles.show', [
            'id' => $role->id,
            'tab' => 'permissions',
        ])
        ->with('success', 'تم تحديث صلاحيات الدور بنجاح.');
}

}
