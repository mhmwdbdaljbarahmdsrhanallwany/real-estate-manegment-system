<?php

namespace App\Http\Controllers\Admin\UsersRoles;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\AccessContext;
use App\Models\UserAssignment;
use App\Models\UserRole;
use App\Models\Role;
use App\Services\MenuService;

class UserController extends Controller
{
    
    protected $menuService;


    public function __construct(
        MenuService $menuService,
    ) {
        $this->menuService = $menuService;
    }
    public function index()
    {
        $data = User::with([
            'assignments.accessContext',
            'assignments.userRoles.role',
        ])
            ->latest('id')
            ->paginate(10);
    
        // تجهيز أسماء سياقات الوصول والأدوار للجدول
        $data->getCollection()->transform(function ($user) {
    
            $assignments = $user->assignments;
    
            $user->assignments_summary = $assignments
                ->map(fn ($assignment) => $assignment->accessContext?->name)
                ->filter()
                ->unique()
                ->implode('، ');
    
            $user->roles_summary = $assignments
                ->flatMap(fn ($assignment) => $assignment->userRoles
                    ->where('status', 'ACTIVE')
                    ->map(fn ($userRole) => $userRole->role?->display_name
                        ?: $userRole->role?->name
                    )
                )
                ->filter()
                ->unique()
                ->implode('، ');
    
            return $user;
        });
    
        // سياقات الوصول النشطة لعرضها في قائمة الفلترة
        $accessContexts = AccessContext::query()
            ->where('status', 'ACTIVE')
            ->orderBy('name')
            ->get();
    
        return view(
            'admin.users_roles.users.index',
            compact('data', 'accessContexts')
        );
    }





public function ajax_users(Request $request)
{
    // الترتيب المسموح به
    $sortBy = $request->input('sortBy', 'id');
    $sortOrder = $request->input('sort', 'asc');

    $allowedSorts = [
        'id',
        'name',
        'email',
        'created_at',
    ];

    if (!in_array($sortBy, $allowedSorts, true)) {
        $sortBy = 'id';
    }

    if (!in_array($sortOrder, ['asc', 'desc'], true)) {
        $sortOrder = 'asc';
    }

    // المستخدمون مع سياقات الوصول والأدوار المسندة
   /*
|--------------------------------------------------------------------------
| تحديد نوع المستخدمين المطلوب عرضهم
|--------------------------------------------------------------------------
*/
$userType = $request->input('userType', 'active');

$query = match ($userType) {
    'deleted' => User::onlyTrashed(),
    default   => User::query(),
};

$query->with([
    'assignments.accessContext',
    'assignments.userRoles.role',
]);

    /*
    |--------------------------------------------------------------------------
    | البحث باسم المستخدم أو البريد الإلكتروني
    |--------------------------------------------------------------------------
    */
    if ($request->filled('searchName')) {
        $search = trim($request->input('searchName'));

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }



/*
|--------------------------------------------------------------------------
| فلترة سياق الوصول
|--------------------------------------------------------------------------
*/
$contextFilter = $request->input('contextFilter');

if (
    filled($contextFilter)
    && $contextFilter !== 'all'
    && filter_var($contextFilter, FILTER_VALIDATE_INT) !== false
) {
    $query->whereHas('assignments', function ($q) use ($contextFilter) {
        $q->where('access_context_id', (int) $contextFilter);
    });
}

    /*
    |--------------------------------------------------------------------------
    | فلترة الدور
    |--------------------------------------------------------------------------
    */
    if (
        $request->filled('roleFilter')
        && $request->input('roleFilter') !== 'all'
    ) {
        $roleFilter = $request->input('roleFilter');

        $query->whereHas(
            'assignments.userRoles',
            function ($q) use ($roleFilter) {
                $q->where('status', 'ACTIVE')
                    ->whereHas('role', function ($roleQuery) use ($roleFilter) {
                        $roleQuery->where('name', $roleFilter)
                            ->where('status', 'ACTIVE');
                    });
            }
        );
    }

    // ترتيب النتائج وترقيمها
    $data = $query
        ->orderBy($sortBy, $sortOrder)
        ->paginate(10)
        ->appends($request->query());

    /*
    |--------------------------------------------------------------------------
    | تجهيز الأعمدة المحسوبة للجدول
    |--------------------------------------------------------------------------
    */
    $data->getCollection()->transform(function ($user) {
        $assignments = $user->assignments;

        // أسماء سياقات الوصول
        $user->assignments_summary = $assignments
            ->map(function ($assignment) {
                return $assignment->accessContext?->name;
            })
            ->filter()
            ->unique()
            ->implode('، ');

        // أسماء الأدوار المسندة بنشاط
        $user->roles_summary = $assignments
            ->flatMap(function ($assignment) {
                return $assignment->userRoles
                    ->where('status', 'ACTIVE')
                    ->filter(function ($userRole) {
                        return $userRole->role
                            && $userRole->role->status === 'ACTIVE';
                    })
                    ->map(function ($userRole) {
                        return $userRole->role->display_name
                            ?: $userRole->role->name;
                    });
            })
            ->filter()
            ->unique()
            ->implode('، ');

        return $user;
    });

    return view(
        'admin.users_roles.users.ajax_users',
        compact('data')
    );
}

public function create()
{
    $accessContexts = \App\Models\AccessContext::query()
        ->where('status', 'ACTIVE')
        ->orderBy('name')
        ->get();

    $roles = \App\Models\Role::query()
        ->where('guard_name', 'web')
        ->where('status', 'ACTIVE')
        ->orderBy('id')
        ->get();

    return view(
        'admin.users_roles.users.create',
        compact('accessContexts', 'roles')
    );
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users,email',
        ],

        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
        ],

        'access_context_id' => [
            'required',
            'integer',
            Rule::exists('access_contexts', 'id')
                ->where('status', 'ACTIVE'),
        ],

        'role_id' => [
            'nullable',
            'integer',
            Rule::exists('roles', 'id')
                ->where('status', 'ACTIVE')
                ->where('guard_name', 'web')
                ->whereNull('deleted_at'),
        ],
        'end_date' => [
    'nullable',
    'date',
    'after:today',
],
    ], [
        'name.required' => 'اسم المستخدم مطلوب.',
        'email.required' => 'البريد الإلكتروني مطلوب.',
        'email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
        'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
        'password.required' => 'كلمة المرور مطلوبة.',
        'password.min' => 'يجب ألا تقل كلمة المرور عن 8 أحرف.',
        'password.confirmed' => 'تأكيد كلمة المرور غير مطابق.',
        'access_context_id.required' => 'يرجى تحديد سياق الوصول.',
        'access_context_id.exists' => 'سياق الوصول غير موجود أو غير نشط.',
        'role_id.exists' => 'الدور المحدد غير موجود أو غير نشط.',
        'end_date.date' => 'يرجى إدخال تاريخ نهاية تعيين صحيح.',
'end_date.after' => 'تاريخ نهاية التعيين يجب أن يكون بعد اليوم.',
    ]);

    DB::transaction(function () use ($validated) {

        // 1. إنشاء حساب المستخدم
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->save();
       
        // 2. إنشاء تعيين المستخدم في سياق الوصول
        $assignment = new UserAssignment();
        $assignment->user_id = $user->id;
        $assignment->access_context_id = $validated['access_context_id'];
        $assignment->status = 'ACTIVE';
          // تواريخ التعيين
          $assignment->start_date = now()->toDateString();
          $assignment->end_date = $validated['end_date'] ?? null;
        $assignment->created_by = auth()->id();
        $assignment->save();

        // 3. إسناد الدور عند اختياره فقط
        if (!empty($validated['role_id'])) {
            $userRole = new UserRole();
            $userRole->user_assignment_id = $assignment->id;
            $userRole->role_id = $validated['role_id'];
            $userRole->status = 'ACTIVE';
            $userRole->created_by = auth()->id();
            $userRole->save();
        }
    });

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'تم إنشاء المستخدم بنجاح.');
}

public function show($id, $tab = 'info')
{
    $user = User::with([
        'assignments.accessContext',
        'assignments.userRoles.role.permissions',
    ])->findOrFail($id);

    $links = $this->menuService->getLinks($user);

    $assignments = $user->assignments;

    $contextsCount = $assignments
        ->pluck('accessContext')
        ->filter()
        ->unique('id')
        ->count();

    $rolesCount = $assignments
        ->flatMap(fn ($assignment) => $assignment->userRoles)
        ->filter(fn ($userRole) =>
            $userRole->status === 'ACTIVE'
            && $userRole->role
            && $userRole->role->status === 'ACTIVE'
        )
        ->unique('role_id')
        ->count();

    return view(
        'admin.users_roles.users.show',
        compact(
            'user',
            'links',
            'tab',
            'assignments',
            'contextsCount',
            'rolesCount'
        )
    );
}


public function edit($id)
{
    $user = User::with([
        'assignments.accessContext',
        'assignments.userRoles.role',
    ])->findOrFail($id);

    return view('admin.users_roles.users.edit', compact('user'));
}


public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'email',
            'max:255',
            'unique:users,email,' . $user->id,
        ],
        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
    ]);

    $user->name = $validated['name'];
    $user->email = $validated['email'];

    if (!empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    $user->save();

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
}
public function delete($id)
{
    $user = User::findOrFail($id);

    // منع حذف حساب المستخدم الذي سجّل الدخول حاليًا
    if ($user->id === auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'لا يمكنك حذف حسابك الحالي.',
        ], 422);
    }

    $user->delete();

    return response()->json([
        'success' => true,
        'message' => 'تم حذف المستخدم بنجاح.',
    ]);
}
public function restore($id)
{
    $user = User::onlyTrashed()->findOrFail($id);

    $user->restore();

    return response()->json([
        'success' => true,
        'message' => 'تمت استعادة المستخدم بنجاح.',
    ]);
}
}
