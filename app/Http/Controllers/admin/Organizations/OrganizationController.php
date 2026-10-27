<?php

namespace App\Http\Controllers\Admin\Organizations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Organization\OrganizationRequest;
use App\Models\Admin\Organizations\Organization;
use App\Services\Access\AccessContextService;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    protected $menuService;

    protected $accessContextService;

    public function __construct(
        MenuService $menuService,
        AccessContextService $accessContextService
    ) {
        $this->menuService = $menuService;
        $this->accessContextService = $accessContextService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = Organization::query()->paginate(10);

        return View('admin.organizations.index', ['data' => $data]);
    }

    public function ajax_organizations(Request $request)
    {
        $sortOrder = $request->sort ?? 'asc';
        $sortBy = $request->sortBy ?? 'organization_id';

        // الحقول المسموح الترتيب عليها
        $allowedSorts = [
            'organization_id',
            'name',
            'commercial_registration',
            'city',
            'status',
            'created_at',
        ];

        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'organization_id';
        }

        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query = Organization::query()
            ->orderBy($sortBy, $sortOrder);

        // البحث باسم المؤسسة
        if ($request->filled('searchName')) {

            $query->where('name', 'like', '%'.$request->searchName.'%');

        }

        // فلترة الحالة
        if ($request->filled('statusFilter')
            && $request->statusFilter != 'all') {

            $query->where('status', $request->statusFilter);

        }

        $data = $query->paginate(10);

        $data->appends($request->all());

        return view(
            'admin.organizations.ajax_search_organization',
            compact('data')
        );
    }

    public function registrationForm()
    {
        return view('admin.organizations.registration-form');
    }

    public function ajax_trashed_organizations(Request $request)
    {
        $sortOrder = $request->sort ?? 'desc';
        $sortBy = $request->sortBy ?? 'deleted_at';

        $allowedSorts = [
            'organization_id',
            'name',
            'deleted_at',
        ];

        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'deleted_at';
        }

        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query = Organization::onlyTrashed()
            ->orderBy($sortBy, $sortOrder);

        // البحث باسم المؤسسة
        if ($request->filled('searchName')) {
            $query->where('name', 'like', '%'.$request->searchName.'%');
        }

        $data = $query->paginate(10);

        $data->appends($request->all());

        $data->getCollection()->transform(function ($organization) {
            $organization->deleted_at_formatted = $organization->deleted_at
                ? $organization->deleted_at->format('d-m-Y h:i A')
                : '-';

            return $organization;
        });

        return view(
            'admin.organizations.ajax_trashed_organization',
            compact('data')
        );
    }

    // ============================================================
    public function trashed()
    {
        $data = Organization::onlyTrashed()
            ->orderByDesc('deleted_at')
            ->paginate(10);

        $data->getCollection()->transform(function ($organization) {
            $organization->deleted_at_formatted = $organization->deleted_at
                ? $organization->deleted_at->format('d-m-Y h:i A')
                : '-';

            return $organization;
        });

        return view('admin.organizations.trashed', compact('data'));
    }

    public function create()
    {

        return View('admin.organizations.create');
    }

    public function store(OrganizationRequest $request)
    {
        $datOrganization = $request->all();

        // رخصة البلدية (ملف واحد)

        if ($request->hasFile('municipality_license')) {

            $file = $request->file('municipality_license');

            $path = $file->store('uploads/licenses/municipality', 'public');

            $datOrganization['municipality_license'] = $path;
        }

        // رخصة الوساطة العقارية (ملف واحد)

        if ($request->hasFile('real_estate_broker_license')) {

            $file = $request->file('real_estate_broker_license');

            $path = $file->store('uploads/licenses/broker', 'public');

            $datOrganization['real_estate_broker_license'] = $path;
        }

        // شعار المؤسسة (logo)

        if ($request->hasFile('logo')) {

            $file = $request->file('logo');

            $path = $file->store('uploads/organizations/logo', 'public');

            $datOrganization['logo'] = $path;
        }
        // تنظيف البيانات غير الضرورية

        unset($datOrganization['documents_path']); // لو كان موجود من الفورم
        // تعطيل API وعدم إنشاء مفتاح عند إضافة المؤسسة
         $datOrganization['api_enabled'] = false;
         $datOrganization['api_key'] = null;
        // إنشاء المؤسسة
        // إنشاء المؤسسة وسياق الوصول كعملية واحدة

        $Organization = DB::transaction(function () use ($datOrganization) {

            $organization = Organization::create($datOrganization);

            $this->accessContextService
                ->createOrganizationContext($organization);

            return $organization;
        });

        return redirect()
            ->route('admin.organizations.index')
            ->with([
                'success' => "تمت إضافة المؤسسة ({$Organization->name}) بنجاح",
            ]);
    }

    public function deleteFile($id, $field)
    {
        $organization = Organization::findOrFail($id);

        // الحقول المسموح حذف ملفاتها
        $allowedFields = [
            'logo',
            'municipality_license',
            'real_estate_broker_license',
        ];

        if (! in_array($field, $allowedFields)) {

            return response()->json([
                'status' => 'error',
                'message' => 'الحقل غير مسموح.',
            ], 403);
        }

        $file = $organization->$field;

        if (! $file) {

            return response()->json([
                'status' => 'error',
                'message' => 'لا يوجد ملف للحذف.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | منع حذف مستندات الاعتماد أثناء وجود اشتراك فعال
        |--------------------------------------------------------------------------
        */

        if (in_array($field, [
            'municipality_license',
            'real_estate_broker_license',
        ])) {

            $hasActiveSubscription = $organization->subscriptions()
                ->where('status', 'active')
                ->exists();

            if ($hasActiveSubscription) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'لا يمكن حذف مستندات المؤسسة لوجود اشتراك فعال.',
                ], 422);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | حذف الملف من التخزين
        |--------------------------------------------------------------------------
        */

        if (Storage::disk('public')->exists($file)) {
            Storage::disk('public')->delete($file);
        }

        // تفريغ الحقل
        $organization->$field = null;

        /*
        |--------------------------------------------------------------------------
        | إذا كانت المؤسسة معتمدة وحُذف مستند اعتماد
        | تعاد إلى حالة المراجعة
        |--------------------------------------------------------------------------
        */

        if (
            $organization->status === 'active'
            && in_array($field, [
                'municipality_license',
                'real_estate_broker_license',
            ])
        ) {
            $organization->status = 'pending_approval';
        }

        $organization->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف الملف بنجاح.',
            'field' => $field,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id,$tab = 'info') 
    {

        $organization = Organization::findOrFail($id);

        $links = $this->menuService
            ->getLinks($organization);

        return view(
            'admin.organizations.show',
            compact(
                'organization',
                'links',
                'tab'
            )
        );

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Organization::findOrFail($id);
        if (empty($data)) {
            return redirect()->route('admin.organizations.index')->with(['error' => 'عفوا غير قادر للوصول للبيانات المطلوبة']);

        }

        return View('admin.organizations.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);

        if (! $organization) {

            return redirect()
                ->route('admin.organizations.index')
                ->with([
                    'error' => 'عفوا غير قادر للوصول للبيانات المطلوبة',
                ]);
        }

        $dataOrganization = $request->all();

        /*
        |--------------------------------------------------------------------------
        | التحقق من وجود اشتراك فعال
        |--------------------------------------------------------------------------
        */

        $hasActiveSubscription = $organization->subscriptions()
            ->where('status', 'active')
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | متغير لمعرفة هل تم تغيير أحد مستندات الاعتماد
        |--------------------------------------------------------------------------
        */

        $hasNewLicense = false;

        /*
        |--------------------------------------------------------------------------
        | تحديث الشعار
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            // حذف الشعار القديم
            if ($organization->logo) {

                $organization->deleteFile(
                    $organization->logo
                );
            }

            $path = $request->file('logo')
                ->store(
                    'uploads/organizations/logo',
                    'public'
                );

            $dataOrganization['logo'] = $path;
        }

        /*
        |--------------------------------------------------------------------------
        | تحديث رخصة البلدية
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('municipality_license')) {

            // منع استبدال المستند أثناء الاشتراك الفعال
            if (
                $hasActiveSubscription
                && $organization->municipality_license
            ) {

                return redirect()
                    ->back()
                    ->with([
                        'error' => 'لا يمكن استبدال رخصة البلدية لوجود اشتراك فعال للمؤسسة.',
                    ]);
            }

            // حذف المستند القديم
            if ($organization->municipality_license) {

                $organization->deleteFile(
                    $organization->municipality_license
                );
            }

            // تخزين المستند الجديد
            $path = $request->file('municipality_license')
                ->store(
                    'uploads/licenses/municipality',
                    'public'
                );

            $dataOrganization['municipality_license'] = $path;

            $hasNewLicense = true;
        }

        /*
        |--------------------------------------------------------------------------
        | تحديث رخصة الوساطة العقارية
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('real_estate_broker_license')) {

            // منع استبدال المستند أثناء الاشتراك الفعال
            if (
                $hasActiveSubscription
                && $organization->real_estate_broker_license
            ) {

                return redirect()
                    ->back()
                    ->with([
                        'error' => 'لا يمكن استبدال رخصة الوساطة العقارية لوجود اشتراك فعال للمؤسسة.',
                    ]);
            }

            // حذف المستند القديم
            if ($organization->real_estate_broker_license) {

                $organization->deleteFile(
                    $organization->real_estate_broker_license
                );
            }

            // تخزين المستند الجديد
            $path = $request->file('real_estate_broker_license')
                ->store(
                    'uploads/licenses/broker',
                    'public'
                );

            $dataOrganization['real_estate_broker_license'] = $path;

            $hasNewLicense = true;
        }

        /*
        |--------------------------------------------------------------------------
        | إعادة المؤسسة للمراجعة عند تغيير مستند اعتماد
        |--------------------------------------------------------------------------
        */

        if (
            $organization->status === 'active'
            && $hasNewLicense
            && ! $hasActiveSubscription
        ) {
            $dataOrganization['status'] = 'pending_approval';
        }

        /*
        |--------------------------------------------------------------------------
        | تحديث بيانات المؤسسة
        |--------------------------------------------------------------------------
        */

        $organization->update($dataOrganization);

        return redirect()
            ->route('admin.organizations.index')
            ->with([
                'success' => "تم تعديل المؤسسة ($organization->name) بنجاح",
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */

    //  الحذف المنطقي
    public function destroy(string $id)
    {
        $organization = Organization::findOrFail($id);

        $organization->delete();

        return response()->json([
            'success' => 'تم حذف المؤسسة بنجاح.',
        ]);
    }

    public function restore(string $id)
    {
        $organization = Organization::withTrashed()
            ->findOrFail($id);

        $organization->restore();

        return response()->json([
            'success' => 'تم استعادة المؤسسة بنجاح.',
        ]);
    }

    // الحذف النهائي
    public function forceDestroy(string $id)
    {
        $organization = Organization::withTrashed()
            ->findOrFail($id);

        $organization->deleteFiles();

        $organization->forceDelete();

        return response()->json([
            'success' => 'تم حذف المؤسسة نهائيًا.',
        ]);
    }
}
