<?php

namespace App\Http\Controllers\Admin\Packages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Packages\PackageRequest;
use App\Models\Admin\Packages\Package;
use App\Services\MenuService;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * عرض قائمة الباقات.
     */
    public function index()
    {
        $data = Package::query()->paginate(1);

        return view('admin.packages.index', [
            'data' => $data,
        ]);
    }

    /**
     * البحث والفلترة والترتيب عبر AJAX.
     */
    public function ajax_packages(Request $request)
    {
        // الأعمدة المسموح بالترتيب حسبها
        $allowedSortColumns = [
            'package_id',
            'name',
            'level',
            'base_price',
            'billing_cycle',
            'duration_days',
            'is_active',
            'sort_order',
        ];

        $sortBy = $request->input('sortBy', 'package_id');

        if (!in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'package_id';
        }

        // التحقق من اتجاه الترتيب
        $sortOrder = strtolower($request->input('sort', 'asc'));

        if (!in_array($sortOrder, ['asc', 'desc'], true)) {
            $sortOrder = 'asc';
        }

        $query = Package::query()
            ->orderBy($sortBy, $sortOrder);

        // البحث باسم الباقة
        if ($request->filled('search_name')) {
            $query->where(
                'name',
                'like',
                '%' . $request->search_name . '%'
            );
        }

        // الفلترة حسب حالة الباقة
        if (
            $request->has('is_active') &&
            $request->is_active !== null &&
            $request->is_active !== '' &&
            $request->is_active !== 'all'
        ) {
            $query->where('is_active', $request->is_active);
        }

        // الفلترة حسب دورة الفوترة
        if (
            $request->filled('billing_cycle') &&
            $request->billing_cycle !== 'all'
        ) {
            $query->where(
                'billing_cycle',
                $request->billing_cycle
            );
        }

        // ترقيم النتائج مع الاحتفاظ بالفلاتر
        $data = $query->paginate(4);

        $data->appends($request->query());

        return view(
            'admin.packages.ajax_serch_packages',
            ['data' => $data]
        );
    }

    /**
     * عرض نموذج إضافة باقة.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * حفظ باقة جديدة.
     */
    public function store(PackageRequest $request)
    {
        $dataPackage = $request->validated();

        $package = Package::create($dataPackage);

        return redirect()
            ->route('admin.packages.index')
            ->with(
                'success',
                "تمت إضافة الباقة ({$package->name}) بنجاح."
            );
    }

    /**
     * عرض تفاصيل الباقة.
     */
    public function show($id, $tab = 'info')
    {
        $package = Package::with([
            'subscriptions.organization',
        ])->findOrFail($id);

        $links = $this->menuService->getLinks($package);

        return view(
            'admin.packages.show',
            compact('package', 'links', 'tab')
        );
    }

    /**
     * عرض نموذج تعديل باقة.
     */
    public function edit($id)
    {
        $data = Package::findOrFail($id);

        return view('admin.packages.edit', [
            'data' => $data,
        ]);
    }

    /**
     * تحديث بيانات الباقة.
     */
    public function update(PackageRequest $request, $id)
    {
        $package = Package::findOrFail($id);

        $dataPackage = $request->validated();

        $package->update($dataPackage);

        return redirect()
            ->route('admin.packages.index')
            ->with(
                'success',
                "تم تعديل الباقة ({$package->name}) بنجاح."
            );
    }

    /**
     * حذف الباقة.
     */
    public function destroy($id)
    {
        return 'Delete Package';
    }
}
