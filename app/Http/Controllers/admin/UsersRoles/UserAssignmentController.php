<?php

namespace App\Http\Controllers\Admin\UsersRoles;

use App\Http\Controllers\Controller;
use App\Models\UserAssignment;
use App\Models\AccessContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
class UserAssignmentController extends Controller
{
   
public function index()
{
    $accessContexts = AccessContext::query()
        ->where('status', 'ACTIVE')
        ->orderBy('name')
        ->get();

    // إحصائيات تعيينات المستخدمين
    $totalAssignments = UserAssignment::count();

    $activeAssignments = UserAssignment::where(
        'status',
        'ACTIVE'
    )->count();

    $inactiveAssignments = UserAssignment::where(
        'status',
        'INACTIVE'
    )->count();

    $expiredAssignments = UserAssignment::where(
        'status',
        'EXPIRED'
    )->count();

    $data = UserAssignment::with([
        'user',
        'accessContext',
        'userRoles.role',
    ])
        ->latest('id')
        ->paginate(10);

    return view(
        'admin.users_roles.user_assignments.index',
        compact(
            'data',
            'accessContexts',
            'totalAssignments',
            'activeAssignments',
            'inactiveAssignments',
            'expiredAssignments'
        )
    );
}

public function stats()
{
    $query = UserAssignment::query();

    return response()->json([
        'stats' => [
            'total' => (clone $query)->count(),

            'active' => (clone $query)
                ->where('status', 'ACTIVE')
                ->count(),

            'inactive' => (clone $query)
                ->where('status', 'INACTIVE')
                ->count(),

            'expired' => (clone $query)
                ->where('status', 'EXPIRED')
                ->count(),
        ],
    ]);
}


    public function ajax_assignments(Request $request)
    {
        $sortBy = $request->input('sortBy', 'id');
        $sortOrder = $request->input('sort', 'desc');

        $allowedSorts = [
            'id',
            'status',
            'start_date',
            'end_date',
            'created_at',
        ];

        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'id';
        }

        if (!in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'desc';
        }

        $query = UserAssignment::query()->with([
            'user',
            'accessContext',
            'userRoles.role',
        ]);

        // البحث باسم المستخدم أو البريد الإلكتروني
        if ($request->filled('searchName')) {
            $search = trim($request->input('searchName'));

            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // فلترة حالة التعيين
        $statusFilter = $request->input('statusFilter');

        if (in_array($statusFilter, [
            'ACTIVE',
            'INACTIVE',
            'EXPIRED',
        ], true)) {
            $query->where('status', $statusFilter);
        }

        // فلترة سياق الوصول
        $contextFilter = $request->input('contextFilter');

        if (
            filled($contextFilter)
            && $contextFilter !== 'all'
            && filter_var($contextFilter, FILTER_VALIDATE_INT) !== false
        ) {
            $query->where(
                'access_context_id',
                (int) $contextFilter
            );
        }

        // ترتيب النتائج وترقيمها
        $data = $query
            ->orderBy($sortBy, $sortOrder)
            ->paginate(10)
            ->appends($request->query());

        // تجهيز بيانات العرض
        $data->getCollection()->transform(function ($assignment) {
            $assignment->user_name =
                $assignment->user?->name ?? 'مستخدم غير متاح';

            $assignment->user_email =
                $assignment->user?->email ?? '—';

            $assignment->context_name =
                $assignment->accessContext?->name ?? 'سياق غير متاح';

            $assignment->roles_summary = $assignment->userRoles
                ->filter(fn ($userRole) =>
                    $userRole->status === 'ACTIVE'
                    && $userRole->role
                    && $userRole->role->status === 'ACTIVE'
                )
                ->map(fn ($userRole) =>
                    $userRole->role->display_name
                    ?: $userRole->role->name
                )
                ->unique()
                ->implode('، ');

            $assignment->start_date_display =
                $assignment->start_date ?: '—';

            $assignment->end_date_display =
                $assignment->end_date ?: 'غير محددة';

            return $assignment;
        });

        return view(
            'admin.users_roles.user_assignments.ajax_assignments',
            compact('data')
        );
    }
    public function updateStatus(Request $request, $id)
{
    $validated = $request->validate([
        'status' => ['required', 'in:ACTIVE,INACTIVE'],
    ]);

    $assignment = UserAssignment::findOrFail($id);

    // منع تعديل حالة التعيين المنتهي
    if ($assignment->status === 'EXPIRED') {
        return response()->json([
            'success' => false,
            'message' => 'لا يمكن تغيير حالة تعيين منتهٍ.',
        ], 422);
    }

    // منع تنشيط تعيين انتهى تاريخه
    if (
        $validated['status'] === 'ACTIVE'
        && $assignment->end_date
        && Carbon::parse($assignment->end_date)->toDateString()
            < today()->toDateString()
    ) {
        return response()->json([
            'success' => false,
            'message' => 'انتهى تاريخ التعيين، ولا يمكن تنشيطه.',
        ], 422);
    }

    // لا حاجة إلى تحديث السجل إذا لم تتغير الحالة
    if ($assignment->status === $validated['status']) {
        return response()->json([
            'success' => true,
            'message' => 'حالة التعيين لم تتغير.',
            'status' => $assignment->status,
        ]);
    }

    $assignment->status = $validated['status'];
    $assignment->save();

    return response()->json([
        'success' => true,
        'message' => $assignment->status === 'ACTIVE'
            ? 'تم تنشيط التعيين بنجاح.'
            : 'تم إلغاء تنشيط التعيين بنجاح.',
        'status' => $assignment->status,
    ]);
}
public function edit($id)
{
    $data = UserAssignment::with([
        'user',
        'accessContext',
    ])->findOrFail($id);

    return view(
        'admin.users_roles.user_assignments.edit',
        compact('data')
    );
}

public function update(Request $request, $id)
{
    $assignment = UserAssignment::findOrFail($id);

    $validated = $request->validate([
        'end_date' => [
            'nullable',
            'date',
            'after_or_equal:today',
        ],
    ], [
        'end_date.date' => 'يرجى إدخال تاريخ نهاية صحيح.',
        'end_date.after_or_equal' => 'يجب أن يكون تاريخ النهاية اليوم أو بعده.',
    ]);

    $assignment->end_date = $validated['end_date'] ?? null;
    $assignment->save();

    return redirect()
        ->route('admin.user_assignments.index')
        ->with('success', 'تم تحديث تاريخ نهاية التعيين بنجاح.');
}
}
