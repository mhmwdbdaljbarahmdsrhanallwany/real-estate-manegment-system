<?php

namespace App\Http\Controllers\Admin\Subscriptions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subscriptions\SubscriptionRequest;
use App\Models\Admin\Invoices\Invoice;
use App\Models\Admin\Organizations\Organization;
use App\Models\Admin\Packages\Package;
use App\Models\Admin\Subscriptions\Subscription;
use App\Services\Invoices\InvoiceService;
use App\Services\MenuService;
use App\Services\Subscriptions\SubscriptionLifecycleService;
use App\Services\Subscriptions\SubscriptionStatusService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
class SubscriptionController extends Controller
{
    protected MenuService $menuService;
    protected SubscriptionLifecycleService $subscriptionLifecycleService;
    protected InvoiceService $invoiceService;
    public function __construct(
        MenuService $menuService,
        SubscriptionLifecycleService $subscriptionLifecycleService,
        InvoiceService $invoiceService
    ) {
        $this->menuService = $menuService;
        $this->subscriptionLifecycleService = $subscriptionLifecycleService;
        $this->invoiceService = $invoiceService;
    }

    public function index()
    {
        $data = Subscription::with([
            // تحميل معرف المؤسسة واسمها فقط.
            'organization:organization_id,name',

            // تحميل معرف الباقة واسمها فقط.
            'package:package_id,name',
        ])->paginate(10);

        // إرسال بيانات الاشتراكات إلى صفحة القائمة.
        return view('admin.subscriptions.index', [
            'data' => $data,
        ]);
    }

    public function ajax_search_subscription(Request $request)
    {
        // الأعمدة المسموح للمستخدم بالترتيب وفقها.
        // القائمة البيضاء تمنع تمرير أسماء أعمدة غير مسموح بها.
        $allowedSorts = [
            'subscription_id',
            'status',
            'subscription_type',
            'start_date',
            'end_date',
            'organization_name',
        ];

        // قراءة عمود الترتيب، واستخدام المعرف افتراضيًا.
        $sortBy = $request->input('sortBy', 'subscription_id');

        // قراءة اتجاه الترتيب، واستخدام التصاعدي افتراضيًا.
        $sortOrder = strtolower($request->input('sort', 'asc'));

        // إذا لم يكن العمود ضمن القائمة المسموحة، نستخدم العمود الافتراضي.
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'subscription_id';
        }

        // قبول asc أو desc فقط لمنع اتجاهات ترتيب غير صالحة.
        if (!in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'asc';
        }

        // إنشاء الاستعلام الأساسي مع تحميل علاقات المؤسسة والباقة.
        $query = Subscription::with([
            'organization:organization_id,name',
            'package:package_id,name',
        ]);

        // إذا كان الترتيب حسب اسم المؤسسة، نحتاج إلى ربط جدول المؤسسات.
        if ($sortBy === 'organization_name') {
            $query->join(
                'organizations',
                'organizations.organization_id',
                '=',
                'subscriptions.organization_id'
            )
            // ترتيب النتائج حسب اسم المؤسسة.
            ->orderBy('organizations.name', $sortOrder)

            // تحديد أعمدة الاشتراكات لتجنب تعارض أسماء الأعمدة.
            ->select('subscriptions.*');
        } else {
            // ترتيب النتائج حسب أحد أعمدة جدول الاشتراكات.
            $query->orderBy('subscriptions.' . $sortBy, $sortOrder);
        }

        // البحث باسم المؤسسة عند إدخال قيمة في حقل البحث.
        if ($request->filled('searchOrganization')) {
            $search = $request->input('searchOrganization');

            // whereHas يقيّد النتائج وفق علاقة المؤسسة.
            $query->whereHas('organization', function ($q) use ($search) {
                // البحث عن الاسم الذي يحتوي على النص المدخل.
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // تطبيق فلتر حالة الاشتراك إذا اختار المستخدم حالة محددة.
        if (
            $request->filled('statusFilter')
            && $request->statusFilter !== 'all'
        ) {
            $query->where('subscriptions.status', $request->statusFilter);
        }

        // تطبيق فلتر نوع الاشتراك: تجريبي أو مدفوع.
        if (
            $request->filled('subscriptionTypeFilter')
            && $request->subscriptionTypeFilter !== 'all'
        ) {
            $query->where(
                'subscriptions.subscription_type',
                $request->subscriptionTypeFilter
            );
        }

        // جلب النتائج مع تقسيمها إلى صفحات.
        $data = $query->paginate(10);

        // الاحتفاظ بقيم البحث والفلاتر والترتيب عند الانتقال بين الصفحات.
        $data->appends($request->query());

        // إعادة جزء HTML الخاص بنتائج البحث عبر AJAX.
        return view(
            'admin.subscriptions.ajax_search_subscription',
            ['data' => $data]
        );
    }

    public function create()
{
    $today = now()->toDateString();

    $organizations = Organization::query()
        ->whereNotExists(function ($query) use ($today) {
            $query->select(DB::raw(1))
                ->from('subscriptions')
                ->whereColumn(
                    'subscriptions.organization_id',
                    'organizations.organization_id'
                )
                ->where('subscriptions.subscription_type', 'paid')
                ->where('subscriptions.status', 'active')
                ->whereDate('subscriptions.end_date', '>=', $today);
        })
        ->where('status', 'active')
        ->get();

    $packages = Package::where('is_active', true)->get();

    return view('admin.subscriptions.create', compact(
        'organizations',
        'packages'
    ));
}

public function store(SubscriptionRequest $request)
{
    $validated = $request->validated();

    $result = DB::transaction(function () use ($validated) {
        $organization = Organization::where(
            'organization_id',
            $validated['organization_id']
        )->lockForUpdate()->firstOrFail();

        $package = Package::where(
            'package_id',
            $validated['package_id']
        )->where('is_active', 1)->firstOrFail();

        if ($validated['subscription_type'] === 'paid') {
            $paidSubscriptionExists = DB::table('subscriptions')
                ->where('organization_id', $organization->organization_id)
                ->where('subscription_type', 'paid')
                ->where('status', 'active')
                ->whereDate('end_date', '>=', now()->toDateString())
                ->exists();
        
            if ($paidSubscriptionExists) {
                throw ValidationException::withMessages([
                    'organization_id' =>
                        'لا يمكن إنشاء اشتراك مدفوع جديد؛ المؤسسة لديها اشتراك مدفوع نشط لم تنتهِ مدته.',
                ]);
            }
        }
        if ($validated['subscription_type'] === 'trial') {
            $existingSubscription = DB::table('subscriptions')
                ->where('organization_id', $organization->organization_id)
                ->where(function ($query) {
                    $query->where('subscription_type', 'trial')
                          ->orWhere('subscription_type', 'paid');
                })
                ->exists();
        
            if ($existingSubscription) {
                throw ValidationException::withMessages([
                    'subscription_type' =>
                        'لا يمكن إنشاء تجربة مجانية؛ للمؤسسة اشتراك تجريبي سابق أو اشتراك مدفوع.',
                ]);
            }
        }
        if ($validated['subscription_type'] === 'paid') {
            DB::table('subscriptions')
                ->where(
                    'organization_id',
                    $organization->organization_id
                )
                ->where('subscription_type', 'trial')
                ->where('status', 'active')
                ->update([
                    'status' => 'cancelled',
                    'activated_at' => null,
                    'updated_at' => now(),
                ]);
        }
        $trialDays = $validated['subscription_type'] === 'trial'
            ? (int) $validated['trial_period_days']
            : 0;
        $dates = $this->subscriptionLifecycleService->calculate(
            $package,
            $validated['subscription_type'],
            $trialDays
        );

        $isActive = $validated['status'] === 'active';
// حساب لقطة السعر مرة واحدة من الباقة الحالية.
$pricingSnapshot = $this->invoiceService
    ->createPricingSnapshot($package);
        $subscription = Subscription::create([
            'organization_id' => $organization->organization_id,
            'package_id' => $package->package_id,
            'subscription_type' => $validated['subscription_type'],
'billing_mode' => $validated['subscription_type'] === 'paid'
    ? $validated['billing_mode']
    : 'full',
            'trial_period_days' => $validated['subscription_type'] === 'trial'
                ? $trialDays
                : null,
            'trial_end_date' => $validated['subscription_type'] === 'trial'
                ? $dates['trial_end_date']
                : null,
            'start_date' => $dates['start_date'],
            'end_date' => $dates['end_date'],
            'status' => $validated['status'],
            'activated_at' => $isActive ? now() : null,
            'auto_renew' => $validated['auto_renew'],
            'notes' => $validated['notes'] ?? null,
             // تثبيت لقطة التسعير داخل الاشتراك.
    ...$pricingSnapshot,
        ]);
        // تفعيل سياقات الوصول المرتبطة بالمؤسسة
DB::table('access_contexts')
->where('organization_id', $organization->organization_id)
->whereNull('deleted_at')
->update([
    'status' => 'ACTIVE',
    'updated_by' => auth()->id(),
    'updated_at' => now(),
]);


       
$invoice = null;

if ($subscription->subscription_type === 'paid') {
    if ($validated['billing_mode'] === 'full') {
        // فاتورة واحدة تغطي كامل مدة الاشتراك.
        $invoice = $this->invoiceService->createForSubscription(
            $subscription,
            $package
        );
    } elseif ($validated['billing_mode'] === 'periodic') {
        // فاتورة الدورة الأولى فور إنشاء الاشتراك.
        $invoice = $this->invoiceService->createFirstPeriodicInvoice(
            $subscription,
            $package
        );
    }
}


        if ($isActive) {
            $this->activateOrganizationIfEligible(
                $organization,
                $subscription
            );
        }

        return [
            'invoice' => $invoice,
            'subscription_id' => $subscription->subscription_id,
            'subscription_type' => $subscription->subscription_type,
        ];
    });

    if ($result['invoice']) {
        return redirect()
            ->route('admin.invoices.show', [
                'id' => $result['invoice']->invoice_id,
            ])
            ->with('success', 'تم إنشاء الاشتراك المدفوع والفاتورة بنجاح.');
    }

    return redirect()
        ->route('admin.subscriptions.show', [
            'id' => $result['subscription_id'],
        ])
        ->with('success', 'تم إنشاء الاشتراك التجريبي بنجاح دون إصدار فاتورة.');
}


public function applyPenalty($id)
{
    try {
        $subscription = Subscription::query()
            ->where('subscription_id', $id)
            ->firstOrFail();

        // الغرامة تخص الاشتراك المدفوع فقط.
        if ($subscription->subscription_type !== 'paid') {
            throw new InvalidArgumentException(
                'لا يمكن تطبيق غرامة انتهاء الاشتراك على اشتراك تجريبي.'
            );
        }

        if (!$subscription->end_date) {
            throw new InvalidArgumentException(
                'لا يوجد تاريخ انتهاء للاشتراك.'
            );
        }

        // الخدمة تتولى المعاملة وقفل الاشتراك ومزامنة فاتورة الغرامة.
        $invoice = $this->invoiceService->syncPenaltyInvoice($subscription);

    } catch (InvalidArgumentException $exception) {
        return back()->with('error', $exception->getMessage());
    }

    if (!$invoice) {
        return redirect()
            ->route('admin.subscriptions.show', ['id' => $id])
            ->with('info', 'لا توجد غرامة مستحقة على الاشتراك حتى اليوم.');
    }

    if (data_get($invoice->calculation_details, 'penalty_frozen') === true) {
        $message = 'تمت تسوية فاتورة الغرامة نهائيًا، ولن تُحتسب غرامات جديدة لهذا الاشتراك.';
    } else {
        $message = 'تمت مزامنة فاتورة الغرامة بنجاح.';
    }

    return redirect()
        ->route('admin.invoices.show', ['id' => $invoice->invoice_id])
        ->with('success', $message);
}



    public function show(
        SubscriptionStatusService $statusService,
        $id,
        $tab = 'info'
    ) {
        $subscription = Subscription::with([
            'organization',
            'package',
            'invoices',
        ])->findOrFail($id);

        
        $status = [
            'trialDays' => $statusService->trialRemainingDays($subscription),
            'remainingDays' => $statusService->remainingDays($subscription),
            'penaltyDays' => $statusService->penaltyRemainingDays($subscription),
            'suspendDays' => $statusService->suspendRemainingDays($subscription),
            'state' => $statusService->currentState($subscription),
            'penaltyDate' => $statusService->penaltyDate($subscription),
            'suspendDate' => $statusService->suspendDate($subscription),
        ];
        $links = $this->menuService->getLinks($subscription);
        return view(
            'admin.subscriptions.show',
            compact('subscription', 'status', 'tab', 'links')
        );
    }
    public function edit($id)
    {
        // جلب الاشتراك باستخدام المعرّف الصحيح.
        $dataSubscription = Subscription::where(
            'subscription_id',
            $id
        )->firstOrFail();
    
        // منع فتح صفحة تعديل الاشتراك المدفوع.
        if ($dataSubscription->subscription_type !== 'trial') {
            return redirect()
                ->route('admin.subscriptions.index')
                ->with(
                    'error',
                    'لا يمكن تعديل الاشتراك المدفوع؛ التعديل متاح للاشتراكات التجريبية فقط.'
                );
        }
    
        $organizations = Organization::select(
            'organization_id',
            'name'
        )
            ->where(
                'organization_id',
                $dataSubscription->organization_id
            )
            ->get();
    
        $packages = Package::select(
            'package_id',
            'name',
            'duration_days',
            'grace_period_days',
            'suspend_after_days',
            'base_price',
            'discount_type',
            'discount_value',
            'tax_type',
            'tax_value'
        )
            ->where('is_active', 1)
            ->get();
    
        // تمرير المتغيرات التي قد تحتاجها صفحة التعديل.
        $subscriptionId = $dataSubscription->subscription_id;
    
        return view('admin.subscriptions.edit', compact(
            'dataSubscription',
            'subscriptionId',
            'organizations',
            'packages'
        ));
    }
    
public function update(Request $request, $id)
{
    DB::transaction(function () use ($request, $id) {
        $subscription = Subscription::where(
            'subscription_id',
            $id
        )->lockForUpdate()->firstOrFail();

        // منع تعديل الاشتراك المدفوع.
        if ($subscription->subscription_type !== 'trial') {
            throw ValidationException::withMessages([
                'subscription_type' =>
                    'لا يمكن تعديل الاشتراك المدفوع؛ التعديل متاح للاشتراكات التجريبية فقط.',
            ]);
        }
        
$trialEndDate = $subscription->trial_end_date
? Carbon::parse($subscription->trial_end_date)->startOfDay()
: null;

// منع التعديل إذا انتهت التجربة أو لم يكن لها تاريخ نهاية.
if (
$trialEndDate === null ||
Carbon::today()->greaterThanOrEqualTo($trialEndDate)
) {
throw ValidationException::withMessages([
    'trial_period_days' =>
        'لا يمكن تعديل مدة الفترة التجريبية بعد انتهائها.',
]);
}


        // التحقق من مدة التجربة فقط.
        $validated = $request->validate([
            'trial_period_days' => [
                'required',
                'integer',
                'min:1',
            ],
        ], [
            'trial_period_days.required' =>
                'يرجى تحديد مدة الفترة التجريبية.',
            'trial_period_days.integer' =>
                'يجب أن تكون مدة التجربة عددًا صحيحًا.',
            'trial_period_days.min' =>
                'يجب أن تكون مدة التجربة يومًا واحدًا على الأقل.',
        ]);

        $trialDays = (int) $validated['trial_period_days'];

        // استخدام الباقة الحالية للاشتراك، دون أخذها من الطلب.
        $package = Package::where(
            'package_id',
            $subscription->package_id
        )->firstOrFail();

        // بداية التجربة الأصلية.
        $trialStartDate = Carbon::parse(
            $subscription->created_at
        )->startOfDay()->format('Y-m-d');

        // حساب التواريخ عبر الخدمة.
        $dates = $this->subscriptionLifecycleService->calculate(
            $package,
            'trial',
            $trialDays,
            $trialStartDate
        );

        // تحديث الحقول المسموح بها فقط.
        $subscription->update([
            'trial_period_days' => $trialDays,
            'trial_end_date' => $dates['trial_end_date'],
            'start_date' => $dates['start_date'],
            'end_date' => $dates['end_date'],
        ]);
    });

    return redirect()
        ->route('admin.subscriptions.index')
        ->with('success', 'تم تحديث مدة الفترة التجريبية بنجاح.');
}

    public function destroy(string $id)
    {
        // البحث عن الاشتراك المطلوب حذفه.
        $subscription = Subscription::findOrFail($id);

        // تنفيذ الحذف المنطقي إذا كان الموديل يستخدم Soft Deletes.
        $subscription->delete();

        // العودة إلى القائمة مع رسالة نجاح.
        return redirect()
            ->route('admin.subscriptions.index')
            ->with('success', 'تم حذف الاشتراك بنجاح.');
    }

    private function activateOrganizationIfEligible(
        Organization $organization,
        Subscription $subscription
    ): void {

        // إذا لم يكن الاشتراك نشطًا، لا ننفذ أي عملية تفعيل.
        if ($subscription->status !== 'active') {
            return;
        }
        if ($organization->status !== 'active') {
            return;
        }
        $organization->api_key ??= Str::random(64);

        // السماح للمؤسسة باستخدام API.
        $organization->api_enabled = true;

        // حفظ مفتاح API وحالة التفعيل في قاعدة البيانات.
        $organization->save();
    }
}
