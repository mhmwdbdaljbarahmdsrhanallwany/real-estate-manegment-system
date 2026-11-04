<?php

namespace App\Http\Livewire\FrontOrganizations\Owner;


use App\Models\FrontOrganizations\Owner\OwnerModels;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class Owner extends Component
{




    use WithPagination;
    use WithFileUploads;

    // ==========================================
    //  متغيرات البحث والتصفية
    // ==========================================
    public $search = '';
    public $status = '';
    public $nationality = '';
    public $city = '';
    public $dateFrom = '';
    public $dateTo = ''; // 
    public $perPage = 10; // عدد الصفوف المعروضه في البجناشن
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $viewMode = 'table'; // table | grid

    // ==========================================
    //  متغيرات الإحصائيات
    // ==========================================
    public $totalOwners = 0;
    public $activeOwners = 0;
    public $inactiveOwners = 0;
    public $totalBuildings = 0;
    public $totalContracts = 0;
    public $totalPayments = 0;
    public $totalRevenue = 0;
    public $blacklistedOwners = 0;

    // ==========================================
    // متغيرات الحذف والحظر
    // ==========================================
    public $ownerIdToDelete = null;
    public $confirmingDelete = false;
    public $ownerStats = null;


    // ========================================
    

    // ==========================================
    //  متغيرات التصدير
    // ==========================================
    public $exportFormat = 'pdf';
    public $exportColumns = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'nationality' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 15],
    ];

    protected $listeners = [
        'refreshOwners' => '$refresh',
        'deleteConfirmed' => 'deleteOwner',
        'blacklistConfirmed' => 'blacklistOwner',
        'exportOwners' => 'export',
    ];

    // ==========================================
    //  قواعد التحقق
    // ==========================================
    protected function rules()
    {
        return [
            'search' => 'nullable|string|max:100',
            'status' => 'nullable|in:active,inactive,all',
            'nationality' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
            'dateFrom' => 'nullable|date',
            'dateTo' => 'nullable|date|after_or_equal:dateFrom',
            'perPage' => 'nullable|integer|in:10,15,25,50,100',
        ];
    }

    // ==========================================
    //  تحميل البيانات
    // ==========================================
    public function mount()
    {
        $this->loadStats();
        $this->exportColumns = [
            'full_name',
            'phone',
            'email',
            'nationality',
            'city',
            'total_buildings',
            'total_revenue',
            'status'
        ];
    }
    // من تاريخ 
    public function updatedDateFrom()
    {
        $this->resetPage();
    }
    // الى تاريخ
    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function loadStats()
    {
        if (!Auth::check()) {
            abort(403, 'يجب تسجيل الدخول أولاً');
        }
        $organization_id = Auth::user()->organization_id;
        // جلب جميع الملاك
        $this->totalOwners = OwnerModels::where('organization_id', $organization_id)->count();
        // جلب الملاك النشطين
        $this->activeOwners = OwnerModels::where('organization_id', $organization_id)
            ->where('is_active', true)
            ->count();
        // جلب الملاك الغير نشطين
        $this->inactiveOwners = OwnerModels::where('organization_id', $organization_id)
            ->where('is_active', false)
            ->count();
        // جلب الملاك المحظورين
        $this->blacklistedOwners = OwnerModels::where('organization_id', $organization_id)
            ->where('is_blacklisted', true)
            ->count();


        $this->ownerStats = [
            'total' => $this->totalOwners,
            'active' => $this->activeOwners,
            'inactive' => $this->inactiveOwners,
            'blacklisted' => $this->blacklistedOwners,
        ];
        // // إحصائيات إضافية
        // $this->totalBuildings = Building::where('organization_id', $organization_id)->count();
        // $this->totalContracts = Contract::where('organization_id', $organization_id)->count();

        // $this->totalPayments = Payment::whereHas('contract', function($query) use ($organization_id) {
        //     $query->where('organization_id', $organization_id);
        // })->count();

        // $this->totalRevenue = Payment::whereHas('contract', function($query) use ($organization_id) {
        //     $query->where('organization_id', $organization_id);
        // })->sum('amount');
    }

    // ==========================================
    //  الحصول على الملاك مع التصفية
    // ==========================================
    public function getOwnersProperty()
    {
        $organization_id = Auth::user()->organization_id;

        return OwnerModels::where('organization_id', $organization_id)
            ->when($this->search, function ($query) {
                $search = '%' . trim($this->search) . '%';

                return $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', $search)
                        ->orWhere('phone', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('id_number', 'like', $search)
                        ->orWhere('nationality', 'like', $search)

                        ->orWhere('address', 'like', $search)
                        ->orWhere('bank_account_name', 'like', $search);
                });
            })
            ->when($this->status && $this->status !== 'all', function ($query) {

                if ($this->status === 'active') {
                    return $query->where('is_active', true);
                }

                if ($this->status === 'inactive') {
                    return $query->where('is_active', false);
                }

                if ($this->status === 'blacklisted') {
                    return $query->where('is_blacklisted', true);
                }

                return $query;
            })
            ->when($this->nationality, function ($query) {
                return $query->where(
                    'nationality',
                    'like',
                    '%' . $this->nationality . '%'
                );
            })
            ->when($this->city, function ($query) {
                return $query->where(
                    'city',
                    'like',
                    '%' . $this->city . '%'
                );
            })
            ->when($this->dateFrom, function ($query) {
                return $query->whereDate(
                    'created_at',
                    '>=',
                    $this->dateFrom
                );
            })
            ->when($this->dateTo, function ($query) {
                return $query->whereDate(
                    'created_at',
                    '<=',
                    $this->dateTo
                );
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }
    // ==========================================
    // الحصول على إحصائيات الملاك
    // ==========================================
    public function getOwnerStatsProperty()
    {
        $organization_id = Auth::user()->organization_id;

        return [
            'total' => $this->totalOwners,
            'active' => $this->activeOwners,
            'inactive' => $this->inactiveOwners,
            'blacklisted' => $this->blacklistedOwners,
            'with_buildings' => 0,
            'without_buildings' => 0,
            'with_contracts' => 0,
            'without_contracts' => 0,

        ];
    }

    // ==========================================
    //  الحصول على الجنسيات المتاحة
    // ==========================================
    public function getNationalitiesProperty()
    {
        $organization_id = Auth::user()->organization_id;
        return OwnerModels::where('organization_id', $organization_id)
            ->select('nationality')
            ->distinct()
            ->whereNotNull('nationality')
            ->pluck('nationality')
            ->toArray();
    }

    // ==========================================
    //  الحصول على المدن المتاحة
    // ==========================================
    // public function getCitiesProperty()
    // {
    //     $organization_id = Auth::user()->organization_id;
    //     return OwnerModels::where('organization_id', $organization_id)
    //         ->select('city')
    //         ->distinct()
    //         ->whereNotNull('city')
    //         ->pluck('city')
    //         ->toArray();
    // }

    // ==========================================
    // تغيير الترتيب
    // ==========================================
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // ==========================================
    //  حذف مالك
    // ==========================================
    public function confirmDelete($id)
    {
        $this->ownerIdToDelete = $id;

        $this->dispatch('show-delete-modal');
    }

    public function deleteOwner()
    {
        $owner = OwnerModels::find($this->ownerIdToDelete);

        // if ($owner) {
        //     // التحقق من وجود عقود مرتبطة
        //     if ($owner->contracts()->exists()) {
        //         $this->dispatch('toast', [
        //             'type' => 'error',
        //             'message' => 'لا يمكن حذف المالك لأنه مرتبط بعقود نشطة'
        //         ]);
        //         return;
        //     }

        //     // التحقق من وجود مباني مرتبطة
        //     if ($owner->buildings()->exists()) {
        //         $this->dispatch('toast', [
        //             'type' => 'error',
        //             'message' => 'لا يمكن حذف المالك لأنه مرتبط بمباني'
        //         ]);
        //         return;
        //     }

        //     $owner->delete();

        //     $this->dispatch('toast', [
        //         'type' => 'success',
        //         'message' => 'تم حذف المالك بنجاح'
        //     ]);

        //     $this->loadStats();
        // }
        $owner->delete();

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'تم حذف المالك بنجاح'
        ]);

        $this->loadStats();

        $this->confirmingDelete = false;
        $this->ownerIdToDelete = null;
    }

    // ==========================================
    // حظر مالك
    // ==========================================
    public $ownerIdToBlacklist = null;// تخزين رقم المالك الذي سيتم حظره
    public $blacklistReason = '';//حفظ سبب الحظر
    public $confirmingBlacklist = false;//متغير للتحكم في ظهور نافذة التأكيد

    // هذه الدالة تعمل عند الضغط على زر الحظر
    public function confirmBlacklist($id)
    {
        // تخزين رقم الملك الذي سيتم حظره
        $this->ownerIdToBlacklist = $id;
        // يمسح أي سبب قديم.
        $this->blacklistReason = '';
        // فتح نافذه سبب الحظر 
        $this->confirmingBlacklist = true;
        // ارسال حدث الى جافا سكربت لفتح المودل
        $this->dispatch('show-blacklist-modal');
    }

    // تنفيذ الحظر
    public function blacklistOwner()
    {
        // تخزين المالك المراد حضره
        $owner = OwnerModels::find($this->ownerIdToBlacklist);
        // التأكد أن المالك موجود إذا وجد المالك نفذ الحظر
        if ($owner) {
            // تفعيل الحظر
            $owner->is_blacklisted = true;
            // حفظ سبب الحظر
            $owner->blacklist_reason = $this->blacklistReason;
            // حفظ تاريخ الحظر
            $owner->blacklisted_at = Carbon::now();
            // حفظ التغييرات
            $owner->save();

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'تم حظر المالك بنجاح'
            ]);

            $this->loadStats();
        }

        $this->confirmingBlacklist = false;
        $this->ownerIdToBlacklist = null;
        $this->blacklistReason = '';
    }

    // ==========================================
    // إلغاء حظر مالك
    // ==========================================
    public function unblacklist($id)
    {
        $owner = OwnerModels::find($id);

        if ($owner) {
            $owner->is_blacklisted = false;
            $owner->blacklist_reason = null;
            $owner->blacklisted_at = null;
            $owner->save();

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'تم إلغاء حظر المالك بنجاح'
            ]);

            $this->loadStats();
        }
    }

    // ==========================================
    //  تغيير حالة المالك
    // ==========================================
    public function toggleStatus($id)
    {
        $owner = OwnerModels::find($id);

        if ($owner) {
            $owner->is_active = !$owner->is_active;
            $owner->save();

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'تم تغيير حالة المالك بنجاح'
            ]);

            $this->loadStats();
        }
    }

    // ==========================================
    //تصدير البيانات
    // ==========================================
    public function export()
    {
        $owners = $this->getOwnersProperty()->items();

        // تنفيذ التصدير حسب الصيغة المختارة
        // يمكن استخدام Laravel Excel أو DomPDF
        $this->dispatch('toast', [
            'type' => 'info',
            'message' => 'جاري تحضير ملف التصدير...'
        ]);
    }

    // ==========================================
    //  إعادة تعيين الفلاتر
    // ==========================================
    public function resetFilters()
    {
        $this->reset([
            'search',
            'status',
            'nationality',
            'city',
            'dateFrom',
            'dateTo'
        ]);

        $this->resetPage();

        $this->dispatch(
            'toast',
            type: 'info',
            message: 'تم إعادة تعيين جميع الفلاتر'
        );
    }

    public $selectedOwner = null;

        public function showOwner($id)
    {
       
        $this->selectedOwner = OwnerModels::find($id);
        $this->dispatch('show-owner-modal');
    }

    // ==========================================
    //  عرض الصفحة
    // ==========================================
   public function render()
{
    return view('front-organizations.livewire.owner.owner')
        ->layout('front-organizations.layout.master');
}
    

    

    // public function render()
    // {
    //     return view('front-organizations.livewire.owner.owner');
    // }
}
