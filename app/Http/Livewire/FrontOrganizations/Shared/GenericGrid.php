<?php

namespace App\Http\Livewire\FrontOrganizations\Shared;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

use App\Services\Access\OrganizationContextService;

class GenericGrid extends Component
{
    use WithPagination;

    /*
    |--------------------------------------------------------------------------
    | الخصائص المحمية
    |--------------------------------------------------------------------------
    */

    #[Locked]
    public string $entityKey = 'owners';


    #[Locked]
    // تحميل اعدادات الملف الى 
    public array $config = [];

    //  بيانات السجل الجاري تعديله.
    public array $editingData = [];
    // بيانات نموذج إضافة سجل جديد.
    public array $creatingData = [];

    //  الفلاتر والبحث
    public array $filters = [];

    public string $search = '';


    // | حالات النوافذ المنبثقة
    public ?int $viewingId = null;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public ?int $togglingId = null;

    public string $modalType = '';


    // | إعدادات الترقيم والترتيب
    public int $perPage = 15;

    public string $sort = 'newest';


    // | إعدادات النظام الداخلية
    protected string $configFile = 'Http/Livewire/FrontOrganizations/Shared/UniversalForm/configs.php';

    protected $paginationTheme = 'tailwind';



    // | دورة حياة المكوّن
    public function mount(string $entityKey = 'owners'): void
    {

        $this->entityKey = $entityKey;

        $this->loadConfiguration();
        // تهيئة الفلاتر
        $this->initializeFilters();

        $this->perPage = $this->normalizePerPage(
            $this->config['pagination']['per_page'] ?? 15
        );
    }


    /**
     * إعادة تحميل الإعدادات الموثوقة عند كل طلب Livewire.
     * هذا يمنع الاعتماد على إعدادات قادمة من المتصفح.
     */
    public function hydrate(): void
    {
        $this->loadConfiguration();
    }

    // config تحميل إعدادات الكيان من ملف 

    protected function loadConfiguration(): void
    {
        $path = app_path($this->configFile);

        if (!is_file($path)) {
            throw new \RuntimeException(
                "ملف الإعدادات غير موجود: {$path}"
            );
        }

        $all = require $path;

        if (!is_array($all)) {
            throw new \RuntimeException(
                'ملف إعدادات المحرك يجب أن يعيد مصفوفة.'
            );
        }

        $defaults = $all['_defaults'] ?? [];

        $entity = $all[$this->entityKey] ?? [];

        if (!is_array($entity) || empty($entity)) {
            abort(404, 'إعدادات القسم غير موجودة: ' . $this->entityKey);
        }

        $merged = $defaults;

        foreach ($entity as $key => $value) {
            if (isset($merged[$key]) && is_array($merged[$key]) && is_array($value) && !array_is_list($merged[$key]) && !array_is_list($value)) {
                $merged[$key] = array_replace_recursive(
                    $merged[$key],
                    $value
                );
            } else {
                $merged[$key] = $value;
            }
        }
        //   ارسال الاعدادات الى داله التحقق من صحتها 
        $this->validateEntityConfiguration($merged);

        $this->config = $merged;
    }


    /**
     * التحقق من صحة إعدادات الكيان قبل استخدامها في الاستعلامات.
     * يمنع تشغيل المحرك على جدول غير محدد أو إعدادات عزل غير آمنة.
     */
    protected function validateEntityConfiguration(array $config): void
    {
        $table = $config['table'] ?? null;

        if (!$this->isSafeIdentifier($table)) {
            throw new \RuntimeException(
                'اسم جدول الكيان غير صحيح في إعدادات المحرك.'
            );
        }

        $primaryKey = $config['primary_key'] ?? 'id';

        if (!$this->isSafeIdentifier($primaryKey)) {
            throw new \RuntimeException(
                'المفتاح الأساسي المحدد غير صحيح.'
            );
        }

        /*
         * يجب تحديد سياسة عزل صريحة لكل كيان.
         * يدعم المحرك طريقتين عامتين:
         * 1) عمود المؤسسة موجود مباشرة في جدول الكيان.
         * 2) العزل عبر علاقة belongs_to مع جدول أب يحتوي organization_id.
         */
        $tenantScoped = $config['tenant_scoped'] ?? null;
        $allowShared = $config['allow_shared'] ?? false;

        if ($tenantScoped === true) {
            $organizationColumn = $config['organization_column'] ?? null;
            $tenantScope = $config['tenant_scope'] ?? null;

            $directScopeIsValid =
                $this->isSafeIdentifier($organizationColumn) &&
                $this->tableHasColumn($table, $organizationColumn);

            $relationScopeIsValid = false;

            if (is_array($tenantScope) && ($tenantScope['type'] ?? null) === 'belongs_to') {
                $parentTable = $tenantScope['table'] ?? null;
                $localKey = $tenantScope['local_key'] ?? null;
                $parentKey = $tenantScope['owner_key'] ?? null;
                $parentOrganizationColumn = $tenantScope['organization_column'] ?? null;

                $relationScopeIsValid =
                    $this->isSafeIdentifier($parentTable) &&
                    $this->isSafeIdentifier($localKey) &&
                    $this->isSafeIdentifier($parentKey) &&
                    $this->isSafeIdentifier($parentOrganizationColumn) &&
                    $this->tableHasColumn($table, $localKey) &&
                    $this->tableHasColumn($parentTable, $parentKey) &&
                    $this->tableHasColumn($parentTable, $parentOrganizationColumn);

                if ($relationScopeIsValid && ($tenantScope['parent_soft_deletes'] ?? false)) {
                    $parentDeletedColumn = $tenantScope['parent_deleted_at_column'] ?? 'deleted_at';
                    $relationScopeIsValid =
                        $this->isSafeIdentifier($parentDeletedColumn) &&
                        $this->tableHasColumn($parentTable, $parentDeletedColumn);
                }
            }

            if (!$directScopeIsValid && !$relationScopeIsValid) {
                throw new AuthorizationException("تعذر التحقق من إعدادات عزل المؤسسة للكيان {$this->entityKey}.");
            }
        } elseif ($tenantScoped === false && $allowShared === true) {
            // كيان مشترك مسموح به صراحةً.
        } else {
            throw new AuthorizationException(
                "لم يتم تحديد سياسة عزل آمنة للكيان {$this->entityKey}."
            );
        }
    }


    /**
     * تهيئة قيم الفلاتر حسب الإعدادات المعرفة لكل كيان.
     * لا يتم قبول مفاتيح فلاتر غير موجودة في ملف الإعدادات.
     */
    protected function initializeFilters(): void
    {
        $this->filters = [];

        foreach (($this->config['filters'] ?? []) as $filter) {
            $key = $filter['key'] ?? null;

            if (!$key || !is_string($key)) {
                continue;
            }

            $this->filters[$key] = $filter['default'] ?? 'all';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | أدوات التحقق والحماية
    |--------------------------------------------------------------------------
    */

    /**
     * التحقق من أن اسم الجدول أو العمود معرف SQL آمن.
     * هذه الدالة لا تستقبل أسماء من المستخدم مباشرة، بل تتحقق من إعدادات النظام.
     */
    protected function isSafeIdentifier(mixed $identifier): bool
    {
        return is_string($identifier)
            && preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $identifier) === 1;
    }

    /**
     * فتح نموذج إضافة سجل جديد من خلال UniversalForm.
     * يتم إرسال الحدث باسم الكيان الحالي دون استقبال اسم كيان من المتصفح.
     */
    /**
     * فتح نموذج إضافة سجل جديد وتهيئة الحقول من إعدادات الكيان.
     */
    public function openCreate(): void
    {
        try {
            // التأكد من وجود سياق المؤسسة الحالية.
            $this->currentOrganizationId();

            // تحميل الإعدادات الموثوقة عند الحاجة.
            if (empty($this->config)) {
                $this->loadConfiguration();
            }

            $this->creatingData = [];

            // تجهيز قيم الحقول من main_fields.
            foreach (($this->config['main_fields'] ?? []) as $field) {
                $key = $field['key'] ?? null;

                if (
                    !$key ||
                    ($field['virtual'] ?? false) ||
                    ($field['readonly'] ?? false) ||
                    ($field['grid_readonly'] ?? false)
                ) {
                    continue;
                }

                $column = $field['column'] ?? $key;

                // منع إدخال المفاتيح والأعمدة المحمية من النموذج.
                if (in_array($column, [
                    $this->resolvePrimaryKey(),
                    'organization_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ], true)) {
                    continue;
                }

                $this->creatingData[$key] = $field['default'] ?? null;
            }

            // فتح نافذة الإضافة.
            $this->closeModal();
            $this->modalType = 'create';
        } catch (Throwable $e) {
            Log::error('[GenericGrid] openCreate', [
                'entity' => $this->entityKey,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'grid-error',
                message: 'تعذر فتح نموذج الإضافة.'
            );
        }
    }

    /**
     * إنشاء قواعد التحقق لحقول نموذج الإضافة اعتماداً على إعدادات الكيان.
     */
    private function getCreateValidationRules(): array
    {
        $rules = [];

        foreach (($this->config['main_fields'] ?? []) as $field) {
            $key = $field['key'] ?? null;

            if (!$key || !array_key_exists($key, $this->creatingData)) {
                continue;
            }

            if (
                ($field['virtual'] ?? false) ||
                ($field['readonly'] ?? false) ||
                ($field['grid_readonly'] ?? false)
            ) {
                continue;
            }

            $column = $field['column'] ?? $key;

            if (in_array($column, [
                $this->resolvePrimaryKey(),
                'organization_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ], true)) {
                continue;
            }

            if (isset($field['validation'])) {
                $rules['creatingData.' . $key] = $field['validation'];
                continue;
            }

            $type = $field['type'] ?? 'text';
            $rule = !empty($field['required']) ? 'required' : 'nullable';

            switch ($type) {
                case 'email':
                    $rule .= '|email|max:255';
                    break;

                case 'number':
                    $rule .= '|numeric';
                    break;

                case 'date':
                    $rule .= '|date';
                    break;

                case 'checkbox':
                    $rule .= '|boolean';
                    break;

                case 'select':
                    $allowedValues = [];

                    foreach (($field['options'] ?? []) as $option) {
                        if (is_array($option) && array_key_exists('value', $option)) {
                            $allowedValues[] = $option['value'];
                        }
                    }

                    if ($allowedValues) {
                        $rule .= '|in:' . implode(',', array_map(
                            fn($value) => str_replace(',', '\\,', (string) $value),
                            $allowedValues
                        ));
                    }

                    break;

                case 'textarea':
                case 'text':
                default:
                    $rule .= '|string|max:5000';
                    break;
            }

            $rules['creatingData.' . $key] = $rule;
        }

        return $rules;
    }


    /**
     * حفظ سجل جديد بعد التحقق من الحقول ونطاق المؤسسة الحالية.
     */
    public function saveCreate(): void
    {
        try {
            // التأكد من أن النموذج مفتوح.
            if ($this->modalType !== 'create') {
                throw new AuthorizationException(
                    'لا توجد عملية إضافة مفتوحة.'
                );
            }

            // إعادة تحميل الإعدادات الموثوقة.
            $this->loadConfiguration();

            // تحديد المؤسسة من سياق النظام، وليس من بيانات النموذج.
            $organizationId = $this->currentOrganizationId();

            // التحقق من المدخلات.
            $validator = Validator::make(
                ['creatingData' => $this->creatingData],
                $this->getCreateValidationRules()
            );

            $validated = $validator->validate();

            $input = $validated['creatingData'] ?? [];

            $table = $this->getTable();
            $primaryKey = $this->resolvePrimaryKey();

            $insertData = [];

            // السماح فقط بالحقول المعرفة في main_fields.
            foreach (($this->config['main_fields'] ?? []) as $field) {
                $key = $field['key'] ?? null;

                if (!$key || !array_key_exists($key, $input)) {
                    continue;
                }

                if (
                    ($field['virtual'] ?? false) ||
                    ($field['readonly'] ?? false) ||
                    ($field['grid_readonly'] ?? false)
                ) {
                    continue;
                }

                $column = $field['column'] ?? $key;

                // منع حقن أعمدة النظام أو تغيير ملكية السجل.
                if (in_array($column, [
                    $primaryKey,
                    'organization_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ], true)) {
                    continue;
                }

                if (!$this->tableHasColumn($table, $column)) {
                    continue;
                }

                // الحالة الافتراضية تعالج بصورة منفصلة.
                if ($key === 'status' && !isset($field['column'])) {
                    continue;
                }

                $value = $input[$key];

                if (($field['type'] ?? '') === 'checkbox') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }

                $insertData[$column] = $value;
            }

            // معالجة حقل الحالة الافتراضي إن كان معرفاً في الإعدادات.
            $statusConfig = $this->config['status'] ?? [];
            $statusColumn = $statusConfig['column'] ?? null;
            $statusValue = $input['status'] ?? null;
            $booleanMap = $statusConfig['boolean_map'] ?? [];

            if (
                $statusColumn &&
                $statusValue !== null &&
                is_array($booleanMap) &&
                $this->tableHasColumn($table, $statusColumn)
            ) {
                if ((string) $statusValue === (string) ($booleanMap['true'] ?? '')) {
                    $insertData[$statusColumn] = true;
                } elseif (
                    (string) $statusValue === (string) ($booleanMap['false'] ?? '')
                ) {
                    $insertData[$statusColumn] = false;
                }
            }

            // التمييز بين نشط وغير نشط إذا كان الجدول يحتوي is_active.
            if ($this->tableHasColumn($table, 'is_active') && $statusValue !== null) {
                if ($statusValue === 'active') {
                    $insertData['is_active'] = true;
                } elseif ($statusValue === 'inactive') {
                    $insertData['is_active'] = false;
                }
            }

            // إسناد المؤسسة الحالية من الخادم عند وجود العمود مباشرة.
            if (
                ($this->config['tenant_scoped'] ?? false) === true &&
                $this->tableHasColumn(
                    $table,
                    $this->config['organization_column'] ?? ''
                )
            ) {
                $organizationColumn = $this->config['organization_column'];
                $insertData[$organizationColumn] = $organizationId;
            }

            if ($this->tableHasColumn($table, 'created_at')) {
                $insertData['created_at'] = now();
            }

            if ($this->tableHasColumn($table, 'updated_at')) {
                $insertData['updated_at'] = now();
            }

            if (empty($insertData)) {
                throw ValidationException::withMessages([
                    'creatingData' => 'لا توجد حقول صالحة لحفظ السجل.',
                ]);
            }

            // تنفيذ الإضافة داخل معاملة قاعدة البيانات.
            DB::transaction(function () use ($insertData) {
                DB::table($this->getTable())->insert($insertData);
            });

            // تنظيف النموذج وإغلاق النافذة.
            $this->creatingData = [];
            $this->closeModal();
            $this->resetPage();

            $this->dispatch(
                'grid-refreshed',
                message: 'تمت إضافة السجل بنجاح.'
            );
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('[GenericGrid] saveCreate', [
                'entity' => $this->entityKey,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'grid-error',
                message: 'تعذر إضافة السجل. تحقق من البيانات وحاول مرة أخرى.'
            );
        }
    }
    /**
     * جلب الملاك التابعين للمؤسسة الحالية لاستخدامهم في قائمة اختيار الوكيل.
     */
    private function getOwnerOptions(): array
    {
        $organizationId = $this->currentOrganizationId();

        return DB::table('owners')
            ->where('organization_id', $organizationId)
            ->whereNull('deleted_at')
            ->orderBy('full_name')
            ->get([
                'owner_id',
                'full_name',
            ])
            ->map(fn($owner) => [
                'value' => $owner->owner_id,
                'label' => $owner->full_name,
            ])
            ->toArray();
    }

    /**
     * التحقق من وجود عمود داخل جدول محدد.
     * تستخدم هذه الدالة للتحقق من الأعمدة قبل بناء الاستعلامات.
     */
    protected function tableHasColumn(string $table, string $column): bool
    {
        if (
            !$this->isSafeIdentifier($table) ||
            !$this->isSafeIdentifier($column)
        ) {
            return false;
        }

        return Schema::hasColumn($table, $column);
    }


    /**
     * إرجاع اسم جدول الكيان الحالي من الإعدادات الموثوقة.
     */
    protected function getTable(): string
    {
        $table = $this->config['table'] ?? null;

        if (!$this->isSafeIdentifier($table)) {
            throw new \RuntimeException('جدول الكيان غير صالح.');
        }

        return $table;
    }


    /**
     * إرجاع المفتاح الأساسي للجدول والتحقق من وجوده.
     * يتم استخدام id أو uuid كبدائل عند عدم وجود المفتاح المحدد.
     */
    protected function resolvePrimaryKey(): string
    {
        $table = $this->getTable();

        $configured = $this->config['primary_key'] ?? 'id';

        if ($this->tableHasColumn($table, $configured)) {
            return $configured;
        }

        foreach (['id', 'uuid'] as $column) {
            if ($this->tableHasColumn($table, $column)) {
                return $column;
            }
        }

        throw new \RuntimeException(
            "لم يتم العثور على مفتاح أساسي صالح في الجدول {$table}."
        );
    }


    /**
     * التحقق من وجود عمود داخل جدول الكيان الحالي.
     */
    protected function hasColumn(string $column): bool
    {
        return $this->tableHasColumn(
            $this->getTable(),
            $column
        );
    }


    /**
     * جلب رقم المؤسسة الحالية من خدمة سياق الوصول الموثوقة.
     * لا تعتمد هذه الدالة على organization_id المرسل من المتصفح.
     */
    protected function currentOrganizationId(): int
    {
        return (int) app(
            OrganizationContextService::class
        )->currentOrganizationId();
    }


    /**
     * تحديد عدد السجلات في الصفحة ضمن الحدود المسموح بها.
     * يمنع إرسال عدد ضخم من المتصفح بهدف استهلاك موارد الخادم.
     */
    protected function normalizePerPage(mixed $value): int
    {
        $value = filter_var($value, FILTER_VALIDATE_INT);

        if ($value === false) {
            return 15;
        }

        return max(1, min(100, $value));
    }


    /**
     * التحقق من أن السجل المطلوب تابع للمؤسسة الحالية.
     * يستخدم نفس الاستعلام المحمي المستخدم في عرض السجلات.
     */
    protected function findScopedRecord(int $id): ?object
    {
        $pk = $this->resolvePrimaryKey();

        return $this->baseQuery()
            ->where($pk, $id)
            ->first();
    }


    /**
     * التحقق من ملكية السجل قبل تنفيذ أي إجراء عليه.
     * يرفض الوصول إلى سجل غير موجود أو تابع لمؤسسة أخرى.
     */
    protected function authorizeRecord(int $id): object
    {
        $record = $this->findScopedRecord($id);

        if (!$record) {
            throw new AuthorizationException(
                'السجل غير موجود أو لا تملك صلاحية الوصول إليه.'
            );
        }

        return $record;
    }


    /**
     * إضافة عمود updated_at إلى بيانات التحديث عند وجوده في الجدول.
     */
    protected function addUpdatedAt(array $updates): array
    {
        if ($this->hasColumn('updated_at')) {
            $updates['updated_at'] = now();
        }

        return $updates;
    }


    /*
    |--------------------------------------------------------------------------
    | الاستعلام الأساسي وعزل المؤسسات
    |--------------------------------------------------------------------------
    */

    /**
     * إنشاء الاستعلام الأساسي للكيان مع تطبيق عزل المؤسسة.
     *
     * إذا كان الكيان تابعاً لمؤسسة:
     * تتم إضافة where organization_id = المؤسسة الحالية.
     *
     * إذا كان الكيان مشتركاً:
     * لا يسمح بذلك إلا عند التصريح allow_shared = true.
     *
     * كما يتم استبعاد السجلات المحذوفة منطقياً عند تفعيل ذلك في الإعدادات.
     */
    protected function baseQuery()
    {
        $table = $this->getTable();

        $query = DB::table($table);

        if (($this->config['tenant_scoped'] ?? false) === true) {
            $organizationColumn = $this->config['organization_column'] ?? null;
            $tenantScope = $this->config['tenant_scope'] ?? null;
            $organizationId = $this->currentOrganizationId();

            // المسار الأول: عمود المؤسسة موجود مباشرة في جدول الكيان.
            if (
                $this->isSafeIdentifier($organizationColumn) &&
                $this->tableHasColumn($table, $organizationColumn)
            ) {
                $query->where("{$table}.{$organizationColumn}", $organizationId);
            }
            // المسار الثاني: تحديد المؤسسة من خلال جدول أب مرتبط بالكيان.
            elseif (is_array($tenantScope) && ($tenantScope['type'] ?? null) === 'belongs_to') {
                $parentTable = $tenantScope['table'] ?? null;
                $localKey = $tenantScope['local_key'] ?? null;
                $parentKey = $tenantScope['owner_key'] ?? null;
                $parentOrganizationColumn = $tenantScope['organization_column'] ?? null;

                if (
                    !$this->isSafeIdentifier($parentTable) ||
                    !$this->isSafeIdentifier($localKey) ||
                    !$this->isSafeIdentifier($parentKey) ||
                    !$this->isSafeIdentifier($parentOrganizationColumn) ||
                    !$this->tableHasColumn($table, $localKey) ||
                    !$this->tableHasColumn($parentTable, $parentKey) ||
                    !$this->tableHasColumn($parentTable, $parentOrganizationColumn)
                ) {
                    throw new AuthorizationException(
                        "تعذر تطبيق نطاق المؤسسة المرتبط على جدول {$table}."
                    );
                }

                $parentAlias = 'tenant_scope_parent';

                $query->whereExists(function ($subQuery) use (
                    $parentTable,
                    $parentAlias,
                    $parentKey,
                    $localKey,
                    $parentOrganizationColumn,
                    $organizationId,
                    $tenantScope,
                    $table
                ) {
                    $subQuery->selectRaw('1')
                        ->from("{$parentTable} as {$parentAlias}")
                        ->whereColumn(
                            "{$parentAlias}.{$parentKey}",
                            "{$table}.{$localKey}"
                        )
                        ->where(
                            "{$parentAlias}.{$parentOrganizationColumn}",
                            $organizationId
                        );

                    // استبعاد السجل الأب المحذوف منطقياً إذا طلبت الإعدادات ذلك.
                    if ($tenantScope['parent_soft_deletes'] ?? false) {
                        $deletedColumn = $tenantScope['parent_deleted_at_column'] ?? 'deleted_at';

                        if (!$this->tableHasColumn($parentTable, $deletedColumn)) {
                            throw new AuthorizationException(
                                'عمود الحذف المنطقي غير موجود في جدول النطاق الأب.'
                            );
                        }

                        $subQuery->whereNull("{$parentAlias}.{$deletedColumn}");
                    }
                });
            } else {
                throw new AuthorizationException(
                    "تعذر تطبيق عزل المؤسسة على جدول {$table}."
                );
            }
        } elseif (
            !(
                ($this->config['tenant_scoped'] ?? null) === false &&
                ($this->config['allow_shared'] ?? false) === true
            )
        ) {
            throw new AuthorizationException(
                'لا توجد سياسة عزل صالحة لهذا الكيان.'
            );
        }

        if ($this->config['has_soft_deletes'] ?? false) {
            $deletedColumn =
                $this->config['deleted_at_column'] ?? 'deleted_at';

            if ($this->tableHasColumn($table, $deletedColumn)) {
                $query->whereNull("{$table}.{$deletedColumn}");
            }
        }

        return $query;
    }


    /*
    |--------------------------------------------------------------------------
    | البحث والفلاتر والترتيب
    |--------------------------------------------------------------------------
    */

    /**
     * تطبيق البحث النصي على الحقول المسموح بها في إعدادات الكيان.
     * يتم تجاهل أي عمود غير موجود في الجدول.
     */
    protected function applySearch($query): void
    {
        $term = trim($this->search);

        if ($term === '') {
            return;
        }

        $fields = $this->config['search']['fields'] ?? [];

        if (empty($fields)) {
            foreach (
                [
                    $this->config['name_field'] ?? null,
                    'phone',
                    'email',
                    'id_number'
                ] as $candidate
            ) {
                if (
                    $candidate &&
                    $this->hasColumn($candidate)
                ) {
                    $fields[] = $candidate;
                }
            }
        }

        $fields = array_values(array_filter(
            $fields,
            fn($field) =>
            $this->isSafeIdentifier($field) &&
                $this->hasColumn($field)
        ));

        if (empty($fields)) {
            return;
        }

        $table = $this->getTable();

        $query->where(function ($subQuery) use ($fields, $term, $table) {
            foreach ($fields as $field) {
                $subQuery->orWhere(
                    "{$table}.{$field}",
                    'LIKE',
                    "%{$term}%"
                );
            }
        });
    }


    /**
     * تطبيق الفلاتر المحددة في إعدادات الكيان.
     * يدعم الفلاتر العادية والتاريخ والحالة ووجود العلاقات.
     */
    protected function applyFilters($query): void
    {
        foreach (($this->config['filters'] ?? []) as $filter) {
            $key = $filter['key'] ?? null;

            if (!$key) {
                continue;
            }

            $value = $this->filters[$key] ?? 'all';

            if (!is_scalar($value) && $value !== null) {
                continue;
            }

            $type = $filter['type'] ?? 'select';

            if ($type === 'sort') {
                continue;
            }

            if (
                $value === 'all' ||
                $value === '' ||
                $value === null
            ) {
                continue;
            }

            /*
             * فلتر وجود علاقة.
             * يعتمد على المفتاح الأجنبي للعلاقة، مع تطبيق نطاق المؤسسة
             * على الاستعلام الأب قبل الوصول إلى هذا الفلتر.
             */
            if ($type === 'relation_exists') {
                $relationName = $filter['relation'] ?? null;

                $relation = $this->config['relations'][$relationName] ?? null;

                if (!$relation) {
                    continue;
                }

                $relationTable = $relation['table'] ?? null;
                $foreignKey = $relation['foreign_key'] ?? null;

                if (
                    !$this->isSafeIdentifier($relationTable) ||
                    !$this->isSafeIdentifier($foreignKey)
                ) {
                    continue;
                }

                $parentTable = $this->getTable();
                $primaryKey = $this->resolvePrimaryKey();

                if (
                    !$this->tableHasColumn($relationTable, $foreignKey)
                ) {
                    continue;
                }

                $relationQuery = function ($subQuery) use (
                    $relationTable,
                    $foreignKey,
                    $parentTable,
                    $primaryKey,
                    $relation
                ) {
                    $subQuery->select(DB::raw(1))
                        ->from($relationTable)
                        ->whereColumn(
                            "{$relationTable}.{$foreignKey}",
                            "{$parentTable}.{$primaryKey}"
                        );

                    if (($relation['organization_column'] ?? null)) {
                        $organizationColumn =
                            $relation['organization_column'];

                        if (
                            !$this->tableHasColumn(
                                $relationTable,
                                $organizationColumn
                            )
                        ) {
                            throw new AuthorizationException(
                                'عمود المؤسسة غير موجود في جدول العلاقة.'
                            );
                        }

                        $subQuery->where(
                            "{$relationTable}.{$organizationColumn}",
                            $this->currentOrganizationId()
                        );
                    }
                };

                if ($value === 'has') {
                    $query->whereExists($relationQuery);
                } elseif ($value === 'none') {
                    $query->whereNotExists($relationQuery);
                }

                continue;
            }

            /*
             * فلتر التاريخ.
             */
            if ($type === 'date') {
                $column = $filter['column'] ?? 'created_at';

                if (!$this->hasColumn($column)) {
                    continue;
                }

                if ($key === 'dateFrom') {
                    $query->whereDate($column, '>=', $value);
                } elseif ($key === 'dateTo') {
                    $query->whereDate($column, '<=', $value);
                } else {
                    $query->whereDate($column, $value);
                }

                continue;
            }

            /*
             * فلتر حالة عام يعتمد على status.column وboolean_map.
             * يعمل مع is_blacklisted أو is_active أو أي عمود منطقي آخر.
             */
            if ($key === 'status') {
                $statusConfig = $this->config['status'] ?? [];
                $statusColumn = $statusConfig['column'] ?? 'status';
                $booleanMap = $statusConfig['boolean_map'] ?? null;

                if (is_array($booleanMap) && $this->hasColumn($statusColumn)) {
                    $trueStatus = $booleanMap['true'] ?? null;
                    $falseStatus = $booleanMap['false'] ?? null;

                    if ($trueStatus !== null && (string) $value === (string) $trueStatus) {
                        $query->where($statusColumn, true);
                    } elseif ($falseStatus !== null && (string) $value === (string) $falseStatus) {
                        $query->where($statusColumn, false);

                        // في الكيانات التي تميز بين غير نشط ومحظور.
                        if ($this->hasColumn('is_active') && $statusColumn !== 'is_active') {
                            $query->where('is_active', true);
                        }
                    } elseif ((string) $value === 'inactive' && $this->hasColumn('is_active')) {
                        if ($statusColumn !== 'is_active') {
                            $query->where($statusColumn, false);
                        }
                        $query->where('is_active', false);
                    }
                } elseif ($this->hasColumn($statusColumn)) {
                    $query->where($statusColumn, $value);
                }

                continue;
            }

            /*
             * فلتر select عادي.
             */
            $column = $filter['column'] ?? $key;

            if (!$this->hasColumn($column)) {
                continue;
            }

            $allowedOperators = [
                '=',
                '!=',
                '>',
                '<',
                '>=',
                '<=',
                'LIKE',
            ];

            $operator = strtoupper(
                (string) ($filter['operator'] ?? '=')
            );

            if (!in_array($operator, $allowedOperators, true)) {
                $operator = '=';
            }

            $query->where($column, $operator, $value);
        }
    }


    /**
     * تطبيق الترتيب على السجلات باستخدام خيارات الترتيب المسموح بها.
     * يمنع تمرير أسماء أعمدة أو اتجاهات ترتيب غير موثوقة.
     */
    protected function applySorting($query): void
    {
        $sortValue = $this->filters['sort'] ?? $this->sort;

        if (
            !is_string($sortValue) ||
            $sortValue === 'all' ||
            $sortValue === ''
        ) {
            $sortValue = 'newest';
        }

        $primaryKey = $this->resolvePrimaryKey();

        $nameField = $this->config['name_field'] ?? null;

        if (
            !$nameField ||
            !$this->hasColumn($nameField)
        ) {
            $nameField = null;
        }

        switch ($sortValue) {
            case 'oldest':
                $query->orderBy($primaryKey, 'asc');
                break;

            case 'name_asc':
                $nameField
                    ? $query->orderBy($nameField, 'asc')
                    : $query->orderBy($primaryKey, 'desc');
                break;

            case 'name_desc':
                $nameField
                    ? $query->orderBy($nameField, 'desc')
                    : $query->orderBy($primaryKey, 'desc');
                break;

            default:
                $query->orderBy($primaryKey, 'desc');
                break;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | جلب السجلات وتجهيزها للواجهة
    |--------------------------------------------------------------------------
    */

    /**
     * جلب سجلات الكيان بعد تطبيق عزل المؤسسة والبحث والفلاتر والترتيب.
     * ثم تجهيز التسميات والحالات والعلاقات قبل إرسالها إلى Blade.
     */
    public function getRecordsProperty(): array
    {
        $this->perPage = $this->normalizePerPage($this->perPage);

        $query = $this->baseQuery();

        $this->applySearch($query);

        $this->applyFilters($query);

        $this->applySorting($query);

        $paginator = $query->paginate($this->perPage);

        $primaryKey = $this->resolvePrimaryKey();

        $items = [];

        foreach ($paginator->items() as $row) {
            $array = (array) $row;

            $array['id'] = $array[$primaryKey] ?? null;

            $array['_labels'] = $this->hydrateLabels($array);

            $this->hydrateStatus($array);

            $this->hydrateRelations($array);

            $items[] = $array;
        }

        return [
            'items' => $items,
            'paginator' => $paginator,
        ];
    }


    /**
     * تجهيز أسماء الحقول المرتبطة بجداول أخرى.
     * لا يتم جلب بيانات مؤسسة أخرى من lookup خاص بمؤسسة مختلفة.
     *
     * يجب أن يحدد lookup إما shared = true للقوائم المشتركة،
     * أو organization_column للقوائم التابعة لمؤسسة.
     */
    protected function hydrateLabels(array $row): array
    {
        $labels = [];

        foreach (($this->config['lookups'] ?? []) as $localKey => $lookup) {
            $foreignValue = $row[$localKey] ?? null;

            if (!$foreignValue) {
                continue;
            }

            $table = $lookup['table'] ?? null;
            $lookupKey = $lookup['key'] ?? 'id';
            $display = $lookup['display'] ?? 'name';

            if (
                !$this->isSafeIdentifier($table) ||
                !$this->isSafeIdentifier($lookupKey) ||
                !$this->isSafeIdentifier($display)
            ) {
                continue;
            }

            /*
             * لا نسمح بقراءة lookup غير مصنف.
             */
            $shared = $lookup['shared'] ?? false;
            $organizationColumn =
                $lookup['organization_column'] ?? null;

            if (
                !$shared &&
                !$organizationColumn &&
                $table !== 'organizations'
            ) {
                continue;
            }

            try {
                $query = DB::table($table)
                    ->where($lookupKey, $foreignValue);

                /*
                 * القوائم الخاصة بالمؤسسة.
                 */
                if ($organizationColumn) {
                    if (
                        !$this->tableHasColumn(
                            $table,
                            $organizationColumn
                        )
                    ) {
                        continue;
                    }

                    $query->where(
                        $organizationColumn,
                        $this->currentOrganizationId()
                    );
                }

                /*
                 * جدول المؤسسات: لا نعرض إلا المؤسسة الحالية.
                 */
                if ($table === 'organizations') {
                    $organizationId = $this->currentOrganizationId();

                    $organizationPrimaryKey =
                        $lookup['key'] ?? 'organization_id';

                    if (
                        !$this->tableHasColumn(
                            'organizations',
                            $organizationPrimaryKey
                        )
                    ) {
                        continue;
                    }

                    $query->where(
                        $organizationPrimaryKey,
                        $organizationId
                    );
                }

                $record = $query->first();

                if ($record && isset($record->{$display})) {
                    $labels[$localKey] = $record->{$display};
                }
            } catch (\Throwable $e) {
                Log::warning('[GenericGrid] hydrateLabels', [
                    'entity' => $this->entityKey,
                    'lookup' => $localKey,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $labels;
    }


    /**
     * تحديد الحالة النصية للسجل اعتماداً على أعمدة الحالة الموجودة.
     * يدعم status العادي وboolean_map وحالة الحظر والتفعيل.
     */
    protected function hydrateStatus(array &$row): void
    {
        if (
            isset($row['status']) &&
            $row['status'] !== null
        ) {
            return;
        }

        $statusConfig = $this->config['status'] ?? [];

        $column = $statusConfig['column'] ?? 'status';

        $booleanMap = $statusConfig['boolean_map'] ?? null;

        if (
            array_key_exists($column, $row) &&
            is_array($booleanMap)
        ) {
            $raw = $row[$column];

            if ($raw) {
                $row['status'] = $booleanMap['true'] ?? 'active';
            } elseif (
                $column !== 'is_active' &&
                array_key_exists('is_active', $row) &&
                empty($row['is_active'])
            ) {
                // بعض الكيانات تميز بين الحظر (عمود مستقل) وعدم النشاط.
                $row['status'] = 'inactive';
            } else {
                $row['status'] = $booleanMap['false'] ?? 'inactive';
            }
        } elseif (array_key_exists('is_blacklisted', $row)) {
            $row['status'] = $row['is_blacklisted']
                ? 'blocked'
                : (
                    !empty($row['is_active'])
                    ? 'active'
                    : 'inactive'
                );
        } else {
            $row['status'] = $row['status'] ?? 'active';
        }
    }


    /**
     * تحميل العلاقات التابعة للسجل الحالي.
     * يتم تقييد كل علاقة بالمفتاح الأجنبي للسجل الأب،
     * وبعمود المؤسسة أيضاً إذا كان موجوداً في إعدادات العلاقة.
     */
    protected function hydrateRelations(array &$row): void
    {
        $primaryKey = $this->resolvePrimaryKey();

        foreach (($this->config['relations'] ?? []) as $name => $relation) {
            if (!($relation['enabled'] ?? true)) {
                continue;
            }

            if (($relation['type'] ?? 'hasMany') !== 'hasMany') {
                continue;
            }

            $table = $relation['table'] ?? null;
            $foreignKey = $relation['foreign_key'] ?? null;
            $relationPrimaryKey =
                $relation['primary_key'] ?? 'id';

            if (
                !$this->isSafeIdentifier($table) ||
                !$this->isSafeIdentifier($foreignKey) ||
                !$this->isSafeIdentifier($relationPrimaryKey)
            ) {
                $row[$name] = [];
                continue;
            }

            if (
                !$this->tableHasColumn($table, $foreignKey) ||
                !$this->tableHasColumn($table, $relationPrimaryKey)
            ) {
                $row[$name] = [];
                continue;
            }

            try {
                $query = DB::table($table)
                    ->where($foreignKey, $row[$primaryKey]);

                /*
                 * إذا كانت العلاقة تحتوي organization_id،
                 * يتم فرض المؤسسة الحالية عليها أيضاً.
                 *
                 * أما owner_agents مثلاً، فيتم عزلها من خلال owner_id
                 * المرتبط بمالك تم جلبه أصلاً من المؤسسة الحالية.
                 */
                $organizationColumn =
                    $relation['organization_column'] ?? null;

                if ($organizationColumn) {
                    if (
                        !$this->tableHasColumn(
                            $table,
                            $organizationColumn
                        )
                    ) {
                        $row[$name] = [];
                        continue;
                    }

                    $query->where(
                        $organizationColumn,
                        $this->currentOrganizationId()
                    );
                }

                if ($relation['has_soft_deletes'] ?? false) {
                    $deletedColumn =
                        $relation['deleted_at_column'] ?? 'deleted_at';

                    if ($this->tableHasColumn($table, $deletedColumn)) {
                        $query->whereNull($deletedColumn);
                    }
                }

                $rows = $query
                    ->orderByDesc($relationPrimaryKey)
                    ->get();

                $statusColumn =
                    $relation['status_column'] ?? null;

                $row[$name] = $rows->map(
                    function ($record) use (
                        $relationPrimaryKey,
                        $statusColumn
                    ) {
                        $array = (array) $record;

                        $array['id'] =
                            $array[$relationPrimaryKey] ?? null;

                        if (
                            $statusColumn &&
                            !isset($array['status'])
                        ) {
                            $array['status'] =
                                !empty($array[$statusColumn])
                                ? 'active'
                                : 'blocked';
                        } elseif (!isset($array['status'])) {
                            $array['status'] = 'active';
                        }

                        return $array;
                    }
                )->toArray();
            } catch (\Throwable $e) {
                Log::error('[GenericGrid] hydrateRelations', [
                    'entity' => $this->entityKey,
                    'relation' => $name,
                    'error' => $e->getMessage(),
                ]);

                $row[$name] = [];
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | الخصائص المحسوبة للواجهة
    |--------------------------------------------------------------------------
    */

    /**
     * جلب بيانات المؤسسة الحالية فقط لعرض اسم المؤسسة في الواجهة.
     * لا يتم إرجاع قائمة بجميع المؤسسات للمستخدم داخل حساب المؤسسة.
     */
    public function getOrganizationsProperty(): array
    {
        try {
            $organizationId = $this->currentOrganizationId();

            $table = 'organizations';

            $primaryKey = 'organization_id';

            if (!$this->tableHasColumn($table, $primaryKey)) {
                if ($this->tableHasColumn($table, 'id')) {
                    $primaryKey = 'id';
                } else {
                    return [];
                }
            }

            $nameColumn = null;

            foreach (['name', 'organization_name'] as $candidate) {
                if ($this->tableHasColumn($table, $candidate)) {
                    $nameColumn = $candidate;
                    break;
                }
            }

            if (!$nameColumn) {
                return [];
            }

            $organization = DB::table($table)
                ->select(
                    "{$primaryKey} as id",
                    "{$nameColumn} as name"
                )
                ->where($primaryKey, $organizationId)
                ->first();

            if (!$organization) {
                return [];
            }

            return [[
                'id' => (int) $organization->id,
                'name' => (string) $organization->name,
            ]];
        } catch (\Throwable $e) {
            Log::warning('[GenericGrid] getOrganizationsProperty', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }


    /**
     * بناء خرائط التسميات المستخدمة في حقول الواجهة.
     * تجمع الخيارات الموجودة في الحقول الرئيسية وحقول العلاقات.
     */
    public function getLabelMapsProperty(): array
    {
        $output = [];

        $collect = function (array $fields) use (&$output) {
            foreach ($fields as $field) {
                if (
                    empty($field['key']) ||
                    empty($field['options']) ||
                    !is_array($field['options'])
                ) {
                    continue;
                }

                $output[$field['key']] = [];

                foreach ($field['options'] as $option) {
                    if (
                        !is_array($option) ||
                        !array_key_exists('value', $option) ||
                        !array_key_exists('label', $option)
                    ) {
                        continue;
                    }

                    $output[$field['key']][$option['value']] =
                        $option['label'];
                }
            }
        };

        $collect($this->config['main_fields'] ?? []);

        foreach (($this->config['relations'] ?? []) as $relation) {
            $collect($relation['fields'] ?? []);
        }

        return $output;
    }


    /**
     * إرجاع السجل المحدد للعرض من سجلات الصفحة الحالية.
     * السجلات المعروضة تكون قد مرت مسبقاً عبر استعلام المؤسسة المحمي.
     */
    public function getViewingRecordProperty(): ?array
    {
        if (!$this->viewingId) {
            return null;
        }

        foreach ($this->records['items'] as $record) {
            if ((int) $record['id'] === (int) $this->viewingId) {
                return $record;
            }
        }

        return null;
    }


    /**
     * إرجاع السجل المحدد للتعديل من سجلات الصفحة الحالية.
     */
    public function getEditingRecordProperty(): ?array
    {
        if (!$this->editingId) {
            return null;
        }

        foreach ($this->records['items'] as $record) {
            if ((int) $record['id'] === (int) $this->editingId) {
                return $record;
            }
        }

        return null;
    }


    /**
     * إرجاع السجل المحدد للحذف من سجلات الصفحة الحالية.
     */
    public function getDeletingRecordProperty(): ?array
    {
        if (!$this->deletingId) {
            return null;
        }

        foreach ($this->records['items'] as $record) {
            if ((int) $record['id'] === (int) $this->deletingId) {
                return $record;
            }
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | أحداث تحديث الفلاتر والترقيم
    |--------------------------------------------------------------------------
    */

    /**
     * إعادة الصفحة الأولى عند تغيير نص البحث.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }


    /**
     * إعادة الصفحة الأولى عند تغيير الفلاتر.
     */
    public function updatedFilters(): void
    {
        $this->resetPage();
    }


    /**
     * ضبط عدد السجلات المسموح به وإعادة الصفحة الأولى عند تغييره.
     */
    public function updatedPerPage(): void
    {
        $this->perPage = $this->normalizePerPage($this->perPage);

        $this->resetPage();
    }


    /**
     * إعادة الصفحة الأولى عند تغيير ترتيب العرض.
     */
    public function updatedFilterSort(): void
    {
        $this->resetPage();
    }


    /*
    |--------------------------------------------------------------------------
    | فتح النوافذ المنبثقة
    |--------------------------------------------------------------------------
    */

    /**
     * فتح نافذة عرض السجل بعد التحقق من تبعيته للمؤسسة الحالية.
     */
    public function openView(int $id): void
    {
        $this->authorizeRecord($id);

        $this->closeModal();

        $this->viewingId = $id;

        $this->modalType = 'view';
    }


    /**
     * فتح نافذة تعديل سجل وتحميل بياناته.
     * يتحقق أولاً من أن السجل تابع للمؤسسة الحالية.
     */
    public function openEdit(int $id): void
    {
        $this->authorizeRecord($id);

        $primaryKey = $this->resolvePrimaryKey();

        $record = $this->baseQuery()
            ->where($primaryKey, $id)
            ->first();

        if (!$record) {
            $this->dispatch(
                'grid-error',
                message: 'السجل غير موجود أو لا تملك صلاحية الوصول إليه.'
            );

            return;
        }

        $record = (array) $record;

        $this->editingData = [];

        foreach (($this->config['main_fields'] ?? []) as $field) {

            $key = $field['key'] ?? null;

            if (!$key) {
                continue;
            }

            $column = $field['column'] ?? $key;

            // الحقول الافتراضية التي لا تمثل عموداً مباشراً.
            if (
                ($field['virtual'] ?? false) ||
                (($field['readonly'] ?? false) || ($field['grid_readonly'] ?? false))
            ) {
                continue;
            }

            // معالجة الحالة الافتراضية.
            if ($key === 'status' && !isset($field['column'])) {

                $statusConfig = $this->config['status'] ?? [];

                $statusColumn = $statusConfig['column'] ?? null;
                $rawStatus = $statusColumn
                    ? ($record[$statusColumn] ?? null)
                    : null;

                if ($rawStatus !== null && is_array($statusConfig['boolean_map'] ?? null)) {

                    $booleanMap = $statusConfig['boolean_map'];

                    if ((bool) $rawStatus) {
                        $status = $booleanMap['true'] ?? 'blocked';
                    } else {
                        $status = !empty($record['is_active'])
                            ? ($booleanMap['false'] ?? 'active')
                            : 'inactive';
                    }
                } else {
                    $status = $record['status'] ?? 'active';
                }

                $this->editingData[$key] = $status;

                continue;
            }

            // لا نحمل أعمدة المؤسسة والمفاتيح الأساسية للتعديل.
            if (in_array($column, [
                $primaryKey,
                'organization_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ], true)) {
                continue;
            }

            $this->editingData[$key] = $record[$column] ?? null;
        }

        $this->editingId = $id;
        $this->viewingId = null;
        $this->deletingId = null;
        $this->togglingId = null;

        $this->modalType = 'edit';
    }
    /**
     * إنشاء قواعد التحقق تلقائياً اعتماداً على main_fields.
     */
    private function getEditValidationRules(): array
    {
        $rules = [];

        foreach (($this->config['main_fields'] ?? []) as $field) {

            $key = $field['key'] ?? null;

            if (!$key || !array_key_exists($key, $this->editingData)) {
                continue;
            }

            if (
                ($field['virtual'] ?? false) ||
                (($field['readonly'] ?? false) || ($field['grid_readonly'] ?? false))
            ) {
                continue;
            }

            if (in_array($key, [
                'id',
                'organization_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ], true)) {
                continue;
            }

            if (isset($field['validation'])) {
                $rules['editingData.' . $key] = $field['validation'];
                continue;
            }

            $rule = !empty($field['required'])
                ? 'required'
                : 'nullable';

            $type = $field['type'] ?? 'text';

            if ($type === 'email') {
                $rule .= '|email|max:255';
            } elseif ($type === 'number') {
                $rule .= '|numeric';
            } elseif ($type === 'date') {
                $rule .= '|date';
            } elseif ($type === 'textarea' || $type === 'text') {
                $rule .= '|string|max:5000';
            }

            $rules['editingData.' . $key] = $rule;
        }

        return $rules;
    }

    /**
     * التحقق من بيانات التعديل وحفظ الحقول المسموح بها فقط.
     * جميع عمليات التحديث تمر عبر baseQuery لضمان عزل المؤسسات.
     */
    public function saveEdit(): void
    {
        try {

            if (!$this->editingId) {
                throw ValidationException::withMessages([
                    'editingData' => 'لم يتم تحديد السجل المطلوب تعديله.',
                ]);
            }

            // إعادة التحقق من صلاحية الوصول للسجل.
            $this->authorizeRecord($this->editingId);

            $primaryKey = $this->resolvePrimaryKey();
            $table = $this->getTable();

            // جلب السجل من نطاق المؤسسة الحالية.
            $record = $this->baseQuery()
                ->where($primaryKey, $this->editingId)
                ->first();

            if (!$record) {
                throw ValidationException::withMessages([
                    'editingData' => 'السجل غير موجود أو لا تملك صلاحية تعديله.',
                ]);
            }

            // التحقق من المدخلات.
            $validator = Validator::make(
                ['editingData' => $this->editingData],
                $this->getEditValidationRules()
            );

            $validated = $validator->validate();

            $input = $validated['editingData'] ?? [];

            $updateData = [];

            // استخراج الأعمدة المسموح بتعديلها من الإعدادات.
            foreach (($this->config['main_fields'] ?? []) as $field) {

                $key = $field['key'] ?? null;

                if (!$key || !array_key_exists($key, $input)) {
                    continue;
                }

                if (
                    ($field['virtual'] ?? false) ||
                    (($field['readonly'] ?? false) || ($field['grid_readonly'] ?? false))
                ) {
                    continue;
                }

                $column = $field['column'] ?? $key;

                // منع تعديل الأعمدة المحمية.
                if (in_array($column, [
                    $primaryKey,
                    'organization_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ], true)) {
                    continue;
                }

                // الحالة الافتراضية لها معالجة خاصة.
                if ($key === 'status' && !isset($field['column'])) {
                    continue;
                }

                // لا نسمح بتحديث عمود غير موجود.
                if (!$this->tableHasColumn($table, $column)) {
                    continue;
                }

                $value = $input[$key];

                // تحويل قيم checkbox إلى Boolean.
                if (($field['type'] ?? '') === 'checkbox') {
                    $value = filter_var(
                        $value,
                        FILTER_VALIDATE_BOOLEAN
                    );
                }

                $updateData[$column] = $value;
            }

            // معالجة حالة السجل الافتراضية.
            $statusConfig = $this->config['status'] ?? [];

            $statusColumn = $statusConfig['column'] ?? null;
            $statusValue = $input['status'] ?? null;

            if (
                $statusColumn &&
                $statusValue !== null &&
                !isset($this->config['main_fields']['status']['column']) &&
                $this->tableHasColumn($table, $statusColumn)
            ) {

                $booleanMap = $statusConfig['boolean_map'] ?? [];

                if (is_array($booleanMap) && count($booleanMap)) {

                    if ($statusValue === ($booleanMap['true'] ?? 'blocked')) {
                        $updateData[$statusColumn] = true;
                    } elseif (
                        $statusValue === ($booleanMap['false'] ?? 'active') ||
                        $statusValue === 'inactive'
                    ) {
                        $updateData[$statusColumn] = false;
                    }
                }

                // التمييز بين نشط وغير نشط إذا كان العمود موجوداً.
                if ($this->tableHasColumn($table, 'is_active')) {

                    if ($statusValue === 'active') {
                        $updateData['is_active'] = true;
                    } elseif ($statusValue === 'inactive') {
                        $updateData['is_active'] = false;
                    }
                }
            }

            if (empty($updateData)) {
                throw ValidationException::withMessages([
                    'editingData' => 'لا توجد حقول صالحة للحفظ.',
                ]);
            }

            // تحديث وقت التعديل إذا كان العمود موجوداً.
            if ($this->tableHasColumn($table, 'updated_at')) {
                $updateData['updated_at'] = now();
            }

            // تحديث السجل داخل نطاق المؤسسة الحالية فقط.
            $updated = $this->baseQuery()
                ->where($primaryKey, $this->editingId)
                ->update($updateData);

            if (!$updated) {
                throw new \RuntimeException(
                    'لم يتم تحديث السجل. ربما لم تتغير البيانات.'
                );
            }

            // تنظيف حالة التعديل.
            $this->editingData = [];
            $this->editingId = null;
            // $this->modalType = null;

            $this->resetPage();

            $this->dispatch(
                'grid-refreshed',
                message: 'تم تعديل البيانات بنجاح.'
            );
        } catch (ValidationException $e) {

            throw $e;
        } catch (Throwable $e) {

            Log::error('[GenericGrid] saveEdit', [
                'entity' => $this->entityKey,
                'editing_id' => $this->editingId,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'grid-error',
                message: 'تعذر حفظ التعديلات. تحقق من البيانات وحاول مرة أخرى.'
            );
        }
    }

    /**
     * فتح نافذة تأكيد الحذف بعد التحقق من ملكية السجل.
     */
    public function openDelete(int $id): void
    {
        $this->authorizeRecord($id);

        $this->closeModal();

        $this->deletingId = $id;

        $this->modalType = 'delete';
    }


    /**
     * فتح نافذة تغيير الحالة بعد التحقق من ملكية السجل.
     */
    public function openToggle(int $id): void
    {
        $this->authorizeRecord($id);

        $this->closeModal();

        $this->togglingId = $id;

        $this->modalType = 'toggle';
    }


    /**
     * إغلاق جميع النوافذ المنبثقة وإعادة تعيين معرفات السجلات.
     */
    public function closeModal(): void
    {
        $this->viewingId = null;

        $this->editingId = null;

        $this->deletingId = null;

        $this->togglingId = null;

        $this->modalType = '';
    }


    /*
    |--------------------------------------------------------------------------
    | عمليات CRUD
    |--------------------------------------------------------------------------
    */

    /**
     * نقطة توافق مع أزرار الحفظ الموجودة في الواجهة.
     *
     * هذا المحرك مخصص للعرض والإدارة السريعة.
     * لا ينفذ الحفظ هنا؛ يجب توجيه الحفظ الفعلي إلى UniversalForm.
     */
    public function saveRecord(): void
    {
        $this->dispatch(
            'grid-error',
            message: 'عملية الحفظ تتم من خلال نموذج UniversalForm.'
        );
    }


    /**
     * تغيير حالة السجل بين نشط وغير نشط أو محظور.
     * يتم التحقق من ملكية السجل قبل التحديث، ويستخدم الاستعلام المحمي
     * مرة أخرى عند تنفيذ UPDATE لمنع التعديل على سجل مؤسسة أخرى.
     */
    public function toggleStatus(int $id): void
    {
        try {
            $record = $this->authorizeRecord($id);

            $primaryKey = $this->resolvePrimaryKey();

            $array = (array) $record;

            $updates = [];

            if ($this->hasColumn('is_blacklisted')) {
                $wasBlocked = (bool) ($array['is_blacklisted'] ?? false);

                $updates['is_blacklisted'] = !$wasBlocked;

                if ($this->hasColumn('is_active')) {
                    $updates['is_active'] = $wasBlocked;
                }

                if ($this->hasColumn('blacklisted_at')) {
                    $updates['blacklisted_at'] =
                        !$wasBlocked ? now() : null;
                }
            } elseif ($this->hasColumn('status')) {
                $currentStatus = $array['status'] ?? 'active';

                $updates['status'] =
                    $currentStatus === 'blocked'
                    ? 'active'
                    : 'blocked';
            } elseif ($this->hasColumn('is_active')) {
                $updates['is_active'] =
                    !((bool) ($array['is_active'] ?? true));
            } else {
                $this->dispatch(
                    'grid-error',
                    message: 'هذا السجل لا يحتوي على حقل حالة قابل للتغيير.'
                );

                return;
            }

            $updates = $this->addUpdatedAt($updates);

            $affected = $this->baseQuery()
                ->where($primaryKey, $id)
                ->update($updates);

            if ($affected < 1) {
                throw new AuthorizationException(
                    'تعذر تحديث السجل أو أنه لم يعد تابعاً للمؤسسة الحالية.'
                );
            }

            $this->closeModal();

            $this->dispatch(
                'grid-refreshed',
                message: 'تم تحديث الحالة بنجاح.'
            );
        } catch (AuthorizationException $e) {
            Log::warning('[GenericGrid] toggleStatus denied', [
                'entity' => $this->entityKey,
                'record_id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'grid-error',
                message: 'لا تملك صلاحية تغيير حالة هذا السجل.'
            );
        } catch (\Throwable $e) {
            Log::error('[GenericGrid] toggleStatus', [
                'entity' => $this->entityKey,
                'record_id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'grid-error',
                message: 'تعذر تغيير الحالة.'
            );
        }
    }


    /**
     * حذف السجل أو حذفه منطقياً حسب إعدادات الكيان.
     * لا يتم تنفيذ الحذف إلا بعد التحقق من أن السجل تابع للمؤسسة الحالية.
     */
    public function deleteRecord(int $id): void
    {
        try {
            $this->authorizeRecord($id);

            $primaryKey = $this->resolvePrimaryKey();

            $query = $this->baseQuery()
                ->where($primaryKey, $id);

            if ($this->config['has_soft_deletes'] ?? false) {
                $deletedColumn =
                    $this->config['deleted_at_column'] ?? 'deleted_at';

                if ($this->hasColumn($deletedColumn)) {
                    $updates = [
                        $deletedColumn => now(),
                    ];

                    $updates = $this->addUpdatedAt($updates);

                    $affected = $query->update($updates);
                } else {
                    $affected = $query->delete();
                }
            } else {
                $affected = $query->delete();
            }

            if ($affected < 1) {
                throw new AuthorizationException(
                    'تعذر حذف السجل أو أنه لم يعد تابعاً للمؤسسة الحالية.'
                );
            }

            $this->closeModal();

            $this->dispatch(
                'grid-refreshed',
                message: 'تم الحذف بنجاح.'
            );
        } catch (AuthorizationException $e) {
            Log::warning('[GenericGrid] deleteRecord denied', [
                'entity' => $this->entityKey,
                'record_id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'grid-error',
                message: 'لا تملك صلاحية حذف هذا السجل.'
            );
        } catch (\Throwable $e) {
            Log::error('[GenericGrid] deleteRecord', [
                'entity' => $this->entityKey,
                'record_id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'grid-error',
                message: 'تعذر حذف السجل.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | إدارة الفلاتر
    |--------------------------------------------------------------------------
    */

    /**
     * مسح البحث وإعادة الفلاتر إلى القيم الافتراضية.
     * ثم إعادة عرض الصفحة الأولى من النتائج.
     */
    public function clearFilters(): void
    {
        $this->search = '';

        $this->initializeFilters();

        $this->resetPage();
    }


    /*
    |--------------------------------------------------------------------------
    | العرض
    |--------------------------------------------------------------------------
    */

    /**
     * عرض واجهة GenericGrid وتمرير البيانات المحمية إليها.
     * كما يتم تحديد عنوان الصفحة من إعدادات الكيان.
     */
    public function render()
    {
        return view(
            'front-organizations.livewire.shared.generic-grid',
            [
                'config' => $this->config,

                'entityKey' => $this->entityKey,

                'records' => $this->records,

                'organizations' => $this->organizations,

                'labelMaps' => $this->labelMaps,

                'viewingRecord' => $this->viewingRecord,

                'editingRecord' => $this->editingRecord,

                'deletingRecord' => $this->deletingRecord,

                'modalType' => $this->modalType,
                'ownerOptions' => $this->entityKey === 'owner_agents'
                    ? $this->getOwnerOptions()
                    : [],
            ]
        );
    }
}
