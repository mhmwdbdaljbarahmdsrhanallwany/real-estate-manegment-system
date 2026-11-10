<?php

namespace App\Http\Livewire\FrontOrganizations\Shared;

use App\Services\Access\OrganizationContextService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Throwable;

/**
 * UniversalForm - محرك CRUD ديناميكي للمؤسسات.
 * ينفذ عمليات البيانات والبحث والفلاتر والنماذج والعلاقات والملفات بواسطة Livewire.
 */
class UniversalForm extends Component
{
    use WithFileUploads;
    use WithPagination;

    #[Locked]
    public string $entityKey = 'owners';

    #[Locked]
    public array $config = [];

    #[Locked]
    public ?int $organizationId = null;

    public array $form = [];
    public array $childForm = [];
    public array $mainUploads = [];
    public array $childUploads = [];
    public array $filters = [];

    public string $search = '';
    public string $childSearch = '';
    public int $perPage = 10;
    public ?int $selectedId = null;
    public ?int $editingId = null;
    public ?int $editingChildId = null;

    public bool $showRecordModal = false;
    public bool $showChildModal = false;
    public bool $showChildViewModal = false;
    public bool $showDeleteModal = false;
    public bool $showDeleteChildModal = false;
    public bool $showBlockModal = false;
    public bool $showBlockChildModal = false;

    public ?int $pendingDeleteId = null;
    public ?int $pendingDeleteChildId = null;
    public ?int $pendingBlockChildId = null;
    public ?array $viewingChild = null;
    public ?string $toastMessage = null;
    public string $toastType = 'success';

    // تهيئة المكوّن وتحميل إعدادات الكيان وتحديد المؤسسة والسجل الأول.
    public function mount(string $entityKey = 'owners'): void
    {
        $this->entityKey = $entityKey;
        $this->config = $this->loadEntityConfig($entityKey);
        $this->organizationId = $this->resolveOrganizationId();
        $this->assertTenantSchema($this->config['table'], $this->tenantConfig());

        $this->perPage = (int) ($this->config['pagination']['per_page'] ?? 10);

        foreach (($this->config['filters'] ?? []) as $filter) {
            $key = $filter['key'] ?? null;
            if ($key) {
                $this->filters[$key] = $filter['default'] ?? 'all';
            }
        }

        $first = $this->entityQuery()->orderByDesc($this->config['primary_key'])->first();
        $this->selectedId = $first ? (int) $first->{$this->config['primary_key']} : null;
    }

    /* ============================================================
     | إعدادات الكيان وعزل المؤسسة
     * ============================================================ */

    // تحميل إعدادات الكيان والتحقق من اسم الجدول والمفتاح الأساسي ووجودهما في قاعدة البيانات.
    protected function loadEntityConfig(string $entityKey): array
    {
        $path = __DIR__ . '/UniversalForm/configs.php';

        if (!is_file($path)) {
            throw new \RuntimeException('ملف إعدادات UniversalForm غير موجود: ' . $path);
        }

        $all = require $path;

        if (!is_array($all) || !isset($all[$entityKey]) || !is_array($all[$entityKey])) {
            abort(404, "الكيان [{$entityKey}] غير معرّف في configs.php");
        }

        $config = $this->mergeConfig($all['_defaults'] ?? [], $all[$entityKey]);

        foreach (['table', 'primary_key'] as $required) {
            if (empty($config[$required])) {
                throw new AuthorizationException("الإعداد [{$required}] مطلوب للكيان.");
            }

            $this->assertSafeIdentifier($config[$required]);
        }

        if (
            !Schema::hasTable($config['table']) ||
            !Schema::hasColumn($config['table'], $config['primary_key'])
        ) {
            throw new AuthorizationException('جدول الكيان أو مفتاحه الأساسي غير موجود في قاعدة البيانات.');
        }

        return $config;
    }

    // دمج الإعدادات الافتراضية مع إعدادات الكيان مع السماح باستبدال القوائم كاملة.
    protected function mergeConfig(array $defaults, array $custom): array
    {
        foreach ($custom as $key => $value) {
            if (is_array($value) && isset($defaults[$key]) && is_array($defaults[$key])) {
                $isList = array_is_list($value);

                $defaults[$key] = $isList
                    ? $value
                    : $this->mergeConfig($defaults[$key], $value);
            } else {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }

    // استخراج رقم المؤسسة الحالية من خدمة سياق الوصول ومنع العمل دون مؤسسة محددة.
    protected function resolveOrganizationId(): int
    {
        $id = app(OrganizationContextService::class)->currentOrganizationId();

        if (!$id) {
            throw new AuthorizationException('تعذر تحديد المؤسسة الحالية من سياق الوصول.');
        }

        return (int) $id;
    }

    // التحقق من أن اسم الجدول أو العمود يتكون من أحرف وأرقام وشرطة سفلية فقط.
    protected function assertSafeIdentifier(string $identifier): void
    {
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $identifier)) {
            throw new AuthorizationException('اسم جدول أو عمود غير صالح في إعدادات الكيان.');
        }
    }

    // تحديد سياسة عزل المؤسسة سواء كانت مباشرة أو عبر علاقة أو غير مطبقة.
    protected function tenantConfig(?array $cfg = null): array
    {
        $cfg ??= $this->config;

        if (isset($cfg['tenant']) && is_array($cfg['tenant'])) {
            $tenant = $cfg['tenant'];

            if (!in_array($tenant['type'] ?? null, ['direct', 'related', 'none'], true)) {
                throw new AuthorizationException('نوع سياسة عزل المؤسسة غير صحيح.');
            }

            return $tenant;
        }

        if (($cfg['tenant_scope']['type'] ?? null) === 'belongs_to') {
            $scope = $cfg['tenant_scope'];

            return [
                'type' => 'related',
                'path' => [[
                    'table' => $scope['table'] ?? null,
                    'foreign_key' => $scope['local_key'] ?? null,
                    'owner_key' => $scope['owner_key'] ?? null,
                    'deleted_at_column' => $scope['parent_deleted_column'] ?? 'deleted_at',
                    'has_soft_deletes' => (bool) ($scope['parent_soft_deletes'] ?? false),
                ]],
                'organization_column' => $scope['organization_column'] ?? 'organization_id',
            ];
        }

        if (!empty($cfg['tenant_scoped'])) {
            $column = $cfg['organization_column'] ?? $cfg['tenant_column'] ?? null;

            if (!$column) {
                throw new AuthorizationException('لم يتم تحديد organization_column لعزل الكيان.');
            }

            return ['type' => 'direct', 'column' => $column];
        }

        if (array_key_exists('tenant_scoped', $cfg) && $cfg['tenant_scoped'] === false) {
            return ['type' => 'none'];
        }

        if (($cfg['tenant_type'] ?? null) === 'none') {
            return ['type' => 'none'];
        }

        throw new AuthorizationException('لم يتم تعريف سياسة عزل المؤسسة لهذا الكيان.');
    }

    // التحقق من وجود جداول وأعمدة ومسارات العلاقات المطلوبة لتطبيق عزل المؤسسة.
    protected function assertTenantSchema(string $table, array $tenant): void
    {
        $this->assertSafeIdentifier($table);

        if (!Schema::hasTable($table)) {
            throw new AuthorizationException("الجدول [{$table}] غير موجود.");
        }

        if (($tenant['type'] ?? null) === 'direct') {
            $column = $tenant['column'] ?? null;

            if (!$column) {
                throw new AuthorizationException('عمود المؤسسة غير محدد.');
            }

            $this->assertSafeIdentifier($column);

            if (!Schema::hasColumn($table, $column)) {
                throw new AuthorizationException("عمود المؤسسة [{$column}] غير موجود في [{$table}].");
            }
        }

        if (($tenant['type'] ?? null) === 'related') {
            $current = $table;

            foreach (($tenant['path'] ?? []) as $step) {
                $parent = $step['table'] ?? null;
                $foreign = $step['foreign_key'] ?? null;
                $owner = $step['owner_key'] ?? null;

                foreach ([$parent, $foreign, $owner] as $identifier) {
                    if (!$identifier) {
                        throw new AuthorizationException('مسار علاقة المؤسسة غير مكتمل.');
                    }

                    $this->assertSafeIdentifier($identifier);
                }

                if (
                    !Schema::hasTable($parent) ||
                    !Schema::hasColumn($current, $foreign) ||
                    !Schema::hasColumn($parent, $owner)
                ) {
                    throw new AuthorizationException('مسار علاقة المؤسسة لا يطابق قاعدة البيانات.');
                }

                if (!empty($step['has_soft_deletes'])) {
                    $deleted = $step['deleted_at_column'] ?? 'deleted_at';

                    $this->assertSafeIdentifier($deleted);

                    if (!Schema::hasColumn($parent, $deleted)) {
                        throw new AuthorizationException("عمود الحذف الناعم [{$deleted}] غير موجود.");
                    }
                }

                $current = $parent;
            }

            $orgColumn = $tenant['organization_column'] ?? null;

            if (!$orgColumn) {
                throw new AuthorizationException('عمود المؤسسة في نهاية مسار العلاقة غير محدد.');
            }

            $this->assertSafeIdentifier($orgColumn);

            if (!Schema::hasColumn($current, $orgColumn)) {
                throw new AuthorizationException("عمود المؤسسة [{$orgColumn}] غير موجود في [{$current}].");
            }
        }
    }

    // تطبيق شرط المؤسسة على الاستعلام وفق سياسة العزل المحددة للكيان.
    protected function applyTenantScope($query, string $table, ?array $cfg = null)
    {
        $tenant = $this->tenantConfig($cfg);
        $this->assertTenantSchema($table, $tenant);

        if ($tenant['type'] === 'none') {
            return $query;
        }

        if (!$this->organizationId) {
            throw new AuthorizationException('تعذر تحديد المؤسسة الحالية.');
        }

        if ($tenant['type'] === 'direct') {
            return $query->where($table . '.' . $tenant['column'], $this->organizationId);
        }

        $this->applyRelatedPath(
            $query,
            $table,
            $table,
            $tenant['path'],
            0,
            $tenant['organization_column']
        );

        return $query;
    }

    // بناء شروط EXISTS المتداخلة للتحقق من انتماء السجل إلى المؤسسة عبر سلسلة علاقات.
    protected function applyRelatedPath(
        $query,
        string $currentTable,
        string $currentAlias,
        array $path,
        int $index,
        string $organizationColumn
    ): void {
        if (!isset($path[$index])) {
            throw new AuthorizationException('مسار علاقة المؤسسة غير صالح.');
        }

        $step = $path[$index];
        $parentTable = $step['table'];
        $parentAlias = 'uf_scope_' . $index;

        $query->whereExists(function ($sub) use (
            $step,
            $parentTable,
            $parentAlias,
            $currentAlias,
            $path,
            $index,
            $organizationColumn
        ) {
            $sub->selectRaw('1')
                ->from($parentTable . ' as ' . $parentAlias)
                ->whereColumn(
                    $parentAlias . '.' . $step['owner_key'],
                    $currentAlias . '.' . $step['foreign_key']
                );

            if (!empty($step['has_soft_deletes'])) {
                $sub->whereNull($parentAlias . '.' . ($step['deleted_at_column'] ?? 'deleted_at'));
            }

            if ($index === count($path) - 1) {
                $sub->where($parentAlias . '.' . $organizationColumn, $this->organizationId);
            } else {
                $this->applyRelatedPath(
                    $sub,
                    $parentTable,
                    $parentAlias,
                    $path,
                    $index + 1,
                    $organizationColumn
                );
            }
        });
    }

    // إنشاء الاستعلام الأساسي للكيان مع تطبيق الحذف الناعم وعزل المؤسسة.
    protected function entityQuery()
    {
        $table = $this->config['table'];
        $query = DB::table($table);

        if (!empty($this->config['has_soft_deletes'])) {
            $deleted = $this->config['deleted_at_column'] ?? 'deleted_at';

            $this->assertSafeIdentifier($deleted);

            if (!Schema::hasColumn($table, $deleted)) {
                throw new AuthorizationException("عمود الحذف الناعم [{$deleted}] غير موجود.");
            }

            $query->whereNull($table . '.' . $deleted);
        }

        return $this->applyTenantScope($query, $table);
    }

    // جلب سجل واحد بعد التأكد من أنه يتبع المؤسسة الحالية.
    protected function findScopedRecord(int|string $id): object
    {
        $record = $this->entityQuery()
            ->where($this->config['table'] . '.' . $this->config['primary_key'], $id)
            ->first();

        if (!$record) {
            throw new AuthorizationException('السجل غير موجود أو لا يتبع المؤسسة الحالية.');
        }

        return $record;
    }

    /* ============================================================
     | البحث والفلاتر والبيانات المعروضة
     * ============================================================ */

    // إنشاء استعلام السجلات مع تطبيق البحث والفلاتر والترتيب.
    protected function filteredQuery()
    {
        $table = $this->config['table'];
        $query = $this->entityQuery();

        $term = trim($this->search);

        if ($term !== '' && !empty($this->config['search']['enabled'])) {
            $fields = $this->config['search']['fields'] ?? [];

            $query->where(function ($sub) use ($fields, $table, $term) {
                foreach ($fields as $field) {
                    $this->assertSafeIdentifier($field);

                    if (Schema::hasColumn($table, $field)) {
                        $sub->orWhere($table . '.' . $field, 'LIKE', '%' . $term . '%');
                    }
                }
            });
        }

        foreach (($this->config['filters'] ?? []) as $filter) {
            $key = $filter['key'] ?? null;

            if (!$key || !array_key_exists($key, $this->filters)) {
                continue;
            }

            $value = $this->filters[$key];
            $type = $filter['type'] ?? 'select';

            if ($value === null || $value === '' || $value === 'all' || $type === 'sort' || $type === 'search') {
                continue;
            }

            if ($type === 'date') {
                $column = $filter['column'] ?? $key;
                $this->assertSafeIdentifier($column);

                if (!Schema::hasColumn($table, $column)) {
                    continue;
                }

                if ($key === 'dateFrom') {
                    $query->whereDate($table . '.' . $column, '>=', $value);
                } elseif ($key === 'dateTo') {
                    $query->whereDate($table . '.' . $column, '<=', $value);
                } else {
                    $query->whereDate($table . '.' . $column, '=', $value);
                }

                continue;
            }

            if ($type === 'relation_exists') {
                $relationKey = $filter['relation'] ?? null;
                $relation = $relationKey ? ($this->config['relations'][$relationKey] ?? null) : null;

                if (!$relation || empty($relation['enabled']) || ($relation['type'] ?? 'hasMany') !== 'hasMany') {
                    continue;
                }

                $childTable = $relation['table'];
                $foreignKey = $relation['foreign_key'];
                $parentKey = $this->config['primary_key'];

                foreach ([$childTable, $foreignKey, $parentKey] as $identifier) {
                    $this->assertSafeIdentifier($identifier);
                }

                $exists = function ($sub) use ($childTable, $foreignKey, $table, $parentKey, $relation) {
                    $sub->selectRaw('1')
                        ->from($childTable)
                        ->whereColumn($childTable . '.' . $foreignKey, $table . '.' . $parentKey);

                    if (!empty($relation['has_soft_deletes'])) {
                        $deleted = $relation['deleted_at_column'] ?? 'deleted_at';
                        $sub->whereNull($childTable . '.' . $deleted);
                    }
                };

                if ($value === 'has') {
                    $query->whereExists($exists);
                } elseif ($value === 'none') {
                    $query->whereNotExists($exists);
                }

                continue;
            }

            if ($type === 'text') {
                $column = $filter['column'] ?? $key;
                $this->assertSafeIdentifier($column);

                if (Schema::hasColumn($table, $column)) {
                    $query->where($table . '.' . $column, 'LIKE', '%' . $value . '%');
                }

                continue;
            }

            if ($type === 'select') {
                $status = $this->config['status'] ?? [];
                $column = $filter['column'] ?? ($key === 'status' ? ($status['column'] ?? $key) : $key);

                $this->assertSafeIdentifier($column);

                if (!Schema::hasColumn($table, $column)) {
                    continue;
                }

                if ($key === 'status' && ($status['column'] ?? null) === $column && !empty($status['boolean_map'])) {
                    $dbValue = array_search($value, $status['boolean_map'], true);

                    if ($dbValue !== false) {
                        $query->where($table . '.' . $column, filter_var($dbValue, FILTER_VALIDATE_BOOLEAN));
                    } elseif (in_array((string) $value, ['0', '1'], true)) {
                        $query->where($table . '.' . $column, (int) $value);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                } elseif (in_array($value, ['1', '0'], true) && in_array($column, ['is_active', 'is_blacklisted'], true)) {
                    $query->where($table . '.' . $column, (int) $value);
                } else {
                    $query->where($table . '.' . $column, $value);
                }
            }
        }

        $sort = $this->filters['sort'] ?? 'newest';
        $pk = $this->config['primary_key'];
        $nameField = $this->config['name_field'] ?? $pk;

        if (in_array($sort, ['name_asc', 'name_desc'], true) && Schema::hasColumn($table, $nameField)) {
            $query->orderBy($table . '.' . $nameField, $sort === 'name_asc' ? 'asc' : 'desc');
        } else {
            $dateColumn = Schema::hasColumn($table, 'created_at') ? 'created_at' : $pk;
            $query->orderBy($table . '.' . $dateColumn, $sort === 'oldest' ? 'asc' : 'desc');
        }

        return $query;
    }

    // جلب السجلات مع الترقيم وإضافة اسم الحالة ومفتاحها لكل سجل.
    public function getRecordsProperty(): LengthAwarePaginator
    {
        $pagination = $this->config['pagination'] ?? [];
        $enabled = $pagination['enabled'] ?? true;
        $perPage = max(1, min(100, $this->perPage));

        if (!$enabled) {
            $perPage = 1000;
        }

        $paginator = $this->filteredQuery()->paginate($perPage);
        $status = $this->config['status'] ?? [];
        $column = $status['column'] ?? 'status';

        $paginator->getCollection()->transform(function ($row) use ($status, $column) {
            $row->status_label = $this->resolveStatusLabel($row->{$column} ?? null, $status);
            $row->status_key = $this->resolveStatusKey($row->{$column} ?? null, $status);

            return $row;
        });

        return $paginator;
    }

    // جلب السجل المحدد وتحميل علاقاته التابعة وعلاقاته المرجعية.
    public function getSelectedRecordProperty(): ?array
    {
        if (!$this->selectedId) {
            return null;
        }

        try {
            $row = (array) $this->findScopedRecord($this->selectedId);
        } catch (AuthorizationException) {
            return null;
        }

        $status = $this->config['status'] ?? [];
        $statusColumn = $status['column'] ?? 'status';

        $row['status_key'] = $this->resolveStatusKey($row[$statusColumn] ?? null, $status);
        $row['status_label'] = $this->resolveStatusLabel($row[$statusColumn] ?? null, $status);

        foreach (($this->config['relations'] ?? []) as $key => $relation) {
            if (empty($relation['enabled'])) {
                continue;
            }

            $type = $relation['type'] ?? 'hasMany';

            if ($type === 'belongsTo') {
                $foreignKey = $relation['foreign_key'] ?? $key;
                $relatedTable = $relation['table'] ?? null;
                $relatedKey = $relation['primary_key'] ?? 'id';

                if ($relatedTable && !empty($row[$foreignKey]) && Schema::hasTable($relatedTable)) {
                    $this->assertSafeIdentifier($relatedTable);
                    $this->assertSafeIdentifier($relatedKey);

                    $relatedQuery = DB::table($relatedTable)->where($relatedKey, $row[$foreignKey]);

                    if ($relatedTable !== 'organizations' && Schema::hasColumn($relatedTable, 'organization_id')) {
                        $relatedQuery->where($relatedTable . '.organization_id', $this->organizationId);
                    } elseif ($relatedTable === 'organizations' && $relatedKey === 'organization_id') {
                        $relatedQuery->where($relatedKey, $this->organizationId);
                    }

                    if (Schema::hasColumn($relatedTable, 'deleted_at')) {
                        $relatedQuery->whereNull($relatedTable . '.deleted_at');
                    }

                    $row['_relation_' . $key] = (array) ($relatedQuery->first() ?? new \stdClass());
                }
            } elseif ($type === 'hasMany') {
                $row[$key] = $this->loadRelation($relation, $row[$this->config['primary_key']]);
            }
        }

        return $row;
    }

    // إرجاع عناصر العلاقة التابعة للسجل المحدد.
    public function getSelectedChildrenProperty(): array
    {
        $record = $this->selectedRecord;

        if (!$record) {
            return [];
        }

        $key = $this->firstCrudRelationKey();

        return $key ? ($record[$key] ?? []) : [];
    }

    // تحميل القوائم المرجعية مع تطبيق عزل المؤسسة على السجلات المرتبطة.
    public function getLookupsProperty(): array
    {
        $result = [];

        foreach (($this->config['lookups'] ?? []) as $name => $lookup) {
            $table = $lookup['table'] ?? null;
            $key = $lookup['key'] ?? null;
            $display = $lookup['display'] ?? null;

            if (!$table || !$key || !$display) {
                throw new AuthorizationException("إعداد القائمة [{$name}] غير مكتمل.");
            }

            foreach ([$table, $key, $display] as $identifier) {
                $this->assertSafeIdentifier($identifier);
            }

            if (
                !Schema::hasTable($table) ||
                !Schema::hasColumn($table, $key) ||
                !Schema::hasColumn($table, $display)
            ) {
                throw new AuthorizationException("جدول أو أعمدة القائمة [{$name}] غير موجودة.");
            }

            $query = DB::table($table);

            if (!empty($lookup['organization_column']) && Schema::hasColumn($table, $lookup['organization_column'])) {
                $query->where($lookup['organization_column'], $this->organizationId);
            } elseif ($table !== 'organizations' && Schema::hasColumn($table, 'organization_id')) {
                $query->where($table . '.organization_id', $this->organizationId);
            }

            if ($table === 'organizations' && $key === 'organization_id') {
                $query->where($table . '.' . $key, $this->organizationId);
            }

            $result[$name] = $query
                ->select($table . '.' . $key . ' as id', $table . '.' . $display . ' as name')
                ->orderBy($table . '.' . $display)
                ->get()
                ->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])
                ->all();
        }

        return $result;
    }

    // حساب الإحصائيات المعرفة في إعدادات الكيان مثل الإجمالي والحالات والقيم الفريدة.
    public function getStatsProperty(): array
    {
        $allRows = $this->entityQuery()->get()->map(fn ($row) => (array) $row)->all();
        $status = $this->config['status'] ?? [];

        foreach ($allRows as &$row) {
            $row['_status'] = $this->resolveStatusKey($row[$status['column'] ?? 'status'] ?? null, $status);
        }

        unset($row);

        $output = [];

        foreach (($this->config['stats'] ?? []) as $key => $stat) {
            $type = $stat['type'] ?? 'total';
            $value = 0;

            if (in_array($type, ['total', 'count'], true) && empty($stat['field'])) {
                $value = count($allRows);
            } elseif ($type === 'count_status') {
                $value = count(array_filter(
                    $allRows,
                    fn ($row) => ($row['_status'] ?? null) === ($stat['value'] ?? null)
                ));
            } elseif ($type === 'count' && !empty($stat['field'])) {
                $field = $stat['field'];

                $value = count(array_filter(
                    $allRows,
                    fn ($row) => (string) ($row[$field] ?? '') === (string) ($stat['value'] ?? '')
                ));
            } elseif ($type === 'unique') {
                $field = $stat['field'] ?? null;
                $values = array_filter(
                    array_column($allRows, $field),
                    fn ($item) => $item !== null && $item !== ''
                );

                $value = count(array_unique($values));
            } elseif ($type === 'sum_relation') {
                $relationKey = $stat['relation'] ?? null;
                $relation = $relationKey ? ($this->config['relations'][$relationKey] ?? null) : null;

                if ($relation && ($relation['type'] ?? 'hasMany') === 'hasMany') {
                    $table = $relation['table'];
                    $foreign = $relation['foreign_key'];
                    $parentIds = array_column($allRows, $this->config['primary_key']);

                    if ($parentIds) {
                        $childQuery = DB::table($table)->whereIn($foreign, $parentIds);

                        if (!empty($relation['has_soft_deletes'])) {
                            $childQuery->whereNull($relation['deleted_at_column'] ?? 'deleted_at');
                        }

                        $value = $childQuery->count();
                    }
                }
            }

            $output[$key] = [
                'label' => $stat['label'] ?? '',
                'icon' => $stat['icon'] ?? 'fas fa-chart-simple',
                'color' => $stat['color'] ?? 'royal',
                'value' => $value,
            ];
        }

        return $output;
    }

    // تحويل القيمة المخزنة في قاعدة البيانات إلى مفتاح حالة موحد للاستخدام في الواجهة.
    protected function resolveStatusKey(mixed $raw, array $status): string
    {
        $map = $status['boolean_map'] ?? null;

        if ($map) {
            $boolean = in_array($raw, [true, 1, '1', 'true', 'TRUE', 'yes', 'on'], true);

            return (string) ($map[$boolean ? 'true' : 'false'] ?? ($boolean ? 'active' : 'inactive'));
        }

        $allowed = $status['values'] ?? ['active', 'inactive', 'blocked'];

        if (is_string($raw) && in_array($raw, $allowed, true)) {
            return $raw;
        }

        return $raw ? 'active' : 'inactive';
    }

    // إرجاع النص العربي المقابل لمفتاح الحالة وفق إعدادات الكيان.
    protected function resolveStatusLabel(mixed $raw, array $status): string
    {
        $key = $this->resolveStatusKey($raw, $status);

        return $status['labels'][$key] ?? match ($key) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'blocked' => 'محظور',
            default => $key,
        };
    }

    // تحميل السجلات التابعة لعلاقة HasMany وتحويل قيم الحالة إلى مفاتيح موحدة.
    protected function loadRelation(array $relation, int|string $parentId): array
    {
        $table = $relation['table'] ?? null;
        $foreign = $relation['foreign_key'] ?? null;
        $pk = $relation['primary_key'] ?? null;

        if (!$table || !$foreign || !$pk) {
            throw new AuthorizationException('إعداد العلاقة التابعة غير مكتمل.');
        }

        foreach ([$table, $foreign, $pk] as $identifier) {
            $this->assertSafeIdentifier($identifier);
        }

        if (
            !Schema::hasTable($table) ||
            !Schema::hasColumn($table, $foreign) ||
            !Schema::hasColumn($table, $pk)
        ) {
            throw new AuthorizationException('جدول العلاقة أو مفاتيحه غير موجودة.');
        }

        $query = DB::table($table)->where($foreign, $parentId);

        if (!empty($relation['has_soft_deletes'])) {
            $query->whereNull($relation['deleted_at_column'] ?? 'deleted_at');
        }

        $rows = $query->orderBy($pk)->get();

        return $rows->map(function ($row) use ($relation) {
            $item = (array) $row;
            $statusColumn = $relation['status_column'] ?? null;

            if ($statusColumn && array_key_exists($statusColumn, $item)) {
                $map = $relation['status_map'] ?? $relation['boolean_map'] ?? [];
                $raw = $item[$statusColumn];

                $isBoolMap = $map && (
                    isset($map['true']) ||
                    isset($map['false']) ||
                    isset($map[1]) ||
                    isset($map[0])
                );

                if ($isBoolMap) {
                    $bool = in_array($raw, [true, 1, '1', 'true', 'TRUE'], true);
                    $item['status'] = $map[$bool ? 'true' : 'false'] ?? ($bool ? 'active' : 'inactive');
                } else {
                    $item['status'] = in_array($raw, ['active', 'inactive', 'blocked'], true)
                        ? $raw
                        : ((bool) $raw ? 'active' : 'inactive');
                }
            }

            return $item;
        })->all();
    }

    // تحديد أول علاقة HasMany مفعلة لاستخدامها في عمليات إدارة العناصر التابعة.
    protected function firstCrudRelationKey(): ?string
    {
        foreach (($this->config['relations'] ?? []) as $key => $relation) {
            if (!empty($relation['enabled']) && ($relation['type'] ?? 'hasMany') === 'hasMany') {
                return (string) $key;
            }
        }

        return null;
    }

    // تحديد السجل النشط في الواجهة والتحقق من صلاحيته ضمن المؤسسة الحالية.
    public function selectRecord(int $id): void
    {
        $this->findScopedRecord($id);
        $this->selectedId = $id;
        $this->resetValidation();
    }

    // إعادة قيم جميع الفلاتر والبحث إلى إعداداتها الافتراضية.
    public function clearFilters(): void
    {
        $this->search = '';

        foreach (($this->config['filters'] ?? []) as $filter) {
            if (!empty($filter['key'])) {
                $this->filters[$filter['key']] = $filter['default'] ?? 'all';
            }
        }

        $this->resetPage();
    }

    // إعادة صفحة النتائج الأولى عند تغيير نص البحث.
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // إعادة صفحة النتائج الأولى عند تغيير أي فلتر.
    public function updatedFilters(): void
    {
        $this->resetPage();
    }

    // إعادة صفحة النتائج الأولى عند تغيير عدد السجلات المعروضة.
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /* ============================================================
     | النماذج الرئيسية
     * ============================================================ */

    // فتح نموذج إضافة أو تعديل سجل وتجهيز الحقول والملفات وإحداثيات الخريطة.
    public function openRecordModal(?int $id = null): void
    {
        $this->resetValidation();
        $this->editingId = $id;
        $this->mainUploads = [];
        $fields = $this->config['main_fields'] ?? [];
        $record = $id ? (array) $this->findScopedRecord($id) : [];
        $this->form = [];

        foreach ($fields as $field) {
            $key = $field['key'] ?? null;

            if (!$key) {
                continue;
            }

            if (($field['type'] ?? '') === 'file') {
                continue;
            }

            $value = $record[$key] ?? $field['default'] ?? '';

            if (!empty($field['is_status'])) {
                $status = $this->config['status'] ?? [];
                $statusColumn = $status['column'] ?? $key;

                if ($id && isset($record[$statusColumn])) {
                    if ($key === $statusColumn && !empty($status['boolean_map'])) {
                        $value = in_array($record[$statusColumn], [true, 1, '1', 'true'], true) ? 1 : 0;
                    } else {
                        $value = $this->resolveStatusKey($record[$statusColumn], $status);
                    }
                }
            }

            $this->form[$key] = $value;
        }

        $tenant = $this->tenantConfig();

        if ($tenant['type'] === 'direct') {
            $this->form[$tenant['column']] = $this->organizationId;
        }

        $this->showRecordModal = true;
        $location = $this->config['location_picker'] ?? [];

        if (!empty($location['enabled'])) {
            $currentLat = $this->form[$location['latitude_field']] ?? null;
            $currentLng = $this->form[$location['longitude_field']] ?? null;

            $this->dispatch(
                'uf-map-refresh',
                lat: is_numeric($currentLat) ? (float) $currentLat : (float) ($location['default_latitude'] ?? 24.7136),
                lng: is_numeric($currentLng) ? (float) $currentLng : (float) ($location['default_longitude'] ?? 46.6753)
            );
        }
    }

    // إغلاق نموذج السجل الرئيسي ومسح الملفات المؤقتة وأخطاء التحقق.
    public function closeRecordModal(): void
    {
        $this->showRecordModal = false;
        $this->mainUploads = [];
        $this->resetValidation();
    }

    // التحقق من بيانات السجل ورفع ملفاته ثم إنشاء السجل أو تحديثه داخل المؤسسة الحالية.
    public function saveRecord(): void
    {
        $stored = [];
        $this->normalizeCoordinates();

        try {
            $table = $this->config['table'];
            $pk = $this->config['primary_key'];
            $fields = $this->config['main_fields'] ?? [];
            $existing = $this->editingId ? (array) $this->findScopedRecord($this->editingId) : [];

            $payload = $this->validateConfiguredFields($this->form, $fields, $table, true);

            $filePayload = $this->storeConfiguredUploads(
                $fields,
                'mainUploads',
                $this->editingId === null,
                $existing,
                'main',
                $stored
            );

            $payload = array_merge($payload, $filePayload);
            $this->applyStatusToPayload($payload, $this->form, $fields, $table);

            $tenant = $this->tenantConfig();

            if ($tenant['type'] === 'direct') {
                $payload[$tenant['column']] = $this->organizationId;
            } elseif ($tenant['type'] === 'related') {
                $foreign = $tenant['path'][0]['foreign_key'] ?? null;

                if (!$foreign || empty($payload[$foreign])) {
                    if (!$this->editingId) {
                        throw new AuthorizationException('يجب تحديد السجل المرتبط بالمؤسسة.');
                    }
                } else {
                    $this->validateRelatedForeignKey($tenant, $payload[$foreign]);
                }
            }

            $this->validateLookupFields($fields, $payload);

            if (Schema::hasColumn($table, 'updated_at')) {
                $payload['updated_at'] = now();
            }

            $savedId = DB::transaction(function () use ($table, $pk, $payload, $existing) {
                if ($this->editingId) {
                    $this->entityQuery()
                        ->where($pk, $this->editingId)
                        ->update($payload);

                    return $this->editingId;
                }

                if (Schema::hasColumn($table, 'created_at')) {
                    $payload['created_at'] = now();
                }

                return (int) DB::table($table)->insertGetId($payload, $pk);
            });

            $this->deleteReplacedUploads($fields, $existing, $filePayload, 'main');
            $this->selectedId = (int) $savedId;
            $this->showRecordModal = false;
            $this->mainUploads = [];
            $this->editingId = null;
            $this->form = [];

            $this->setToast($existing ? 'تم تحديث البيانات بنجاح.' : 'تمت إضافة البيانات بنجاح.');
            $this->resetPage();
        } catch (ValidationException $e) {
            $this->rollbackStoredUploads($stored);
            throw $e;
        } catch (Throwable $e) {
            $this->rollbackStoredUploads($stored);
            report($e);

            $this->setToast(
                $e instanceof AuthorizationException
                    ? $e->getMessage()
                    : 'تعذر حفظ البيانات. راجع سجل الأخطاء.',
                'error'
            );
        }
    }

    // فتح نافذة تأكيد حذف السجل بعد التحقق من انتمائه للمؤسسة الحالية.
    public function openDeleteModal(int $id): void
    {
        $this->findScopedRecord($id);
        $this->pendingDeleteId = $id;
        $this->showDeleteModal = true;
    }

    // حذف السجل المحدد حذفاً ناعماً أو نهائياً حسب إعدادات الكيان.
    public function deleteRecord(): void
    {
        try {
            $id = $this->pendingDeleteId;

            if (!$id) {
                return;
            }

            $this->findScopedRecord($id);
            $table = $this->config['table'];
            $pk = $this->config['primary_key'];

            if (!empty($this->config['has_soft_deletes'])) {
                $deleted = $this->config['deleted_at_column'] ?? 'deleted_at';

                $this->entityQuery()
                    ->where($pk, $id)
                    ->update([$deleted => now()]);
            } else {
                $this->entityQuery()
                    ->where($pk, $id)
                    ->delete();
            }

            if ($this->selectedId === $id) {
                $next = $this->entityQuery()->orderByDesc($pk)->value($pk);
                $this->selectedId = $next ? (int) $next : null;
            }

            $this->showDeleteModal = false;
            $this->pendingDeleteId = null;
            $this->setToast('تم حذف السجل بنجاح.');
        } catch (Throwable $e) {
            report($e);

            $this->setToast(
                $e instanceof AuthorizationException ? $e->getMessage() : 'تعذر حذف السجل.',
                'error'
            );
        }
    }

    // تغيير حالة السجل بين نشط ومحظور وفق إعدادات الحالة الحالية للكيان.
    public function toggleRecordStatus(int $id): void
    {
        try {
            $row = $this->findScopedRecord($id);
            $status = $this->config['status'] ?? [];
            $column = $status['column'] ?? null;

            if (!$column || !Schema::hasColumn($this->config['table'], $column)) {
                throw new \RuntimeException('لم يتم إعداد عمود حالة صحيح لهذا الكيان.');
            }

            $this->assertSafeIdentifier($column);
            $current = $row->{$column};
            $booleanMap = $status['boolean_map'] ?? null;

            if ($booleanMap) {
                $newValue = !in_array($current, [true, 1, '1', 'true', 'TRUE'], true);
            } else {
                $newValue = $current === 'blocked' ? 'active' : 'blocked';
            }

            $payload = [$column => $newValue];

            if (Schema::hasColumn($this->config['table'], 'updated_at')) {
                $payload['updated_at'] = now();
            }

            $this->entityQuery()
                ->where($this->config['primary_key'], $id)
                ->update($payload);

            $this->setToast(
                $this->resolveStatusLabel($newValue, $status) === 'محظور'
                    ? 'تم حظر السجل.'
                    : 'تم تحديث حالة السجل.'
            );
        } catch (Throwable $e) {
            report($e);

            $this->setToast(
                $e instanceof AuthorizationException ? $e->getMessage() : 'تعذر تغيير حالة السجل.',
                'error'
            );
        }
    }

    /* ============================================================
     | العلاقات التابعة (HasMany فقط)
     * ============================================================ */

    // فتح نموذج إضافة أو تعديل عنصر تابع وتجهيز حقوله وملفاته.
    public function openChildModal(?int $childId = null): void
    {
        $relationKey = $this->firstCrudRelationKey();

        if (!$relationKey || !$this->selectedId) {
            $this->setToast('اختر السجل الرئيسي أولاً.', 'error');
            return;
        }

        $this->resetValidation();
        $this->editingChildId = $childId;
        $this->childUploads = [];
        $relation = $this->config['relations'][$relationKey];
        $record = [];

        if ($childId) {
            $record = $this->findChildRecord($this->selectedId, $childId, $relation);
        }

        $this->childForm = [];

        foreach (($relation['fields'] ?? []) as $field) {
            $key = $field['key'] ?? null;

            if (!$key || ($field['type'] ?? '') === 'file') {
                continue;
            }

            $this->childForm[$key] = $record[$key] ?? $field['default'] ?? '';
        }

        $this->showChildModal = true;
    }

    // إغلاق نموذج العنصر التابع ومسح الملفات المؤقتة وأخطاء التحقق.
    public function closeChildModal(): void
    {
        $this->showChildModal = false;
        $this->childUploads = [];
        $this->resetValidation();
    }

    // التحقق من بيانات العنصر التابع ورفع ملفاته ثم إضافته أو تحديثه للسجل الرئيسي.
    public function saveChild(): void
    {
        $stored = [];

        try {
            if (!$this->selectedId) {
                throw new AuthorizationException('اختر السجل الرئيسي أولاً.');
            }

            $this->findScopedRecord($this->selectedId);
            $relationKey = $this->firstCrudRelationKey();

            if (!$relationKey) {
                throw new \RuntimeException('لا توجد علاقة فرعية قابلة للإدارة.');
            }

            $relation = $this->config['relations'][$relationKey];
            $table = $relation['table'];
            $pk = $relation['primary_key'];
            $foreign = $relation['foreign_key'];

            $existing = $this->editingChildId
                ? $this->findChildRecord($this->selectedId, $this->editingChildId, $relation)
                : [];

            $payload = $this->validateConfiguredFields(
                $this->childForm,
                $relation['fields'] ?? [],
                $table,
                false
            );

            $filePayload = $this->storeConfiguredUploads(
                $relation['fields'] ?? [],
                'childUploads',
                $this->editingChildId === null,
                $existing,
                $relationKey,
                $stored
            );

            $payload = array_merge($payload, $filePayload);
            $payload[$foreign] = $this->selectedId;

            if (Schema::hasColumn($table, 'updated_at')) {
                $payload['updated_at'] = now();
            }

            DB::transaction(function () use ($table, $pk, $foreign, $payload) {
                if ($this->editingChildId) {
                    DB::table($table)
                        ->where($pk, $this->editingChildId)
                        ->where($foreign, $this->selectedId)
                        ->update($payload);
                } else {
                    if (Schema::hasColumn($table, 'created_at')) {
                        $payload['created_at'] = now();
                    }

                    DB::table($table)->insert($payload);
                }
            });

            $this->deleteReplacedUploads($relation['fields'] ?? [], $existing, $filePayload, $relationKey);

            $this->showChildModal = false;
            $this->childUploads = [];
            $this->childForm = [];
            $this->editingChildId = null;

            $this->setToast($existing ? 'تم تحديث بيانات العنصر التابع.' : 'تمت إضافة العنصر التابع.');
        } catch (ValidationException $e) {
            $this->rollbackStoredUploads($stored);
            throw $e;
        } catch (Throwable $e) {
            $this->rollbackStoredUploads($stored);
            report($e);

            $this->setToast(
                $e instanceof AuthorizationException ? $e->getMessage() : 'تعذر حفظ العنصر التابع.',
                'error'
            );
        }
    }

    // جلب عنصر تابع والتحقق من ارتباطه بالسجل الرئيسي المحدد.
    protected function findChildRecord(int $parentId, int $childId, array $relation): array
    {
        $query = DB::table($relation['table'])
            ->where($relation['primary_key'], $childId)
            ->where($relation['foreign_key'], $parentId);

        if (!empty($relation['has_soft_deletes'])) {
            $query->whereNull($relation['deleted_at_column'] ?? 'deleted_at');
        }

        $row = $query->first();

        if (!$row) {
            throw new AuthorizationException('العنصر التابع غير موجود أو لا يتبع السجل المحدد.');
        }

        return (array) $row;
    }

    // فتح نافذة عرض تفاصيل العنصر التابع بعد التحقق من ارتباطه بالسجل المحدد.
    public function viewChild(int $childId): void
    {
        if (!$this->selectedId) {
            return;
        }

        $relationKey = $this->firstCrudRelationKey();

        if (!$relationKey) {
            return;
        }

        $relation = $this->config['relations'][$relationKey];
        $this->viewingChild = $this->findChildRecord($this->selectedId, $childId, $relation);
        $this->showChildViewModal = true;
    }

    // فتح نافذة تأكيد حذف العنصر التابع بعد التحقق من ارتباطه بالسجل الرئيسي.
    public function openDeleteChildModal(int $childId): void
    {
        if (!$this->selectedId) {
            return;
        }

        $relationKey = $this->firstCrudRelationKey();

        if (!$relationKey) {
            return;
        }

        $this->findChildRecord(
            $this->selectedId,
            $childId,
            $this->config['relations'][$relationKey]
        );

        $this->pendingDeleteChildId = $childId;
        $this->showDeleteChildModal = true;
    }

    // حذف العنصر التابع المحدد حذفاً ناعماً أو نهائياً حسب إعداد العلاقة.
    public function deleteChild(): void
    {
        try {
            if (!$this->selectedId || !$this->pendingDeleteChildId) {
                return;
            }

            $relationKey = $this->firstCrudRelationKey();

            if (!$relationKey) {
                return;
            }

            $relation = $this->config['relations'][$relationKey];

            $this->findScopedRecord($this->selectedId);
            $this->findChildRecord($this->selectedId, $this->pendingDeleteChildId, $relation);

            $query = DB::table($relation['table'])
                ->where($relation['primary_key'], $this->pendingDeleteChildId)
                ->where($relation['foreign_key'], $this->selectedId);

            if (!empty($relation['has_soft_deletes'])) {
                $deleted = $relation['deleted_at_column'] ?? 'deleted_at';
                $query->whereNull($deleted)->update([$deleted => now()]);
            } else {
                $query->delete();
            }

            $this->showDeleteChildModal = false;
            $this->pendingDeleteChildId = null;
            $this->setToast('تم حذف العنصر التابع.');
        } catch (Throwable $e) {
            report($e);
            $this->setToast('تعذر حذف العنصر التابع.', 'error');
        }
    }

    // تبديل حالة العنصر التابع بين التفعيل والإيقاف.
    public function toggleChildStatus(int $childId): void
    {
        try {
            if (!$this->selectedId) {
                return;
            }

            $this->findScopedRecord($this->selectedId);
            $relationKey = $this->firstCrudRelationKey();

            if (!$relationKey) {
                return;
            }

            $relation = $this->config['relations'][$relationKey];
            $column = $relation['status_column'] ?? null;

            if (!$column || !Schema::hasColumn($relation['table'], $column)) {
                throw new \RuntimeException('عمود حالة العنصر التابع غير محدد.');
            }

            $row = $this->findChildRecord($this->selectedId, $childId, $relation);
            $current = $row[$column] ?? null;
            $new = !in_array($current, [true, 1, '1', 'true', 'TRUE'], true);

            DB::table($relation['table'])
                ->where($relation['primary_key'], $childId)
                ->where($relation['foreign_key'], $this->selectedId)
                ->update([$column => $new]);

            $this->setToast($new ? 'تم تفعيل العنصر التابع.' : 'تم إيقاف العنصر التابع.');
        } catch (Throwable $e) {
            report($e);
            $this->setToast('تعذر تغيير حالة العنصر التابع.', 'error');
        }
    }

    /* ============================================================
     | التحقق من الحقول والقوائم والملفات
     * ============================================================ */

    // بناء قواعد التحقق من الحقول وفق أنواعها وإرجاع البيانات الصالحة للتخزين.
   
protected function validateConfiguredFields(array $data, array $fields, string $table, bool $main): array
{
    $rules = [];
    $attributes = [];
    $messages = [];
    $allowed = [];

    foreach ($fields as $field) {
        $key = $field['key'] ?? null;

        if (!$key) {
            continue;
        }

        $this->assertSafeIdentifier($key);

        if (($field['type'] ?? 'text') === 'file') {
            continue;
        }

        if (!empty($field['is_status'])) {
            $statusColumn = $this->config['status']['column'] ?? $key;

            if ($key !== $statusColumn && !Schema::hasColumn($table, $key)) {
                $allowed[] = $key;
                $attributes[$key] = $field['label'] ?? $key;
                $rules[$key] = ['required', 'string'];
                continue;
            }
        }

        if (!Schema::hasColumn($table, $key)) {
            throw new AuthorizationException("الحقل [{$key}] غير موجود في جدول [{$table}].");
        }

        $allowed[] = $key;
        $attributes[$key] = $field['label'] ?? $key;
        $custom = $field['rules'] ?? $field['validation'] ?? [];

        if (is_string($custom)) {
            $custom = array_filter(explode('|', $custom));
        }

        if (!is_array($custom)) {
            $custom = [$custom];
        }

        $fieldRules = array_values(array_filter(
            $custom,
            fn ($rule) => is_string($rule) && $rule !== ''
        ));

        if (!in_array('required', $fieldRules, true) && !in_array('nullable', $fieldRules, true)) {
            $fieldRules[] = !empty($field['required']) ? 'required' : 'nullable';
        }

        switch ($field['type'] ?? 'text') {
            case 'email':
                $fieldRules[] = 'email';
                break;

            case 'date':
                $fieldRules[] = 'date';
                break;

            case 'number':
                $fieldRules[] = 'numeric';

                if ($key === ($this->config['location_picker']['latitude_field'] ?? null)) {
                    $fieldRules[] = 'between:-90,90';
                }

                if ($key === ($this->config['location_picker']['longitude_field'] ?? null)) {
                    $fieldRules[] = 'between:-180,180';
                }

                break;

            case 'checkbox':
                $fieldRules[] = 'boolean';
                break;

            case 'text':
            case 'textarea':
            case 'tel':
                $fieldRules[] = 'string';
                break;

            case 'select':
                if (!empty($field['options'])) {
                    $values = array_map(
                        fn ($option) => (string) ($option['value'] ?? ''),
                        $field['options']
                    );

                    $fieldRules[] = Rule::in($values);
                }

                break;
        }

        if (isset($field['max'])) {
            $fieldRules[] = 'max:' . (int) $field['max'];
        }

        if (isset($field['min'])) {
            $fieldRules[] = 'min:' . (int) $field['min'];
        }

        $rules[$key] = array_values(array_unique($fieldRules, SORT_REGULAR));

        $messages[$key . '.required'] = 'حقل :attribute مطلوب.';
        $messages[$key . '.email'] = 'يرجى إدخال بريد إلكتروني صحيح في :attribute.';
        $messages[$key . '.date'] = 'يرجى إدخال تاريخ صحيح في :attribute.';
        $messages[$key . '.numeric'] = 'يجب أن تكون قيمة :attribute رقمية.';
        $messages[$key . '.between'] = 'قيمة :attribute خارج النطاق المسموح.';
    }

    $payload = Arr::only($data, $allowed);

    // تحويل الأرقام العربية والفواصل العشرية إلى صيغة رقمية موحدة.
    $latitudeKey = $this->config['location_picker']['latitude_field'] ?? 'latitude';
    $longitudeKey = $this->config['location_picker']['longitude_field'] ?? 'longitude';

    foreach ([$latitudeKey, $longitudeKey] as $coordinateKey) {
        if (!array_key_exists($coordinateKey, $payload) || !is_string($payload[$coordinateKey])) {
            continue;
        }

        $value = trim($payload[$coordinateKey]);

        $value = strtr($value, [
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3',
            '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7',
            '٨' => '8', '٩' => '9',
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3',
            '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7',
            '۸' => '8', '۹' => '9',
            '٫' => '.',
            '،' => '.',
        ]);

        $payload[$coordinateKey] = $value;
    }

    foreach ($payload as $key => $value) {
        if ($value === '' && empty($this->fieldRequired($fields, $key))) {
            $payload[$key] = null;
        }
    }

    $validator = Validator::make($payload, $rules, $messages);
    $validator->setAttributeNames($attributes);
    $validated = $validator->validate();

    foreach ($fields as $field) {
        if (!empty($field['is_status'])) {
            $key = $field['key'];
            $column = $this->config['status']['column'] ?? $key;

            if ($key !== $column) {
                unset($validated[$key]);
            }
        }
    }

    return $validated;
}

    // تحديد ما إذا كان الحقل مطلوباً وفق إعدادات الحقول.
    protected function fieldRequired(array $fields, string $key): bool
    {
        foreach ($fields as $field) {
            if (($field['key'] ?? null) === $key) {
                return !empty($field['required']);
            }
        }

        return false;
    }

    // تحويل قيمة حقل الحالة الافتراضي إلى القيمة المناسبة لعمود الحالة في قاعدة البيانات.
    protected function applyStatusToPayload(array &$payload, array $form, array $fields, string $table): void
    {
        foreach ($fields as $field) {
            if (empty($field['is_status'])) {
                continue;
            }

            $key = $field['key'];

            if (!array_key_exists($key, $form)) {
                continue;
            }

            $status = $this->config['status'] ?? [];
            $column = $status['column'] ?? $key;

            $this->assertSafeIdentifier($column);

            if (!Schema::hasColumn($table, $column)) {
                throw new AuthorizationException("عمود الحالة [{$column}] غير موجود.");
            }

            $value = $form[$key];
            $booleanMap = $status['boolean_map'] ?? null;

            if ($booleanMap) {
                $reverse = array_search((string) $value, array_map('strval', $booleanMap), true);

                if ($reverse !== false) {
                    $value = filter_var($reverse, FILTER_VALIDATE_BOOLEAN);
                } elseif (in_array((string) $value, ['0', '1'], true) || is_bool($value)) {
                    $value = (bool) $value;
                } else {
                    throw ValidationException::withMessages([
                        $key => 'الحالة المختارة غير قابلة للتخزين في عمود الحالة.',
                    ]);
                }
            }

            $payload[$column] = $value;

            if ($key !== $column) {
                unset($payload[$key]);
            }
        }
    }

    // التحقق من أن القيم المختارة من القوائم المرجعية تتبع المؤسسة الحالية.
    protected function validateLookupFields(array $fields, array $payload): void
    {
        foreach ($fields as $field) {
            $key = $field['key'] ?? null;
            $lookupName = $field['options_source'] ?? $field['lookup'] ?? null;

            if (!$key || !$lookupName || empty($payload[$key])) {
                continue;
            }

            $lookup = $this->config['lookups'][$lookupName] ?? null;

            if (!$lookup) {
                throw new AuthorizationException("القائمة المرجعية [{$lookupName}] غير معرفة.");
            }

            $table = $lookup['table'];
            $pk = $lookup['key'];

            $query = DB::table($table)->where($pk, $payload[$key]);

            if (!empty($lookup['organization_column']) && Schema::hasColumn($table, $lookup['organization_column'])) {
                $query->where($lookup['organization_column'], $this->organizationId);
            } elseif ($table !== 'organizations' && Schema::hasColumn($table, 'organization_id')) {
                $query->where('organization_id', $this->organizationId);
            }

            if ($table === 'organizations' && $pk === 'organization_id') {
                $query->where($pk, $this->organizationId);
            }

            if (!$query->exists()) {
                throw ValidationException::withMessages([
                    $key => 'السجل المرتبط غير متاح ضمن المؤسسة الحالية.',
                ]);
            }
        }
    }

    // التحقق من أن المفتاح الأجنبي يشير إلى سجل مرتبط بالمؤسسة الحالية.
    protected function validateRelatedForeignKey(array $tenant, mixed $foreignValue): void
    {
        if ($foreignValue === null || $foreignValue === '') {
            throw new AuthorizationException('يجب تحديد السجل المرتبط بالمؤسسة.');
        }

        $step = $tenant['path'][0];
        $parentTable = $step['table'];

        $query = DB::table($parentTable)->where($step['owner_key'], $foreignValue);

        if (!empty($step['has_soft_deletes'])) {
            $query->whereNull($step['deleted_at_column'] ?? 'deleted_at');
        }

        $query->where($tenant['organization_column'], $this->organizationId);

        if (!$query->exists()) {
            throw new AuthorizationException('السجل المرتبط لا يتبع المؤسسة الحالية.');
        }
    }

   
// تحديد إعدادات رفع الملفات ومسار التخزين داخل مجلد المؤسسة.
protected function uploadOptions(array $field, string $scope): array
{
    $upload = $field['upload'] ?? [];

    if (!is_array($upload)) {
        throw new AuthorizationException('إعدادات رفع الملف غير صحيحة.');
    }

    // المسار الافتراضي لجميع ملفات المؤسسة.
    $directory = $upload['directory'] ?? (
        'organization/' . $this->entityKey .
        ($scope === 'main' ? '' : '/' . $scope)
    );

    $directory = trim(str_replace('\\', '/', $directory), '/');

    foreach (explode('/', $directory) as $part) {
        if (
            $part === '' ||
            in_array($part, ['.', '..'], true) ||
            !preg_match('/^[A-Za-z0-9_-]+$/', $part)
        ) {
            throw new AuthorizationException('مسار حفظ الملف غير آمن.');
        }
    }

    $disk = $upload['disk'] ?? 'public';

    if (!config("filesystems.disks.{$disk}")) {
        throw new AuthorizationException("قرص التخزين [{$disk}] غير معرف.");
    }

    $mimes = $upload['mimes'] ?? [
        'pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'
    ];

    if (is_string($mimes)) {
        $mimes = array_filter(array_map('trim', explode(',', $mimes)));
    }

    foreach ($mimes as $mime) {
        if (!preg_match('/^[A-Za-z0-9]+$/', (string) $mime)) {
            throw new AuthorizationException('امتداد ملف غير صالح في الإعدادات.');
        }
    }

    return [
        'disk' => $disk,
        'directory' => $directory,
        'mimes' => $mimes,
        'max_kb' => (int) ($upload['max_kb'] ?? 10240),
        'multiple' => (bool) ($upload['multiple'] ?? false),
        'delete_old' => (bool) ($upload['delete_old'] ?? false),
    ];
}

    // التحقق من الملفات المرفوعة وتخزينها وإرجاع مساراتها لإضافتها إلى بيانات السجل.
    protected function storeConfiguredUploads(
        array $fields,
        string $property,
        bool $creating,
        array $existing,
        string $scope,
        array &$stored
    ): array {
        $bag = $this->{$property};
        $payload = [];

        foreach ($fields as $field) {
            if (($field['type'] ?? '') !== 'file') {
                continue;
            }

            $key = $field['key'] ?? null;

            if (!$key) {
                continue;
            }

            $table = $scope === 'main'
                ? $this->config['table']
                : ($this->config['relations'][$scope]['table'] ?? null);

            if (!$table || !Schema::hasColumn($table, $key)) {
                throw new AuthorizationException("عمود الملف [{$key}] غير موجود في الجدول.");
            }

            $options = $this->uploadOptions($field, $scope);
            $files = $bag[$key] ?? null;
            $required = !empty($field['required']) && ($creating || empty($existing[$key]));

            if ($files === null || $files === '' || $files === []) {
                if ($required) {
                    throw ValidationException::withMessages([
                        $key => 'يرجى اختيار ملف للحقل ' . ($field['label'] ?? $key) . '.',
                    ]);
                }

                if ($creating) {
                    $payload[$key] = $options['multiple']
                        ? json_encode([], JSON_UNESCAPED_UNICODE)
                        : null;
                }

                continue;
            }

            $validationData = [$key => $files];
            $rules = [$key => [$options['multiple'] ? 'array' : 'file']];

            if (!$options['multiple']) {
                $rules[$key][] = 'file';
            }

            if ($options['multiple']) {
                $rules[$key . '.*'] = ['file'];
            }

            $fileRule = ['file'];

            if ($options['mimes']) {
                $fileRule[] = 'mimes:' . implode(',', $options['mimes']);
            }

            if ($options['max_kb'] > 0) {
                $fileRule[] = 'max:' . $options['max_kb'];
            }

            if ($options['multiple']) {
                $rules[$key . '.*'] = $fileRule;
            } else {
                $rules[$key] = array_merge($rules[$key], array_slice($fileRule, 1));
            }

            Validator::make($validationData, $rules, [
                $key . '.*.mimes' => 'نوع أحد الملفات في ' . ($field['label'] ?? $key) . ' غير مسموح.',
                $key . '.*.max' => 'حجم أحد الملفات في ' . ($field['label'] ?? $key) . ' أكبر من الحد المسموح.',
                $key . '.mimes' => 'نوع الملف في ' . ($field['label'] ?? $key) . ' غير مسموح.',
                $key . '.max' => 'حجم الملف في ' . ($field['label'] ?? $key) . ' أكبر من الحد المسموح.',
            ])->validate();

            $items = $options['multiple'] ? (array) $files : [$files];
            $paths = [];

            foreach ($items as $file) {
                $path = $file->store($options['directory'], $options['disk']);

                if (!$path) {
                    throw new \RuntimeException('تعذر تخزين الملف ' . $key . '.');
                }

                $paths[] = $path;
                $stored[] = ['disk' => $options['disk'], 'path' => $path];
            }

            $payload[$key] = $options['multiple']
                ? json_encode($paths, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
                : $paths[0];
        }

        return $payload;
    }

    // حذف الملفات القديمة المستبدلة بعد نجاح حفظ الملفات الجديدة إذا كان ذلك مفعلاً.
    protected function deleteReplacedUploads(array $fields, array $existing, array $newPayload, string $scope): void
    {
        foreach ($fields as $field) {
            if (($field['type'] ?? '') !== 'file' || empty($field['key'])) {
                continue;
            }

            $key = $field['key'];

            if (!array_key_exists($key, $newPayload) || empty($existing[$key])) {
                continue;
            }

            $options = $this->uploadOptions($field, $scope);

            if (!$options['delete_old']) {
                continue;
            }

            $old = $this->decodeStoredPaths($existing[$key]);
            $new = $this->decodeStoredPaths($newPayload[$key]);

            foreach (array_diff($old, $new) as $path) {
                if (str_starts_with($path, $options['directory'] . '/')) {
                    Storage::disk($options['disk'])->delete($path);
                }
            }
        }
    }

    // تحويل قيمة مسار الملف المخزن إلى مصفوفة مسارات سواء كانت JSON أو نصاً مفرداً.
    protected function decodeStoredPaths(mixed $value): array
    {
        if (!$value) {
            return [];
        }

        if (is_array($value)) {
            return array_values(array_filter($value, 'is_string'));
        }

        $decoded = json_decode((string) $value, true);

        if (is_array($decoded)) {
            return array_values(array_filter($decoded, 'is_string'));
        }

        return [(string) $value];
    }

    // حذف الملفات التي رُفعت أثناء عملية فاشلة لمنع بقاء ملفات يتيمة في التخزين.
    protected function rollbackStoredUploads(array $stored): void
    {
        foreach ($stored as $file) {
            try {
                Storage::disk($file['disk'])->delete($file['path']);
            } catch (Throwable $e) {
                report($e);
            }
        }
    }

    /* ============================================================
     | عرض مساعد وترجمة القيم
     * ============================================================ */

    // تحويل قيمة الحقل إلى نص مناسب للعرض مع دعم العلاقات والقوائم والتواريخ.
    public function displayValue(array $record, string $field): string
    {
        $value = $record[$field] ?? null;

        if ($value === null || $value === '') {
            return '—';
        }

        foreach (($this->config['relations'] ?? []) as $relationKey => $relation) {
            if (
                ($relation['type'] ?? '') === 'belongsTo' &&
                ($relation['foreign_key'] ?? null) === $field
            ) {
                $related = $record['_relation_' . $relationKey] ?? [];
                $nameField = $relation['name_field'] ?? 'name';

                if (!empty($related[$nameField])) {
                    return (string) $related[$nameField];
                }
            }
        }

        foreach (($this->config['main_fields'] ?? []) as $definition) {
            if (($definition['key'] ?? null) === $field && !empty($definition['options'])) {
                foreach ($definition['options'] as $option) {
                    if ((string) ($option['value'] ?? '') === (string) $value) {
                        return (string) ($option['label'] ?? $value);
                    }
                }
            }
        }

        foreach (($this->config['lookups'] ?? []) as $lookupKey => $lookup) {
            if ($lookupKey === $field || ($lookup['key'] ?? null) === $field) {
                foreach (($this->lookups[$lookupKey] ?? []) as $option) {
                    if ((string) $option['id'] === (string) $value) {
                        return (string) $option['name'];
                    }
                }
            }
        }

        if (in_array($field, ['created_at', 'updated_at', 'authority_start_date', 'authority_end_date'], true)) {
            try {
                return \Illuminate\Support\Carbon::parse($value)->format('Y-m-d');
            } catch (Throwable) {
            }
        }

        return is_scalar($value) ? (string) $value : '—';
    }

    // إرجاع خيارات الحقل من القوائم المرجعية أو الخيارات الثابتة في الإعدادات.
    public function fieldOptions(array $field): array
    {
        if (!empty($field['options_source'])) {
            return $this->lookups[$field['options_source']] ?? [];
        }

        if (!empty($field['lookup'])) {
            return $this->lookups[$field['lookup']] ?? [];
        }

        return $field['options'] ?? [];
    }

    // تحديد ما إذا كان الحقل مداراً تلقائياً بواسطة سياسة عزل المؤسسة المباشر.
    public function isTenantManagedField(string $key): bool
    {
        $tenant = $this->tenantConfig();

        return $tenant['type'] === 'direct' && ($tenant['column'] ?? null) === $key;
    }

    // تعيين رسالة التنبيه ونوعها لعرض نتيجة العملية في الواجهة.
    protected function setToast(string $message, string $type = 'success'): void
    {
        $this->toastMessage = $message;
        $this->toastType = $type;
    }

    // إخفاء رسالة التنبيه الحالية من الواجهة.
    public function dismissToast(): void
    {
        $this->toastMessage = null;
    }

    // تحويل الأرقام العربية والفواصل العشرية إلى صيغة رقمية إنجليزية قبل التحقق والحفظ.
    private function normalizeCoordinates(): void
    {
        $location = $this->config['location_picker'] ?? [];

        $fields = array_filter([
            $location['latitude_field'] ?? 'latitude',
            $location['longitude_field'] ?? 'longitude',
        ]);

        foreach ($fields as $field) {
            if (!isset($this->form[$field])) {
                continue;
            }

            $value = (string) $this->form[$field];

            $value = strtr($value, [
                '٠' => '0',
                '١' => '1',
                '٢' => '2',
                '٣' => '3',
                '٤' => '4',
                '٥' => '5',
                '٦' => '6',
                '٧' => '7',
                '٨' => '8',
                '٩' => '9',
                '٫' => '.',
                '،' => '.',
            ]);

            $this->form[$field] = $value;
        }
    }
    
public function updatedForm($value, $key): void
{
    $this->resetValidation($key);
}

public function updatedChildForm($value, $key): void
{
    $this->resetValidation($key);
}

public function updatedMainUploads($value, $key): void
{
    $this->resetValidation('mainUploads.' . $key);
}

public function updatedChildUploads($value, $key): void
{
    $this->resetValidation('childUploads.' . $key);
}


    // تجهيز بيانات الصفحة وإرسال السجلات والإحصائيات والعلاقات إلى واجهة Blade.
    public function render(): View
    {
        $records = $this->records;
        $selectedRecord = $this->selectedRecord;
        $selectedChildren = $this->selectedChildren;
        $stats = $this->stats;
        $lookups = $this->lookups;
        $tenant = $this->tenantConfig();
        $displayValues = [];

        if ($selectedRecord) {
            $fieldsToDisplay = array_merge(
                $this->config['ui']['details']['header_fields'] ?? [],
                $this->config['ui']['details']['info_fields'] ?? []
            );

            foreach ($fieldsToDisplay as $definition) {
                $field = $definition['field'] ?? null;

                if ($field) {
                    $displayValues[$field] = $this->displayValue($selectedRecord, $field);
                }
            }
        }
        

        return view('front-organizations.livewire.shared.universal-form', [
            'records' => $records,
            'selectedRecord' => $selectedRecord,
            'selectedChildren' => $selectedChildren,
            'stats' => $stats,
            'lookups' => $lookups,
            'displayValues' => $displayValues,
            'tenantManagedColumn' => $tenant['type'] === 'direct' ? ($tenant['column'] ?? null) : null,
            'componentId' => $this->getId(),
            'childSearch' => $this->childSearch,
        ]);
    }
}