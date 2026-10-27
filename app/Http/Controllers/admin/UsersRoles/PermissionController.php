<?php

namespace App\Http\Controllers\Admin\UsersRoles;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionModule;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
class PermissionController extends Controller
{

    
/**
 * عرض قائمة الصلاحيات.
 */
public function index(): View
{
    // الإحصائيات
    $totalPermissions = Permission::count();

    $activePermissions = Permission::where('status', 'ACTIVE')->count();

    $inactivePermissions = Permission::where('status', 'INACTIVE')->count();

    // استخراج الوحدات المرتبطة بالصلاحيات من جدول permission_modules
    $moduleValues = \App\Models\PermissionModule::query()
        ->whereHas('permissions')
        ->orderBy('sort_order')
        ->get(['id', 'name', 'display_name']);

    // تجهيز خيارات الفلتر: القيمة البرمجية، والاسم العربي للعرض
    $modules = ['all' => 'جميع الوحدات'];

    foreach ($moduleValues as $module) {
        $modules[$module->name] = $module->display_name;
    }

    // قراءة الوحدة المحددة في الفلتر
    $selectedModule = request('moduleFilter', 'all');

    // جلب الصلاحيات مع تحميل الوحدة المرتبطة
    $query = Permission::query()
        ->with('permissionModule');

    // تطبيق فلتر الوحدة باستخدام module_id
    if ($selectedModule !== 'all' && $selectedModule !== '') {
        $query->whereHas('permissionModule', function ($moduleQuery) use ($selectedModule) {
            $moduleQuery->where('name', $selectedModule);
        });
    }

    $permissions = $query
        ->orderBy(
            \App\Models\PermissionModule::select('sort_order')
                ->whereColumn(
                    'permission_modules.id',
                    'permissions.module_id'
                )
        )
        ->orderBy('display_name')
        ->paginate(15)
        ->withQueryString();

    // تقسيم صلاحيات الصفحة الحالية حسب الوحدة
    $groupedPermissions = $permissions
        ->getCollection()
        ->groupBy(
            fn ($permission) => $permission->permissionModule?->name
                ?? '__unassigned'
        );

    return view(
        'admin.users_roles.permissions.index',
        compact(
            'permissions',
            'groupedPermissions',
            'modules',
            'selectedModule',
            'totalPermissions',
            'activePermissions',
            'inactivePermissions'
        )
    );
}

/**
 * عرض نموذج إضافة صلاحية جديدة.
 */
public function create(): View
{
    $modules = PermissionModule::query()
        ->where('status', 'ACTIVE')
        ->orderBy('sort_order')
        ->get(['id', 'name', 'display_name']);

    return view(
        'admin.users_roles.permissions.create',
        compact('modules')
    );
}

/**
 * حفظ صلاحية جديدة.
 */
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'module_id' => [
            'required',
            'integer',
            Rule::exists('permission_modules', 'id')
                ->whereNull('deleted_at')
                ->where('status', 'ACTIVE'),
        ],

        'name' => [
            'required',
            'string',
            'max:255',
            'unique:permissions,name',
        ],

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
        'module_id.required' => 'يرجى اختيار وحدة الصلاحية.',
        'module_id.exists' => 'الوحدة المحددة غير موجودة أو غير نشطة.',
        'name.required' => 'الاسم البرمجي للصلاحية مطلوب.',
        'name.unique' => 'هذا الاسم البرمجي مستخدم بالفعل.',
        'display_name.required' => 'اسم الصلاحية المعروض مطلوب.',
    ]);

    $validated['guard_name'] = 'web';
    $validated['status'] = 'ACTIVE';

    Permission::create($validated);

    return redirect()
        ->route('admin.permissions.index')
        ->with('success', 'تمت إضافة الصلاحية بنجاح.');
}

}
