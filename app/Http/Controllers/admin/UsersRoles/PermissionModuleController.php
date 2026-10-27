<?php

namespace App\Http\Controllers\Admin\UsersRoles;

use App\Http\Controllers\Controller;
use App\Models\PermissionModule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PermissionModuleController extends Controller
{
    /**
     * عرض صفحة إنشاء وحدة جديدة.
     */
    public function create(): View
    {
        return view('admin.users_roles.permission_modules.create');
    }

    /**
     * حفظ الوحدة الجديدة.
     */
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:100',
            'regex:/^[a-z][a-z0-9_]*$/',
            'unique:permission_modules,name',
        ],
        'display_name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string', 'max:1000'],
        'sort_order' => ['nullable', 'integer', 'min:1'],
    ], [
        'name.required' => 'الاسم البرمجي للوحدة مطلوب.',
        'name.regex' => 'الاسم البرمجي يجب أن يبدأ بحرف إنجليزي صغير، ويحتوي على أحرف إنجليزية صغيرة وأرقام وشرطة سفلية فقط.',
        'name.unique' => 'هذا الاسم البرمجي مستخدم مسبقًا.',
        'display_name.required' => 'اسم الوحدة المعروض مطلوب.',
        'sort_order.integer' => 'ترتيب الوحدة يجب أن يكون رقمًا صحيحًا.',
        'sort_order.min' => 'ترتيب الوحدة يجب أن يكون 1 أو أكبر.',
    ]);

    PermissionModule::create([
        'name' => $validated['name'],
        'display_name' => $validated['display_name'],
        'description' => $validated['description'] ?? null,
        'sort_order' => $validated['sort_order']
            ?? ((int) PermissionModule::max('sort_order') + 1),
        'status' => 'ACTIVE',
    ]);

    return redirect()
        ->route('admin.permissions.index')
        ->with('success', 'تمت إضافة الوحدة بنجاح.');
}

}
