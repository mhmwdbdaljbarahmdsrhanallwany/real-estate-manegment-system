<?php

namespace App\Http\Livewire\FrontOrganizations\Shared;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Illuminate\Auth\Access\AuthorizationException;
use App\Services\Access\OrganizationContextService;
class UniversalForm extends Component
{
    /*
    |--------------------------------------------------------------------------
    | الخصائص
    |--------------------------------------------------------------------------
    */

    
    public string $entityKey = 'owners';

    public array $config = [];
    public array $formData = [];
    public ?int $editingId = null;
    public string $formMode = 'create';
    public bool $showForm = false;
    public array $filters = [];
    public string $search = '';
    public int $perPage = 10;
    public int $currentPage = 1;
    public string $sort = 'newest';
    public array $relatedRecords = [];

    protected string $configFile =
    'Http/Livewire/FrontOrganizations/Shared/UniversalForm/configs.php';

    /*
    |--------------------------------------------------------------------------
    | التهيئة
    |--------------------------------------------------------------------------
    */
/*
|--------------------------------------------------------------------------
| استقبال أحداث GenericGrid
|--------------------------------------------------------------------------
*/

/**
 * استقبال طلب فتح نموذج الإضافة أو التعديل من GenericGrid.
 *
 * يتحقق من الكيان والوضع المطلوب، ثم يعيد تحميل إعدادات
 * الكيان من الملف الموثوق، ويستدعي create أو edit.
 *
 * عند التعديل، تقوم edit() بتحميل السجل من الاستعلام
 * المقيد بالمؤسسة الحالية، فلا يمكن تعديل سجل مؤسسة أخرى.
 */
#[On('open-universal-form')]
public function handleOpenUniversalForm(
    string $entityKey = 'owners',
    string $mode = 'create',
    ?int $id = null
): void {
    try {
        // التحقق من وجود سياق مؤسسة صالح.
        $this->currentOrganizationId();

        // منع استقبال اسم كيان غير صالح.
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $entityKey)) {
            throw new \InvalidArgumentException(
                'اسم الكيان المطلوب غير صالح.'
            );
        }

        // اعتماد الكيان المطلوب ثم تحميل إعداداته من الملف الموثوق.
        $this->entityKey = $entityKey;

        $this->loadConfiguration();

        // طلب فتح نموذج إضافة.
        if ($mode === 'create') {
            $this->initializeFilters();

            $this->create();

            return;
        }

        // طلب فتح نموذج تعديل.
        if ($mode === 'edit') {
            if (!$id || $id <= 0) {
                throw new \InvalidArgumentException(
                    'معرف السجل المطلوب تعديله غير صالح.'
                );
            }

            $this->edit($id);

            return;
        }

        throw new \InvalidArgumentException(
            'نوع العملية المطلوبة غير مدعوم.'
        );

    } catch (AuthorizationException $e) {
        Log::warning('[UniversalForm] Unauthorized form request', [
            'entity' => $entityKey,
            'mode' => $mode,
            'id' => $id,
            'error' => $e->getMessage(),
        ]);

        $this->dispatch(
            'uf-error',
            message: 'لا تملك صلاحية الوصول إلى هذا السجل.'
        );

    } catch (\Throwable $e) {
        Log::error('[UniversalForm] Open form error', [
            'entity' => $entityKey,
            'mode' => $mode,
            'id' => $id,
            'error' => $e->getMessage(),
        ]);

        $this->dispatch(
            'uf-error',
            message: 'تعذر فتح النموذج: ' . $e->getMessage()
        );
    }
}
    public function mount(string $entityKey = 'owners'): void
    {
        $this->entityKey = $entityKey;

        $this->loadConfiguration();

        $this->perPage = (int) (
            $this->config['pagination']['per_page'] ?? 10
        );

        $this->initializeFilters();

        $this->sort = $this->readSortFromFilters() ?? 'newest';
    }

    /**
     * إعادة تحميل الإعدادات الموثوقة بعد استعادة خصائص Livewire.
     * لا نعتمد على config المرسلة من المتصفح.
     */
    public function hydrate(): void
    {
        $this->loadConfiguration();
    }

    protected function loadConfiguration(): void
    {
        $path = app_path($this->configFile);

        if (!file_exists($path)) {
            throw new \RuntimeException(
                "ملف الإعدادات غير موجود: {$path}"
            );
        }

        $all = require $path;

        $defaults = $all['_defaults'] ?? [];
        $entity = $all[$this->entityKey] ?? [];

        if (empty($entity)) {
            abort(404, 'إعدادات القسم غير موجودة: ' . $this->entityKey);
        }

        $this->config = $this->mergeConfiguration(
            $defaults,
            $entity
        );

        $this->validateConfiguration();
    }

    protected function mergeConfiguration(
        array $defaults,
        array $custom
    ): array {
        foreach ($custom as $k => $v) {
            if (
                isset($defaults[$k]) &&
                is_array($defaults[$k]) &&
                is_array($v) &&
                !array_is_list($defaults[$k]) &&
                !array_is_list($v)
            ) {
                $defaults[$k] = $this->mergeConfiguration(
                    $defaults[$k],
                    $v
                );
            } else {
                $defaults[$k] = $v;
            }
        }

        return $defaults;
    }

    protected function validateConfiguration(): void
    {
        $table = $this->config['table'] ?? null;
        $pk = $this->config['primary_key'] ?? null;

        if (!$table || !$pk) {
            throw new \RuntimeException(
                'يجب تحديد table و primary_key.'
            );
        }

        $this->validateIdentifier($table);
        $this->validateIdentifier($pk);

        if (!empty($this->config['organization_column'])) {
            $this->validateIdentifier(
                $this->config['organization_column']
            );
        }
    }

    protected function validateIdentifier(string $id): void
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $id)) {
            throw new \InvalidArgumentException(
                'اسم جدول/عمود غير صالح: ' . $id
            );
        }
    }

    protected function getTable(): string
    {
        return $this->config['table'];
    }

    protected function getPrimaryKey(): string
    {
        return $this->config['primary_key'];
    }

    /**
     * الحصول على المؤسسة الحالية بعد التحقق من صلاحية المستخدم.
     */
    protected function currentOrganizationId(): int
    {
        $organizationService = app(
            OrganizationContextService::class
        );

        $organizationId = $organizationService->currentOrganizationId();

        if ($organizationId <= 0) {
            throw new AuthorizationException(
                'لا يوجد سياق مؤسسة صالح للوصول.'
            );
        }

        return $organizationId;
    }

    /**
     * تحديد عمود المؤسسة للكيان الحالي.
     */
    protected function getOrganizationColumn(): ?string
    {
        if ($this->getTable() === 'owners') {
            return 'organization_id';
        }

        $column = $this->config['organization_column'] ?? null;

        return $column ?: null;
    }

    /**
     * حماية بيانات الإدخال من تغيير المؤسسة.
     */
    protected function enforceOrganizationOnRecord(
        array $record
    ): array {
        $column = $this->getOrganizationColumn();

        if ($column) {
            unset($record[$column]);

            $record[$column] = $this->currentOrganizationId();
        }

        return $record;
    }

    /**
     * التحقق من ملكية سجل رئيسي.
     */
    protected function ensureParentBelongsToOrganization(
        int $parentId
    ): object {
        $parent = $this->baseQuery()
            ->where($this->getPrimaryKey(), $parentId)
            ->first();

        if (!$parent) {
            throw new AuthorizationException('لا يسمح لك بالوصول إلى هذا السجل.');
        }

        return $parent;
    }

    protected function initializeFilters(): void
    {
        foreach (($this->config['filters'] ?? []) as $f) {
            $key = $f['key'] ?? null;

            if (!$key) {
                continue;
            }

            $this->filters[$key] = $f['default'] ?? 'all';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | الاستعلامات الأساسية وعزل المؤسسات
    |--------------------------------------------------------------------------
    */

    protected function baseQuery()
    {
        $table = $this->getTable();

        $q = DB::table($table);

        // استبعاد السجلات المحذوفة
        if ($this->config['has_soft_deletes'] ?? false) {
            $deletedAtColumn =
                $this->config['deleted_at_column'] ?? 'deleted_at';

            $this->validateIdentifier($deletedAtColumn);

            $q->whereNull($table . '.' . $deletedAtColumn);
        }

        /*
         * جدول الملاك:
         * العزل المباشر باستخدام organization_id.
         */
        if ($table === 'owners') {
            $q->where(
                $table . '.organization_id',
                $this->currentOrganizationId()
            );

            return $q;
        }

        /*
         * الكيانات الأخرى التي تحتوي على عمود مؤسسة.
         */
        $organizationColumn = $this->getOrganizationColumn();

        if ($organizationColumn) {
            $this->validateIdentifier($organizationColumn);

            $q->where(
                $table . '.' . $organizationColumn,
                $this->currentOrganizationId()
            );

            return $q;
        }

        /*
         * منع تشغيل كيان معلن أنه تابع لمؤسسة
         * دون تحديد عمود العزل الخاص به.
         */
        if ($this->config['tenant_scoped'] ?? false) {
            throw new AuthorizationException(
                'إعداد عزل المؤسسة غير مكتمل لهذا الكيان.'
            );
        }

        return $q;
    }

    protected function applySearch($q)
    {
        $cfg = $this->config['search'] ?? [];

        if (
            !($cfg['enabled'] ?? true) ||
            trim($this->search) === ''
        ) {
            return $q;
        }

        $fields = $cfg['fields'] ?? [];

        if (empty($fields)) {
            return $q;
        }

        $term = trim($this->search);
        $table = $this->getTable();

        $q->where(function ($sq) use ($fields, $term, $table) {
            foreach ($fields as $f) {
                $this->validateIdentifier($f);

                $sq->orWhere(
                    $table . '.' . $f,
                    'LIKE',
                    "%{$term}%"
                );
            }
        });

        return $q;
    }

    protected function applyFilters($q)
    {
        foreach (($this->config['filters'] ?? []) as $filter) {
            $key = $filter['key'] ?? null;

            if (!$key) {
                continue;
            }

            $value = $this->filters[$key] ?? 'all';
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

            if ($type === 'relation_exists') {
                $relName = $filter['relation'] ?? null;

                $rel = $this->config['relations'][$relName] ?? null;

                if (!$rel) {
                    continue;
                }

                $relTable = $rel['table'];
                $relFk = $rel['foreign_key'];
                $parentTable = $this->getTable();
                $parentPk = $this->getPrimaryKey();

                $this->validateIdentifier($relTable);
                $this->validateIdentifier($relFk);

                if ($value === 'has' || $value === 'none') {
                    $callback = function ($sub) use (
                        $relTable,
                        $relFk,
                        $parentTable,
                        $parentPk,
                        $rel
                    ) {
                        $sub->select(DB::raw(1))
                            ->from($relTable)
                            ->whereColumn(
                                $relTable . '.' . $relFk,
                                $parentTable . '.' . $parentPk
                            );

                        if ($rel['has_soft_deletes'] ?? false) {
                            $deletedColumn =
                                $rel['deleted_at_column'] ?? 'deleted_at';

                            $sub->whereNull(
                                $relTable . '.' . $deletedColumn
                            );
                        }

                        if (!empty($rel['organization_column'])) {
                            $organizationColumn =
                                $rel['organization_column'];

                            $this->validateIdentifier(
                                $organizationColumn
                            );

                            $sub->where(
                                $relTable . '.' . $organizationColumn,
                                $this->currentOrganizationId()
                            );
                        }
                    };

                    if ($value === 'has') {
                        $q->whereExists($callback);
                    } else {
                        $q->whereNotExists($callback);
                    }
                }

                continue;
            }

            $column = $filter['column'] ?? $key;

            $this->validateIdentifier($column);

            $column = $this->getTable() . '.' . $column;

            if ($type === 'date') {
                if ($key === 'dateFrom') {
                    $q->whereDate($column, '>=', $value);
                } elseif ($key === 'dateTo') {
                    $q->whereDate($column, '<=', $value);
                } else {
                    $q->whereDate($column, $value);
                }

                continue;
            }

            if (
                $type === 'select' &&
                !($filter['virtual'] ?? false)
            ) {
                $operator = $filter['operator'] ?? '=';

                $allowedOperators = [
                    '=',
                    '!=',
                    '<>',
                    '>',
                    '<',
                    '>=',
                    '<=',
                ];

                if (!in_array($operator, $allowedOperators, true)) {
                    throw new \InvalidArgumentException(
                        'عامل تصفية غير مسموح.'
                    );
                }

                $q->where($column, $operator, $value);
            }
        }

        return $q;
    }

    protected function readSortFromFilters(): ?string
    {
        foreach (($this->config['filters'] ?? []) as $filter) {
            if (($filter['type'] ?? null) === 'sort') {
                $v = $this->filters[$filter['key']] ?? null;

                if ($v && $v !== 'all') {
                    return $v;
                }
            }
        }

        return null;
    }

    protected function applySorting($q)
    {
        $sort = $this->readSortFromFilters() ?? $this->sort;

        $nameField =
            $this->config['name_field'] ?? $this->getPrimaryKey();

        $pk = $this->getPrimaryKey();
        $table = $this->getTable();

        $this->validateIdentifier($nameField);

        switch ($sort) {
            case 'oldest':
                $q->orderBy($table . '.' . $pk, 'asc');
                break;

            case 'name_asc':
                $q->orderBy($table . '.' . $nameField, 'asc');
                break;

            case 'name_desc':
                $q->orderBy($table . '.' . $nameField, 'desc');
                break;

            default:
                $q->orderBy($table . '.' . $pk, 'desc');
                break;
        }

        return $q;
    }

    /*
    |--------------------------------------------------------------------------
    | الخصائص المحسوبة
    |--------------------------------------------------------------------------
    */

    public function getRecordsProperty(): array
    {
        $q = $this->baseQuery();

        $this->applySearch($q);
        $this->applyFilters($q);
        $this->applySorting($q);

        $pagination = $this->config['pagination'] ?? [];

        $records = ($pagination['enabled'] ?? true)
            ? $q->forPage(
                $this->currentPage,
                $this->perPage
            )->get()
            : $q->get();

        $pk = $this->getPrimaryKey();
        $out = [];

        foreach ($records as $row) {
            $item = (array) $row;

            $item['id'] = $item[$pk] ?? null;

            $item['_labels'] = $this->hydrateLabels($item);

            $this->hydrateStatus($item);

            $this->hydrateRelations($item);

            $out[] = $item;
        }

        return $out;
    }

    protected function hydrateLabels(array $item): array
    {
        $labels = [];

        foreach (($this->config['lookups'] ?? []) as $localKey => $lookup) {
            $fk = $item[$localKey] ?? null;

            if ($fk === null || $fk === '') {
                continue;
            }

            $table = $lookup['table'] ?? null;
            $key = $lookup['key'] ?? 'id';
            $display = $lookup['display'] ?? 'name';

            if (!$table) {
                continue;
            }

            $this->validateIdentifier($table);
            $this->validateIdentifier($key);
            $this->validateIdentifier($display);

            $q = DB::table($table)
                ->where($key, $fk);

            /*
             * تقييد القوائم التابعة للمؤسسة.
             */
            if (!empty($lookup['organization_column'])) {
                $column = $lookup['organization_column'];

                $this->validateIdentifier($column);

                $q->where(
                    $column,
                    $this->currentOrganizationId()
                );
            }

            /*
             * organization_id لا يعرض مؤسسة أخرى.
             */
            if ($localKey === 'organization_id') {
                $q->where($key, $this->currentOrganizationId());
            }

            $row = $q->first();

            if ($row) {
                $labels[$localKey] = $row->$display ?? null;
            }
        }

        return $labels;
    }

    protected function hydrateStatus(array &$item): void
    {
        if (isset($item['status']) && $item['status'] !== null) {
            return;
        }

        $statusCfg = $this->config['status'] ?? [];

        $statusCol = $statusCfg['column'] ?? 'status';

        $booleanMap = $statusCfg['boolean_map'] ?? null;

        if (!array_key_exists($statusCol, $item)) {
            return;
        }

        $raw = $item[$statusCol];

        if (is_array($booleanMap)) {
            $item['status'] = $raw
                ? ($booleanMap['true'] ?? 'active')
                : ($booleanMap['false'] ?? 'inactive');
        } else {
            $item['status'] = $raw;
        }
    }

    protected function hydrateRelations(array &$item): void
    {
        $pk = $this->getPrimaryKey();

        foreach (($this->config['relations'] ?? []) as $name => $rel) {
            if (!($rel['enabled'] ?? true)) {
                continue;
            }

            if (($rel['type'] ?? 'hasMany') !== 'hasMany') {
                continue;
            }

            $table = $rel['table'] ?? null;
            $fk = $rel['foreign_key'] ?? null;
            $rk = $rel['primary_key'] ?? 'id';

            if (!$table || !$fk) {
                continue;
            }

            $this->validateIdentifier($table);
            $this->validateIdentifier($fk);
            $this->validateIdentifier($rk);

            // التحقق من ملكية السجل الرئيسي
            $this->ensureParentBelongsToOrganization(
                (int) $item[$pk]
            );

            $q = DB::table($table)
                ->where($fk, $item[$pk]);

            if ($rel['has_soft_deletes'] ?? false) {
                $del = $rel['deleted_at_column'] ?? 'deleted_at';

                $q->whereNull($table . '.' . $del);
            }

            if (!empty($rel['organization_column'])) {
                $column = $rel['organization_column'];

                $this->validateIdentifier($column);

                $q->where(
                    $table . '.' . $column,
                    $this->currentOrganizationId()
                );
            }

            $rows = $q->orderByDesc($table . '.' . $rk)->get();

            $statusCol = $rel['status_column'] ?? null;

            $item[$name] = $rows->map(
                function ($r) use ($rk, $statusCol) {
                    $arr = (array) $r;

                    $arr['id'] = $arr[$rk];

                    if ($statusCol && !isset($arr['status'])) {
                        $arr['status'] =
                            !empty($arr[$statusCol])
                            ? 'active'
                            : 'blocked';
                    }

                    return $arr;
                }
            )->toArray();
        }
    }

    public function getRecordsMetaProperty(): array
    {
        $q = $this->baseQuery();

        $this->applySearch($q);
        $this->applyFilters($q);

        $total = $q->count();

        $enabled =
            $this->config['pagination']['enabled'] ?? true;

        return [
            'total' => $total,
            'per_page' => $enabled ? $this->perPage : $total,
            'current_page' => $this->currentPage,
            'last_page' => $enabled
                ? max(
                    1,
                    (int) ceil(
                        $total / max(1, $this->perPage)
                    )
                )
                : 1,
        ];
    }

    /**
     * الإحصائيات تحسب على جميع سجلات المؤسسة المطابقة للفلاتر.
     * لا تعتمد على سجلات الصفحة الحالية فقط.
     */
    public function getStatsProperty(): array
    {
        $q = $this->baseQuery();

        $this->applySearch($q);
        $this->applyFilters($q);

        $rows = $q->get();

        $records = [];

        foreach ($rows as $row) {
            $item = (array) $row;

            $this->hydrateStatus($item);

            $records[] = $item;
        }

        $result = [
            'total' => count($records),
        ];

        foreach (($this->config['stats'] ?? []) as $key => $def) {
            $type = $def['type'] ?? 'count_status';

            switch ($type) {
                case 'count_status':
                    $value = $def['value'] ?? null;

                    $result[$key] = $value !== null
                        ? count(array_filter(
                            $records,
                            fn($r) => ($r['status'] ?? null) === $value
                        ))
                        : 0;
                    break;

                case 'unique':
                    $field = $def['field'] ?? null;

                    $result[$key] = $field
                        ? count(array_unique(array_filter(
                            array_column($records, $field)
                        )))
                        : 0;
                    break;

                case 'sum_relation':
                    $rel = $def['relation'] ?? null;
                    $sum = 0;

                    if ($rel) {
                        foreach ($records as &$record) {
                            $this->hydrateRelations($record);
                            $sum += count($record[$rel] ?? []);
                        }

                        unset($record);
                    }

                    $result[$key] = $sum;
                    break;

                case 'count':
                    $result[$key] = count($records);
                    break;

                default:
                    $result[$key] = 0;
                    break;
            }
        }

        return $result;
    }

    public function getLabelsProperty(): array
    {
        $labels = [];

        $collect = function (array $fields) use (&$labels) {
            foreach ($fields as $f) {
                $k = $f['key'] ?? '';

                if (!$k || empty($f['options'])) {
                    continue;
                }

                $labels[$k] = [];

                foreach ($f['options'] as $o) {
                    $labels[$k][$o['value']] = $o['label'];
                }
            }
        };

        $collect($this->config['main_fields'] ?? []);

        $collect($this->config['sub_fields'] ?? []);

        foreach (($this->config['relations'] ?? []) as $rel) {
            $collect($rel['fields'] ?? []);
        }

        return $labels;
    }

    public function getLookupDataProperty(): array
    {
        $out = [];

        foreach (($this->config['lookups'] ?? []) as $key => $lookup) {
            $table = $lookup['table'] ?? null;
            $fkKey = $lookup['key'] ?? 'id';
            $display = $lookup['display'] ?? 'name';

            if (!$table) {
                continue;
            }

            $this->validateIdentifier($table);
            $this->validateIdentifier($fkKey);
            $this->validateIdentifier($display);

            $q = DB::table($table)
                ->select($fkKey, $display);

            /*
             * إذا كانت القائمة تابعة لمؤسسة،
             * يجب تحديد organization_column في إعداداتها.
             */
            if (!empty($lookup['organization_column'])) {
                $column = $lookup['organization_column'];

                $this->validateIdentifier($column);

                $q->where(
                    $column,
                    $this->currentOrganizationId()
                );
            }

            /*
             * منع عرض المؤسسات الأخرى في قائمة المؤسسة.
             */
            if ($key === 'organization_id') {
                $q->where(
                    $fkKey,
                    $this->currentOrganizationId()
                );
            }

            $rows = $q->get();

            $out[$key] = $rows->map(
                fn($r) => [
                    'id' => $r->$fkKey,
                    'label' => $r->$display,
                ]
            )->toArray();
        }

        return $out;
    }

    /*
    |--------------------------------------------------------------------------
    | التوافق مع تصميم المالكين
    |--------------------------------------------------------------------------
    */

    protected function transformLabelsForLegacy(
        array $labels
    ): array {
        return [
            'idType' => $labels['id_type'] ?? [],

            'payment' =>
            $labels['preferred_payment_method']
                ?? $labels['payment']
                ?? [],

            'agent' =>
            $labels['role']
                ?? $labels['type']
                ?? [],
        ];
    }

    protected function getOrganizationsArrayProperty(): array
    {
        return collect(
            $this->lookupData['organization_id'] ?? []
        )->map(
            fn($o) => [
                'id' => $o['id'],
                'name' => $o['label'],
            ]
        )->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | الترقيم
    |--------------------------------------------------------------------------
    */

    public function goToPage(int $page): void
    {
        $meta = $this->recordsMeta;

        $this->currentPage = max(
            1,
            min($page, $meta['last_page'])
        );
    }

    public function nextPage(): void
    {
        $this->goToPage($this->currentPage + 1);
    }

    public function previousPage(): void
    {
        $this->goToPage($this->currentPage - 1);
    }

    public function updatedPerPage($value): void
    {
        $opts =
            $this->config['pagination']['options']
            ?? [10, 25, 50, 100];

        $value = (int) $value;

        $this->perPage = in_array($value, $opts, true)
            ? $value
            : (int) (
                $this->config['pagination']['per_page'] ?? 10
            );

        $this->currentPage = 1;
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
    }

    public function updatedFilters(): void
    {
        $this->currentPage = 1;
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD عام
    |--------------------------------------------------------------------------
    */

    protected function getValidationRules(array $fields): array
    {
        $rules = [];

        foreach ($fields as $f) {
            $k = $f['key'] ?? null;

            if (!$k) {
                continue;
            }

            if (!empty($f['validation'])) {
                $rules[$k] = $f['validation'];
            } elseif ($f['required'] ?? false) {
                $rules[$k] =
                    'required' .
                    (($f['type'] ?? '') === 'email' ? '|email' : '');
            } else {
                $rules[$k] =
                    'nullable' .
                    (($f['type'] ?? '') === 'email' ? '|email' : '');
            }
        }

        return $rules;
    }

    protected function prepareRecordData(
        array $data,
        array $fields
    ): array {
        $record = [];

        foreach ($fields as $f) {
            $k = $f['key'] ?? null;

            if (!$k) {
                continue;
            }

            $col = $f['column'] ?? $k;

            $this->validateIdentifier($col);

            if ($f['virtual'] ?? false) {
                continue;
            }

            if (
                ($f['is_status'] ?? false) === true &&
                !isset($f['column'])
            ) {
                continue;
            }

            if (array_key_exists($k, $data)) {
                $record[$col] =
                    $data[$k] === '' ? null : $data[$k];
            }
        }

        return $record;
    }

    public function saveRecord(
        array $data = [],
        ?int $id = null
    ): void {
        try {
            // التحقق من المؤسسة قبل أي عملية كتابة
            $organizationId = $this->currentOrganizationId();

            $fields = $this->config['main_fields'] ?? [];

            $v = Validator::make(
                $data,
                $this->getValidationRules($fields)
            );

            if ($v->fails()) {
                $this->dispatch(
                    'uf-error',
                    message: $v->errors()->first()
                );

                return;
            }

            $record = $this->prepareRecordData(
                $data,
                $fields
            );

            // معالجة الحالات المنطقية
            $statusCfg = $this->config['status'] ?? [];

            if (
                isset($statusCfg['boolean_map']) &&
                is_array($statusCfg['boolean_map'])
            ) {
                $status = $data['status'] ?? null;

                if ($status !== null) {
                    if ($status === 'blocked') {
                        $record['is_blacklisted'] = true;
                        $record['is_active'] = false;
                        $record['blacklisted_at'] = now();
                    } elseif ($status === 'inactive') {
                        $record['is_blacklisted'] = false;
                        $record['is_active'] = false;
                        $record['blacklisted_at'] = null;
                    } else {
                        $record['is_blacklisted'] = false;
                        $record['is_active'] = true;
                        $record['blacklisted_at'] = null;
                    }
                }
            }

            if (empty($record)) {
                $this->dispatch(
                    'uf-error',
                    message: 'لا توجد بيانات صالحة.'
                );

                return;
            }

            $table = $this->getTable();
            $pk = $this->getPrimaryKey();

            // منع التلاعب بعمود المؤسسة
            $record = $this->enforceOrganizationOnRecord($record);

            if ($id) {
                /*
                 * التعديل يتم من خلال الاستعلام المحمي نفسه.
                 */
                $record['updated_at'] = now();

                $updated = $this->baseQuery()
                    ->where($pk, $id)
                    ->update($record);

                if (!$updated) {
                    throw new AuthorizationException(
                        'السجل غير موجود أو لا يتبع مؤسستك.'
                    );
                }

                $newId = $id;
                $msg = 'تم تحديث البيانات بنجاح ✓';
            } else {
                $record['created_at'] = now();
                $record['updated_at'] = now();

                $newId = DB::table($table)->insertGetId($record);

                $msg = 'تمت الإضافة بنجاح ✓';
            }

            $this->closeForm();

            $this->dispatchGenericUpdated($newId, $msg);
        } catch (ValidationException $e) {
            throw $e;
        } catch (AuthorizationException $e) {
            Log::warning('UF save authorization denied', [
                'entity' => $this->entityKey,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'لا تملك صلاحية الوصول إلى هذا السجل.'
            );
        } catch (\Throwable $e) {
            Log::error('UF save error', [
                'entity' => $this->entityKey,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر حفظ البيانات: ' . $e->getMessage()
            );
        }
    }

    public function deleteRecord(int $id): void
    {
        try {
            $table = $this->getTable();
            $pk = $this->getPrimaryKey();

            $q = $this->baseQuery()->where($pk, $id);

            if ($this->config['has_soft_deletes'] ?? false) {
                $col =
                    $this->config['deleted_at_column']
                    ?? 'deleted_at';

                $affected = $q->update([
                    $col => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $affected = $q->delete();
            }

            if (!$affected) {
                throw new AuthorizationException(
                    'السجل غير موجود أو لا يتبع مؤسستك.'
                );
            }

            $this->dispatchGenericUpdated(
                null,
                'تم الحذف بنجاح ✓'
            );
        } catch (\Throwable $e) {
            Log::error('UF delete error', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر الحذف أو لا تملك صلاحية الوصول.'
            );
        }
    }

    public function changeRecordStatus(
        int $id,
        string $status
    ): void {
        try {
            $cfg = $this->config['status'] ?? [];

            $column = $cfg['column'] ?? 'status';

            $this->validateIdentifier($column);

            $allowed = $cfg['values'] ?? [];

            if (
                !empty($allowed) &&
                !in_array($status, $allowed, true)
            ) {
                throw new \InvalidArgumentException(
                    'حالة غير مسموحة.'
                );
            }

            $update = [
                $column => $status,
                'updated_at' => now(),
            ];

            if (
                isset($cfg['boolean_map']) &&
                is_array($cfg['boolean_map'])
            ) {
                if ($status === 'blocked') {
                    $update['is_blacklisted'] = true;
                    $update['is_active'] = false;
                    $update['blacklisted_at'] = now();
                } elseif ($status === 'inactive') {
                    $update['is_blacklisted'] = false;
                    $update['is_active'] = false;
                    $update['blacklisted_at'] = null;
                } else {
                    $update['is_blacklisted'] = false;
                    $update['is_active'] = true;
                    $update['blacklisted_at'] = null;
                }

                unset($update[$column]);
            }

            $affected = $this->baseQuery()
                ->where($this->getPrimaryKey(), $id)
                ->update($update);

            if (!$affected) {
                throw new AuthorizationException(
                    'السجل غير موجود أو لا يتبع مؤسستك.'
                );
            }

            $this->dispatchGenericUpdated(
                $id,
                'تم تحديث الحالة بنجاح ✓'
            );
        } catch (\Throwable $e) {
            Log::error('UF status error', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر تحديث الحالة أو لا تملك الصلاحية.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD العلاقات
    |--------------------------------------------------------------------------
    */

    public function loadRelations(int $parentId): array
    {
        $result = [];

        // لا يتم تحميل أي علاقة قبل التحقق من ملكية السجل الرئيسي
        $this->ensureParentBelongsToOrganization($parentId);

        foreach (($this->config['relations'] ?? []) as $name => $rel) {
            if (!($rel['enabled'] ?? true)) {
                continue;
            }

            if (($rel['type'] ?? 'hasMany') !== 'hasMany') {
                continue;
            }

            $table = $rel['table'] ?? null;
            $fk = $rel['foreign_key'] ?? null;
            $pk = $rel['primary_key'] ?? 'id';

            if (!$table || !$fk) {
                continue;
            }

            $this->validateIdentifier($table);
            $this->validateIdentifier($fk);
            $this->validateIdentifier($pk);

            $q = DB::table($table)
                ->where($fk, $parentId);

            if ($rel['has_soft_deletes'] ?? false) {
                $q->whereNull(
                    $table . '.' .
                        ($rel['deleted_at_column'] ?? 'deleted_at')
                );
            }

            if (!empty($rel['organization_column'])) {
                $column = $rel['organization_column'];

                $this->validateIdentifier($column);

                $q->where(
                    $table . '.' . $column,
                    $this->currentOrganizationId()
                );
            }

            $rows = $q->orderByDesc($table . '.' . $pk)->get();

            $statusCol = $rel['status_column'] ?? null;

            $result[$name] = $rows->map(
                function ($r) use ($pk, $statusCol) {
                    $arr = (array) $r;

                    $arr['id'] = $arr[$pk];

                    if ($statusCol && !isset($arr['status'])) {
                        $arr['status'] =
                            !empty($arr[$statusCol])
                            ? 'active'
                            : 'blocked';
                    }

                    return $arr;
                }
            )->toArray();
        }

        $this->relatedRecords = $result;

        return $result;
    }

    public function saveRelatedRecord(
        string $relationName,
        int $parentId,
        array $data,
        ?int $relatedId = null
    ): void {
        try {
            // التحقق من ملكية المالك قبل التعامل مع الوكلاء
            $this->ensureParentBelongsToOrganization($parentId);

            $rel = $this->config['relations'][$relationName] ?? null;

            if (!$rel || !($rel['enabled'] ?? true)) {
                throw new \RuntimeException(
                    'العلاقة غير معرفة.'
                );
            }

            $table = $rel['table'];
            $pk = $rel['primary_key'];
            $fk = $rel['foreign_key'];

            $this->validateIdentifier($table);
            $this->validateIdentifier($pk);
            $this->validateIdentifier($fk);

            $fields = $rel['fields'] ?? [];

            $v = Validator::make(
                $data,
                $this->getValidationRules($fields)
            );

            if ($v->fails()) {
                $this->dispatch(
                    'uf-error',
                    message: $v->errors()->first()
                );

                return;
            }

            $record = $this->prepareRecordData(
                $data,
                $fields
            );

            // لا نثق في parent_id المرسل من المتصفح
            $record[$fk] = $parentId;

            // إذا كان الجدول التابع يحتوي على عمود مؤسسة
            if (!empty($rel['organization_column'])) {
                $column = $rel['organization_column'];

                $this->validateIdentifier($column);

                unset($record[$column]);

                $record[$column] = $this->currentOrganizationId();
            }

            if ($relatedId) {
                $q = DB::table($table)
                    ->where($pk, $relatedId)
                    ->where($fk, $parentId);

                if (!empty($rel['organization_column'])) {
                    $q->where(
                        $rel['organization_column'],
                        $this->currentOrganizationId()
                    );
                }

                $updated = $q->update(
                    array_merge(
                        $record,
                        ['updated_at' => now()]
                    )
                );

                if (!$updated) {
                    throw new AuthorizationException(
                        'الوكيل غير موجود أو لا يتبع هذا المالك.'
                    );
                }

                $msg = 'تم التحديث ✓';
            } else {
                $record['created_at'] = now();
                $record['updated_at'] = now();

                $statusCol = $rel['status_column'] ?? null;

                if (
                    $statusCol &&
                    !array_key_exists($statusCol, $record)
                ) {
                    $record[$statusCol] = true;
                }

                DB::table($table)->insert($record);

                $msg = 'تمت الإضافة ✓';
            }

            $this->loadRelations($parentId);

            $this->dispatchGenericUpdated($parentId, $msg);
        } catch (\Throwable $e) {
            Log::error('UF save relation error', [
                'relation' => $relationName,
                'parent_id' => $parentId,
                'related_id' => $relatedId,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر حفظ السجل المرتبط أو لا تملك الصلاحية.'
            );
        }
    }

    public function deleteRelatedRecord(
        string $relationName,
        int $parentId,
        int $relatedId
    ): void {
        try {
            $this->ensureParentBelongsToOrganization($parentId);

            $rel = $this->config['relations'][$relationName] ?? null;

            if (!$rel || !($rel['enabled'] ?? true)) {
                return;
            }

            $table = $rel['table'];
            $pk = $rel['primary_key'];
            $fk = $rel['foreign_key'];

            $this->validateIdentifier($table);
            $this->validateIdentifier($pk);
            $this->validateIdentifier($fk);

            $q = DB::table($table)
                ->where($pk, $relatedId)
                ->where($fk, $parentId);

            if (!empty($rel['organization_column'])) {
                $q->where(
                    $rel['organization_column'],
                    $this->currentOrganizationId()
                );
            }

            if ($rel['has_soft_deletes'] ?? false) {
                $affected = $q->update([
                    ($rel['deleted_at_column'] ?? 'deleted_at') => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $affected = $q->delete();
            }

            if (!$affected) {
                throw new AuthorizationException(
                    'السجل المرتبط غير موجود أو غير تابع لهذا المالك.'
                );
            }

            $this->loadRelations($parentId);

            $this->dispatchGenericUpdated(
                $parentId,
                'تم الحذف ✓'
            );
        } catch (\Throwable $e) {
            Log::error('UF delete relation error', [
                'relation' => $relationName,
                'parent_id' => $parentId,
                'related_id' => $relatedId,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر الحذف أو لا تملك الصلاحية.'
            );
        }
    }

    public function changeRelatedStatus(
        string $relationName,
        int $parentId,
        int $relatedId
    ): void {
        try {
            $this->ensureParentBelongsToOrganization($parentId);

            $rel = $this->config['relations'][$relationName] ?? null;

            if (!$rel || !($rel['enabled'] ?? true)) {
                return;
            }

            $table = $rel['table'];
            $pk = $rel['primary_key'];
            $fk = $rel['foreign_key'];

            $statusCol = $rel['status_column'] ?? 'is_active';

            $this->validateIdentifier($table);
            $this->validateIdentifier($pk);
            $this->validateIdentifier($fk);
            $this->validateIdentifier($statusCol);

            $q = DB::table($table)
                ->where($pk, $relatedId)
                ->where($fk, $parentId);

            if (!empty($rel['organization_column'])) {
                $q->where(
                    $rel['organization_column'],
                    $this->currentOrganizationId()
                );
            }

            $row = $q->first();

            if (!$row) {
                throw new AuthorizationException(
                    'السجل المرتبط غير موجود أو غير تابع لهذا المالك.'
                );
            }

            $q->update([
                $statusCol => !$row->$statusCol,
                'updated_at' => now(),
            ]);

            $this->loadRelations($parentId);

            $this->dispatchGenericUpdated(
                $parentId,
                'تم تحديث الحالة ✓'
            );
        } catch (\Throwable $e) {
            Log::error('UF relation status error', [
                'relation' => $relationName,
                'parent_id' => $parentId,
                'related_id' => $relatedId,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر تحديث الحالة أو لا تملك الصلاحية.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | دوال التوافق مع التصميم الحالي
    |--------------------------------------------------------------------------
    */

    public function saveOwner(
        array $data,
        ?int $id = null
    ): void {
        $this->saveRecord($data, $id);
    }

    public function deleteOwner(int $id): void
    {
        $this->deleteRecord($id);
    }

    public function blockOwner(int $id): void
    {
        $rec = $this->baseQuery()
            ->where($this->getPrimaryKey(), $id)
            ->first();

        if (!$rec) {
            return;
        }

        $arr = (array) $rec;

        $this->hydrateStatus($arr);

        $currentStatus = $arr['status'] ?? 'active';

        $nextStatus = $currentStatus === 'blocked'
            ? 'active'
            : 'blocked';

        $this->changeRecordStatus($id, $nextStatus);
    }

    public function saveAgent(
        int $ownerId,
        array $data,
        ?int $agentId = null
    ): void {
        $this->saveRelatedRecord(
            'agents',
            $ownerId,
            $data,
            $agentId
        );
    }

    public function deleteAgent(
        int $agentId,
        int $ownerId
    ): void {
        $this->deleteRelatedRecord(
            'agents',
            $ownerId,
            $agentId
        );
    }

    public function blockAgent(
        int $agentId,
        int $ownerId
    ): void {
        $this->changeRelatedStatus(
            'agents',
            $ownerId,
            $agentId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | النموذج
    |--------------------------------------------------------------------------
    */

    public function create(): void
    {
        // التحقق من سياق المؤسسة قبل فتح النموذج
        $this->currentOrganizationId();

        $this->formData = [];

        foreach (($this->config['main_fields'] ?? []) as $f) {
            if (!empty($f['key'])) {
                $this->formData[$f['key']] =
                    $f['default'] ?? null;
            }
        }

        // لا نسمح بتحديد المؤسسة من النموذج
        if ($this->getOrganizationColumn()) {
            unset($this->formData[$this->getOrganizationColumn()]);
        }

        $this->editingId = null;
        $this->formMode = 'create';
        $this->showForm = true;
    }

    public function edit(int $id): bool
    {
        try {
            $rec = $this->baseQuery()
                ->where($this->getPrimaryKey(), $id)
                ->first();

            if (!$rec) {
                $this->dispatch(
                    'uf-error',
                    message: 'السجل غير موجود أو لا يتبع مؤسستك.'
                );

                return false;
            }

            $this->formData = (array) $rec;

            // منع تغيير المؤسسة من بيانات النموذج
            if ($this->getOrganizationColumn()) {
                unset(
                    $this->formData[$this->getOrganizationColumn()]
                );
            }

            $this->editingId = $id;
            $this->formMode = 'edit';
            $this->showForm = true;

            return true;
        } catch (\Throwable $e) {
            Log::error('UF edit error', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر تحميل السجل.'
            );

            return false;
        }
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->formData = [];
        $this->editingId = null;
        $this->formMode = 'create';

        $this->resetValidation();
    }

    /*
    |--------------------------------------------------------------------------
    | الأحداث — إطلاق الحدثين للتوافق الكامل
    |--------------------------------------------------------------------------
    */

    protected function dispatchGenericUpdated(
        ?int $keepRecord = null,
        string $message = ''
    ): void {
        // الحدث العام
        $this->dispatch(
            'uf-records-updated',
            records: $this->records,
            stats: $this->stats,
            labels: $this->labels,
            lookupData: $this->lookupData,
            relatedRecords: $this->relatedRecords,
            keepRecord: $keepRecord,
            message: $message
        );

        // حدث التوافق مع تصميم المالكين
        $legacyLabels = $this->transformLabelsForLegacy(
            $this->labels
        );

        $organizations = $this->getOrganizationsArrayProperty();

        $this->dispatch(
            'uf-data-updated',
            owners: $this->records,
            stats: $this->stats,
            organizations: $organizations,
            idTypeLabels: $legacyLabels['idType'],
            paymentLabels: $legacyLabels['payment'],
            agentTypeLabels: $legacyLabels['agent'],
            keepOwner: $keepRecord,
            message: $message
        );
    }

    public function refreshData(): void
    {
        $this->currentOrganizationId();

        $this->currentPage = 1;

        unset(
            $this->records,
            $this->recordsMeta,
            $this->stats,
            $this->labels,
            $this->lookupData
        );

        $this->dispatch(
            'uf-refreshed',
            message: 'تم التحديث.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | العرض — تمرير كل ما يحتاجه التصميم
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        // التحقق من المؤسسة في كل عملية عرض
        $this->currentOrganizationId();

        $owners = $this->records;

        $organizations = $this->getOrganizationsArrayProperty();

        $legacyLabels = $this->transformLabelsForLegacy(
            $this->labels
        );

        return view(
            'front-organizations.livewire.shared.universal-form',
            [
                // البيانات العامة
                'entityKey' => $this->entityKey,
                'config' => $this->config,
                'records' => $this->records,
                'recordsMeta' => $this->recordsMeta,
                'stats' => $this->stats,
                'labels' => $this->labels,
                'lookupData' => $this->lookupData,

                // التوافق مع تصميم المالكين
                'owners' => $owners,
                'organizations' => $organizations,
                'legacyLabels' => $legacyLabels,
            ]
        )->layout('front-organizations.layout.master');
    }
}
