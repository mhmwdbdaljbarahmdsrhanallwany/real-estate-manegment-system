<?php

namespace App\Http\Controllers\Admin\Cashboxes;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cashboxes\Cashbox;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CashboxController extends Controller
{
    public function notices()
{
    return response()->json([
        'html' => view(
            'admin.cashboxes.partials.notices',
            $this->getCashboxNoticeData()
        )->render(),
    ]);
}
    private function getCashboxNoticeData(): array
{
    return [
        'inactiveCashboxesCount' => Cashbox::where(
            'status',
            'inactive'
        )->count(),

        'activeMainCashboxesCount' => Cashbox::where(
            'is_main',
            true
        )->where(
            'status',
            'active'
        )->count(),
    ];
}
    /**
     * عرض صفحة إدارة الصناديق.
     */
   
    public function index()
    {
        $cashboxes = Cashbox::query()
            ->orderByDesc('is_main')
            ->orderBy('name')
            ->paginate(10);
            $noticeData = $this->getCashboxNoticeData();
        // عدد الصناديق غير النشطة في قاعدة البيانات.
       
    
        // عدد الصناديق الرئيسية النشطة.
        $activeMainCashboxesCount = Cashbox::where('is_main', true)
            ->where('status', 'active')
            ->count();
            return view(
                'admin.cashboxes.index',
                array_merge(
                    compact('cashboxes'),
                    $noticeData
                )
            );
    }

    /**
     * البحث عن الصناديق عبر AJAX.
     */
    public function ajaxSearch(Request $request)
    {
        $query = Cashbox::query();

        // البحث باسم الصندوق أو الكود.
        if ($request->filled('searchName')) {
            $search = trim($request->input('searchName'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // التصفية حسب الحالة.
        $statusFilter = $request->input('statusFilter', 'all');

        if ($statusFilter !== 'all' && $statusFilter !== '') {
            $query->where('status', $statusFilter);
        }

        // أعمدة الترتيب المسموح بها.
        $allowedSorts = [
            'cashboxe_id',
            'name',
            'code',
            'current_balance',
            'created_at',
        ];

        $sortBy = $request->input('sortBy', 'cashboxe_id');
        $sort = strtolower($request->input('sort', 'desc'));

        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'cashboxe_id';
        }

        if (!in_array($sort, ['asc', 'desc'], true)) {
            $sort = 'desc';
        }

        $cashboxes = $query
            ->orderBy($sortBy, $sort)
            ->paginate(10)
            ->withQueryString();

        return view('admin.cashboxes.ajax_search', compact('cashboxes'));
    }
    
/**
 * تغيير حالة الصندوق المالي عبر AJAX.
 */
public function changeStatus(Request $request)
{
    $validated = $request->validate([
        'cashboxe_id' => [
            'required',
            'integer',
            'exists:cashboxes,cashboxe_id',
        ],
        'status' => [
            'required',
            Rule::in(['active', 'inactive']),
        ],
    ], [
        'cashboxe_id.required' => 'لم يتم تحديد الصندوق المالي.',
        'cashboxe_id.exists' => 'الصندوق المالي المطلوب غير موجود.',
        'status.required' => 'يرجى تحديد حالة الصندوق.',
        'status.in' => 'الحالة المختارة غير صحيحة.',
    ]);

    $cashbox = Cashbox::findOrFail($validated['cashboxe_id']);

    // إذا كانت الحالة الحالية مطابقة للحالة المطلوبة.
    if ($cashbox->status === $validated['status']) {
        return response()->json([
            'status' => true,
            'message' => $validated['status'] === 'active'
                ? 'الصندوق نشط بالفعل.'
                : 'الصندوق غير نشط بالفعل.',
            'cashbox' => $cashbox,
        ]);
    }

    // تحديث الحالة فقط، دون المساس بالأرصدة أو الصندوق الرئيسي.
    $cashbox->update([
        'status' => $validated['status'],
    ]);

    $messages = [
        'active' => 'تم تفعيل الصندوق المالي بنجاح.',
        'inactive' => 'تم تعطيل الصندوق المالي بنجاح.',
    ];

    return response()->json([
        'status' => true,
        'message' => $messages[$validated['status']],
        'cashbox' => $cashbox->fresh(),
    ]);
}


    /**
     * عرض صفحة إضافة صندوق جديد.
     */
    public function create()
    {
        return view('admin.cashboxes.create');
    }

    /**
     * حفظ صندوق جديد.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'code' => [
                'required',
                'string',
                'max:255',
                'unique:cashboxes,code',
            ],
            'currency' => [
                'required',
                Rule::in(['USD', 'YER', 'SAR']),
            ],
            'opening_balance' => [
                'required',
                'numeric',
                'min:0',
            ],
            'is_main' => [
                'nullable',
                'boolean',
            ],
        ], [
            'name.required' => 'يرجى إدخال اسم الصندوق.',
            'name.string' => 'اسم الصندوق يجب أن يكون نصًا صحيحًا.',
            'name.max' => 'اسم الصندوق يجب ألا يتجاوز 255 حرفًا.',

            'code.required' => 'يرجى إدخال كود الصندوق.',
            'code.string' => 'كود الصندوق يجب أن يكون نصًا صحيحًا.',
            'code.max' => 'كود الصندوق يجب ألا يتجاوز 255 حرفًا.',
            'code.unique' => 'كود الصندوق مستخدم بالفعل.',

            'currency.required' => 'يرجى اختيار عملة الصندوق.',
            'currency.in' => 'العملة المختارة غير مدعومة.',

            'opening_balance.required' => 'يرجى إدخال الرصيد الافتتاحي.',
            'opening_balance.numeric' => 'الرصيد الافتتاحي يجب أن يكون رقمًا.',
            'opening_balance.min' => 'الرصيد الافتتاحي لا يمكن أن يكون سالبًا.',

            'is_main.boolean' => 'قيمة الصندوق الرئيسي غير صحيحة.',
        ]);

        $isMain = $request->boolean('is_main');

        // منع تعيين صندوق رئيسي جديد إذا كان هناك صندوق رئيسي بالفعل.
        if ($isMain && Cashbox::where('is_main', true)->exists()) {
            $errors = [
                'is_main' => [
                    'يوجد صندوق رئيسي بالفعل، لا يمكن تعيين صندوق رئيسي آخر.',
                ],
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'تعذر إنشاء الصندوق.',
                    'errors' => $errors,
                ], 422);
            }

            return back()
                ->withErrors($errors)
                ->withInput();
        }

        $openingBalance = (float) $validated['opening_balance'];

        $cashbox = Cashbox::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'currency' => $validated['currency'],
            'opening_balance' => $openingBalance,
            'current_balance' => $openingBalance,
            'is_main' => $isMain,
            'status' => 'active',
        ]);

        // استجابة AJAX.
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'تم إنشاء الصندوق بنجاح.',
                'cashbox' => $cashbox,
                'redirect' => route('admin.cashboxes.index'),
            ], 201);
        }

        // استجابة النموذج التقليدي.
        return redirect()
            ->route('admin.cashboxes.index')
            ->with('success', 'تم إنشاء الصندوق بنجاح.');
    }

    /**
     * عرض صفحة تعديل صندوق محدد.
     */
    public function edit($cashboxe)
    {
        $cashbox = Cashbox::findOrFail($cashboxe);

        return view('admin.cashboxes.edit', compact('cashbox'));
    }

    /**
     * تحديث بيانات الصندوق.
     *
     * لا يتم تعديل الأرصدة من هذه الدالة.
     */
    public function update(Request $request, $cashboxe)
    {
        $cashbox = Cashbox::findOrFail($cashboxe);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cashboxes', 'code')->ignore(
                    $cashbox->cashboxe_id,
                    'cashboxe_id'
                ),
            ],
            'currency' => [
                'required',
                Rule::in(['USD', 'YER', 'SAR']),
            ],
            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
            'is_main' => [
                'nullable',
                'boolean',
            ],
        ], [
            'name.required' => 'يرجى إدخال اسم الصندوق.',
            'name.string' => 'اسم الصندوق يجب أن يكون نصًا صحيحًا.',
            'name.max' => 'اسم الصندوق يجب ألا يتجاوز 255 حرفًا.',

            'code.required' => 'يرجى إدخال كود الصندوق.',
            'code.string' => 'كود الصندوق يجب أن يكون نصًا صحيحًا.',
            'code.max' => 'كود الصندوق يجب ألا يتجاوز 255 حرفًا.',
            'code.unique' => 'كود الصندوق مستخدم لدى صندوق آخر.',

            'currency.required' => 'يرجى اختيار عملة الصندوق.',
            'currency.in' => 'العملة المختارة غير مدعومة.',

            'status.required' => 'يرجى تحديد حالة الصندوق.',
            'status.in' => 'حالة الصندوق المختارة غير صحيحة.',

            'is_main.boolean' => 'قيمة الصندوق الرئيسي غير صحيحة.',
        ]);

        $isMain = $request->boolean('is_main');

        // إذا تم اختيار الصندوق الحالي كرئيسي، نستثنيه من البحث.
        // نمنع وجود صندوق رئيسي آخر.
        if (
            $isMain &&
            Cashbox::where('is_main', true)
                ->where('cashboxe_id', '!=', $cashbox->cashboxe_id)
                ->exists()
        ) {
            $errors = [
                'is_main' => [
                    'يوجد صندوق رئيسي آخر بالفعل.',
                ],
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'تعذر تحديث الصندوق.',
                    'errors' => $errors,
                ], 422);
            }

            return back()
                ->withErrors($errors)
                ->withInput();
        }

        // تحديث البيانات الأساسية فقط دون تغيير الأرصدة.
        $cashbox->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'currency' => $validated['currency'],
            'status' => $validated['status'],
            'is_main' => $isMain,
        ]);

        // استجابة AJAX.
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'تم تحديث بيانات الصندوق بنجاح.',
                'cashbox' => $cashbox->fresh(),
                'redirect' => route('admin.cashboxes.index'),
            ]);
        }

        // استجابة النموذج التقليدي.
        return redirect()
            ->route('admin.cashboxes.index')
            ->with('success', 'تم تحديث بيانات الصندوق بنجاح.');
    }
}
