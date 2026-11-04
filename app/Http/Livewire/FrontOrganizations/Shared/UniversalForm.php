<?php

namespace App\Http\Livewire\FrontOrganizations\Shared;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;

class UniversalForm extends Component
{
    /*
    |--------------------------------------------------------------------------
    | الخصائص العامة للمحرك
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

    public ?int $selectedRecordId = null;

    public array $relatedRecords = [];

    /*
    |--------------------------------------------------------------------------
    | إعدادات المحرك
    |--------------------------------------------------------------------------
    */

    protected string $configFile = 'Http/Livewire/FrontOrganizations/Shared/UniversalForm/configs.php';

    /*
    |--------------------------------------------------------------------------
    | تهيئة المحرك
    |--------------------------------------------------------------------------
    */

    public function mount(string $entityKey = 'owners'): void
    {
        $this->entityKey = $entityKey;

        $this->loadConfiguration();

        $this->perPage = (int) (
            $this->config['pagination']['per_page'] ?? 10
        );

        $this->initializeFilters();

    }

    /*
    |--------------------------------------------------------------------------
    | تحميل إعدادات الجدول
    |--------------------------------------------------------------------------
    */

    protected function loadConfiguration(): void
    {
        $configPath = app_path($this->configFile);

        if (!file_exists($configPath)) {
            throw new \RuntimeException(
                "ملف إعدادات UniversalForm غير موجود: {$configPath}"
            );
        }

        $allConfigs = require $configPath;

        $defaults = $allConfigs['_defaults'] ?? [];

        $entityConfig = $allConfigs[$this->entityKey] ?? [];

        if (empty($entityConfig)) {
            abort(404, 'إعدادات القسم غير موجودة: ' . $this->entityKey);
        }

        $this->config = $this->mergeConfiguration(
            $defaults,
            $entityConfig
        );

        $this->validateConfiguration();

    }

    /*
    |--------------------------------------------------------------------------
    | دمج الإعدادات الافتراضية مع إعدادات الجدول
    |--------------------------------------------------------------------------
    */

    protected function mergeConfiguration(array $defaults, array $custom): array
    {
        foreach ($custom as $key => $value) {

            if (
                isset($defaults[$key]) &&
                is_array($defaults[$key]) &&
                is_array($value) &&
                !array_is_list($defaults[$key]) &&
                !array_is_list($value)
            ) {
                $defaults[$key] = $this->mergeConfiguration(
                    $defaults[$key],
                    $value
                );
            } else {
                $defaults[$key] = $value;
            }

        }

        return $defaults;
    }

    /*
    |--------------------------------------------------------------------------
    | التحقق من سلامة إعدادات الجدول
    |--------------------------------------------------------------------------
    */

    protected function validateConfiguration(): void
    {
        $table = $this->config['table'] ?? null;

        $primaryKey = $this->config['primary_key'] ?? null;

        if (!$table || !$primaryKey) {
            throw new \RuntimeException(
                'يجب تحديد table و primary_key في إعدادات UniversalForm.'
            );
        }

        $this->validateIdentifier($table);

        $this->validateIdentifier($primaryKey);

    }

    /*
    |--------------------------------------------------------------------------
    | التحقق من أسماء الجداول والأعمدة
    |--------------------------------------------------------------------------
    */

    protected function validateIdentifier(string $identifier): void
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $identifier)) {
            throw new \InvalidArgumentException(
                'اسم جدول أو عمود غير صالح: ' . $identifier
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | الحصول على اسم الجدول
    |--------------------------------------------------------------------------
    */

    protected function getTable(): string
    {
        return $this->config['table'];
    }

    /*
    |--------------------------------------------------------------------------
    | الحصول على المفتاح الأساسي
    |--------------------------------------------------------------------------
    */

    protected function getPrimaryKey(): string
    {
        return $this->config['primary_key'];
    }

    /*
    |--------------------------------------------------------------------------
    | تهيئة الفلاتر
    |--------------------------------------------------------------------------
    */

    protected function initializeFilters(): void
    {
        foreach (($this->config['filters'] ?? []) as $filter) {

            $key = $filter['key'] ?? null;

            if (!$key) {
                continue;
            }

            $this->filters[$key] = $filter['default'] ?? 'all';

        }
    }

    /*
    |--------------------------------------------------------------------------
    | بناء استعلام السجلات الأساسي
    |--------------------------------------------------------------------------
    */

    protected function baseQuery()
    {
        $table = $this->getTable();

        $query = DB::table($table);

        if ($this->config['has_soft_deletes'] ?? false) {

            $query->whereNull(
                $this->config['deleted_at_column'] ?? 'deleted_at'
            );

        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | البحث العام
    |--------------------------------------------------------------------------
    */

    protected function applySearch($query)
    {
        $searchConfig = $this->config['search'] ?? [];

        if (
            !($searchConfig['enabled'] ?? true) ||
            trim($this->search) === ''
        ) {
            return $query;
        }

        $fields = $searchConfig['fields'] ?? [];

        if (empty($fields)) {
            return $query;
        }

        $search = trim($this->search);

        $query->where(function ($q) use ($fields, $search) {

            foreach ($fields as $field) {

                $this->validateIdentifier($field);

                $q->orWhere(
                    $field,
                    'LIKE',
                    '%' . $search . '%'
                );

            }

        });

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | تطبيق الفلاتر العامة
    |--------------------------------------------------------------------------
    */

    protected function applyFilters($query)
    {
        foreach (($this->config['filters'] ?? []) as $filter) {

            $key = $filter['key'] ?? null;

            if (!$key) {
                continue;
            }

            $value = $this->filters[$key] ?? 'all';

            if ($value === 'all' || $value === '' || $value === null) {
                continue;
            }

            $column = $filter['column'] ?? $key;

            $type = $filter['type'] ?? 'select';

            if ($type === 'date') {

                if ($key === 'dateFrom') {

                    $column = $filter['column'] ?? 'created_at';

                    $this->validateIdentifier($column);

                    $query->whereDate($column, '>=', $value);

                } elseif ($key === 'dateTo') {

                    $column = $filter['column'] ?? 'created_at';

                    $this->validateIdentifier($column);

                    $query->whereDate($column, '<=', $value);

                } else {

                    $this->validateIdentifier($column);

                    $query->whereDate($column, $value);

                }

                continue;
            }

            if ($type === 'select') {

                if (($filter['virtual'] ?? false) === true) {
                    continue;
                }

                $this->validateIdentifier($column);

                $operator = $filter['operator'] ?? '=';

                $query->where($column, $operator, $value);

            }

        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | ترتيب السجلات
    |--------------------------------------------------------------------------
    */

    protected function applySorting($query)
    {
        $sort = $this->sort;

        $nameField = $this->config['name_field'] ?? $this->getPrimaryKey();

        $primaryKey = $this->getPrimaryKey();

        $this->validateIdentifier($nameField);

        switch ($sort) {

            case 'oldest':

                $query->orderBy($primaryKey, 'asc');

                break;

            case 'name_asc':

                $query->orderBy($nameField, 'asc');

                break;

            case 'name_desc':

                $query->orderBy($nameField, 'desc');

                break;

            case 'newest':
            default:

                $query->orderBy($primaryKey, 'desc');

                break;

        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | جلب السجلات العامة
    |--------------------------------------------------------------------------
    */

    public function getRecordsProperty(): array
    {
        $query = $this->baseQuery();

        $this->applySearch($query);

        $this->applyFilters($query);

        $this->applySorting($query);

        $pagination = $this->config['pagination'] ?? [];

        if ($pagination['enabled'] ?? true) {

            $total = (clone $query)->count();

            $records = $query
                ->forPage($this->currentPage, $this->perPage)
                ->get();

        } else {

            $records = $query->get();

            $total = $records->count();

        }

        $result = [];

        foreach ($records as $record) {

            $item = (array) $record;

            $id = $item[$this->getPrimaryKey()];

            // المفتاح العام الذي تستخدمه واجهة العرض
            $item['id'] = $id;

            $result[] = $item;

        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | معلومات الترقيم
    |--------------------------------------------------------------------------
    */

    public function getRecordsMetaProperty(): array
    {
        $query = $this->baseQuery();

        $this->applySearch($query);

        $this->applyFilters($query);

        $total = $query->count();

        $enabled = $this->config['pagination']['enabled'] ?? true;

        return [
            'total' => $total,

            'per_page' => $enabled ? $this->perPage : $total,

            'current_page' => $this->currentPage,

            'last_page' => $enabled
                ? max(1, (int) ceil($total / max(1, $this->perPage)))
                : 1,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | الانتقال بين الصفحات
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

    /*
    |--------------------------------------------------------------------------
    | تغيير عدد السجلات في الصفحة
    |--------------------------------------------------------------------------
    */

    public function updatedPerPage($value): void
    {
        $options = $this->config['pagination']['options'] ?? [10, 25, 50, 100];

        $value = (int) $value;

        if (in_array($value, $options, true)) {
            $this->perPage = $value;
        } else {
            $this->perPage = (int) (
                $this->config['pagination']['per_page'] ?? 10
            );
        }

        $this->currentPage = 1;
    }

    /*
    |--------------------------------------------------------------------------
    | إعادة ضبط الصفحة عند البحث
    |--------------------------------------------------------------------------
    */

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
    }

    public function updatedFilters(): void
    {
        $this->currentPage = 1;
    }

    public function updatedSort(): void
    {
        $this->currentPage = 1;
    }

    /*
    |--------------------------------------------------------------------------
    | فتح نموذج الإضافة
    |--------------------------------------------------------------------------
    */

    public function create(): void
    {
        if (!($this->config['actions']['create'] ?? true)) {
            return;
        }

        $this->formData = [];

        foreach (($this->config['main_fields'] ?? []) as $field) {

            $key = $field['key'] ?? null;

            if (!$key) {
                continue;
            }

            $this->formData[$key] = $field['default'] ?? null;

        }

        $this->editingId = null;

        $this->formMode = 'create';

        $this->showForm = true;

    }

    /*
    |--------------------------------------------------------------------------
    | فتح نموذج التعديل
    |--------------------------------------------------------------------------
    */

    public function edit(int $id): bool
    {
        try {

            if (!($this->config['actions']['edit'] ?? true)) {
                return false;
            }

            $record = $this->baseQuery()
                ->where($this->getPrimaryKey(), $id)
                ->first();

            if (!$record) {

                $this->dispatch(
                    'uf-error',
                    message: 'السجل المطلوب غير موجود.'
                );

                return false;
            }

            $this->formData = (array) $record;

            $this->editingId = $id;

            $this->formMode = 'edit';

            $this->showForm = true;

            return true;

        } catch (\Throwable $e) {

            Log::error('UniversalForm edit error', [
                'entity' => $this->entityKey,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'حدث خطأ أثناء تحميل بيانات التعديل.'
            );

            return false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | إغلاق النموذج
    |--------------------------------------------------------------------------
    */

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
    | بناء قواعد التحقق من إعدادات الحقول
    |--------------------------------------------------------------------------
    */

    protected function getValidationRules(array $fields): array
    {
        $rules = [];

        foreach ($fields as $field) {

            $key = $field['key'] ?? null;

            if (!$key) {
                continue;
            }

            $validation = $field['validation'] ?? [];

            if (!empty($validation)) {

                $rules[$key] = $validation;

            } elseif ($field['required'] ?? false) {

                $rules[$key] = 'required';

                if (($field['type'] ?? '') === 'email') {
                    $rules[$key] .= '|email';
                }

            } else {

                $rules[$key] = 'nullable';

                if (($field['type'] ?? '') === 'email') {
                    $rules[$key] .= '|email';
                }

            }

        }

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | تجهيز بيانات الحفظ من إعدادات الحقول
    |--------------------------------------------------------------------------
    */

    protected function prepareRecordData(
        array $data,
        array $fields
    ): array {

        $record = [];

        foreach ($fields as $field) {

            $key = $field['key'] ?? null;

            if (!$key) {
                continue;
            }

            $column = $field['column'] ?? $key;

            $this->validateIdentifier($column);

            // الحقول الافتراضية لا تحفظ في قاعدة البيانات
            if (($field['virtual'] ?? false) === true) {
                continue;
            }

            if (($field['is_status'] ?? false) === true &&
                !isset($field['column'])) {
                continue;
            }

            if (array_key_exists($key, $data)) {

                $value = $data[$key];

                $record[$column] = $value === '' ? null : $value;

            }

        }

        return $record;
    }

    /*
    |--------------------------------------------------------------------------
    | حفظ سجل عام
    |--------------------------------------------------------------------------
    */

    public function saveRecord(array $data = [], ?int $id = null): void
    {
        try {

            $fields = $this->config['main_fields'] ?? [];

            $rules = $this->getValidationRules($fields);

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {

                $this->dispatch(
                    'uf-error',
                    message: $validator->errors()->first()
                );

                return;
            }

            $record = $this->prepareRecordData($data, $fields);

            if (empty($record)) {

                $this->dispatch(
                    'uf-error',
                    message: 'لا توجد بيانات صالحة للحفظ.'
                );

                return;
            }

            $table = $this->getTable();

            $primaryKey = $this->getPrimaryKey();

            if ($id) {

                if (!($this->config['actions']['edit'] ?? true)) {
                    return;
                }

                $exists = $this->baseQuery()
                    ->where($primaryKey, $id)
                    ->exists();

                if (!$exists) {
                    throw new \RuntimeException('السجل المطلوب غير موجود.');
                }

                $record['updated_at'] = now();

                DB::table($table)
                    ->where($primaryKey, $id)
                    ->update($record);

                $newId = $id;

                $message = 'تم تحديث البيانات بنجاح ✓';

            } else {

                if (!($this->config['actions']['create'] ?? true)) {
                    return;
                }

                $record['created_at'] = now();

                $record['updated_at'] = now();

                $newId = DB::table($table)->insertGetId($record);

                $message = 'تمت إضافة السجل بنجاح ✓';

            }

            $this->closeForm();

            $this->dispatchGenericUpdated($newId, $message);

        } catch (ValidationException $e) {

            throw $e;

        } catch (\Throwable $e) {

            Log::error('UniversalForm saveRecord error', [
                'entity' => $this->entityKey,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر حفظ البيانات. تحقق من الحقول وإعدادات الجدول.'
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | حذف سجل عام
    |--------------------------------------------------------------------------
    */

    public function deleteRecord(int $id): void
    {
        try {

            if (!($this->config['actions']['delete'] ?? true)) {
                return;
            }

            $table = $this->getTable();

            $primaryKey = $this->getPrimaryKey();

            $exists = $this->baseQuery()
                ->where($primaryKey, $id)
                ->exists();

            if (!$exists) {

                $this->dispatch(
                    'uf-error',
                    message: 'السجل المطلوب غير موجود.'
                );

                return;
            }

            if ($this->config['has_soft_deletes'] ?? false) {

                $deletedColumn = $this->config['deleted_at_column'] ?? 'deleted_at';

                $this->validateIdentifier($deletedColumn);

                DB::table($table)
                    ->where($primaryKey, $id)
                    ->update([
                        $deletedColumn => now(),
                        'updated_at' => now(),
                    ]);

            } else {

                DB::table($table)
                    ->where($primaryKey, $id)
                    ->delete();

            }

            $this->dispatchGenericUpdated(
                null,
                'تم حذف السجل بنجاح ✓'
            );

        } catch (\Throwable $e) {

            Log::error('UniversalForm deleteRecord error', [
                'entity' => $this->entityKey,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر حذف السجل.'
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | تغيير حالة سجل عام
    |--------------------------------------------------------------------------
    */

    public function changeRecordStatus(int $id, string $status): void
    {
        try {

            if (!($this->config['actions']['status'] ?? false)) {
                return;
            }

            $statusConfig = $this->config['status'] ?? [];

            $column = $statusConfig['column'] ?? 'status';

            $this->validateIdentifier($column);

            $allowed = $statusConfig['values'] ?? [];

            if (!empty($allowed) && !in_array($status, $allowed, true)) {
                throw new \InvalidArgumentException('حالة غير مسموح بها.');
            }

            $exists = $this->baseQuery()
                ->where($this->getPrimaryKey(), $id)
                ->exists();

            if (!$exists) {
                return;
            }

            DB::table($this->getTable())
                ->where($this->getPrimaryKey(), $id)
                ->update([
                    $column => $status,
                    'updated_at' => now(),
                ]);

            $this->dispatchGenericUpdated(
                $id,
                'تم تحديث حالة السجل بنجاح ✓'
            );

        } catch (\Throwable $e) {

            Log::error('UniversalForm changeRecordStatus error', [
                'entity' => $this->entityKey,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر تحديث حالة السجل.'
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | تحميل سجل معين
    |--------------------------------------------------------------------------
    */

    public function getRecord(int $id): ?array
    {
        $record = $this->baseQuery()
            ->where($this->getPrimaryKey(), $id)
            ->first();

        if (!$record) {
            return null;
        }

        $data = (array) $record;

        $data['id'] = $data[$this->getPrimaryKey()];

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | تحميل العلاقات
    |--------------------------------------------------------------------------
    */

    public function loadRelations(int $parentId): array
    {
        $relations = [];

        foreach (($this->config['relations'] ?? []) as $name => $relation) {

            if (!($relation['enabled'] ?? true)) {
                continue;
            }

            if (($relation['type'] ?? '') !== 'hasMany') {
                continue;
            }

            $table = $relation['table'] ?? null;

            $foreignKey = $relation['foreign_key'] ?? null;

            $primaryKey = $relation['primary_key'] ?? 'id';

            if (!$table || !$foreignKey) {
                continue;
            }

            $this->validateIdentifier($table);
            $this->validateIdentifier($foreignKey);
            $this->validateIdentifier($primaryKey);

            $query = DB::table($table)
                ->where($foreignKey, $parentId);

            if ($relation['has_soft_deletes'] ?? false) {

                $deletedColumn = $relation['deleted_at_column'] ?? 'deleted_at';

                $this->validateIdentifier($deletedColumn);

                $query->whereNull($deletedColumn);
            }

            $records = $query
                ->orderByDesc($primaryKey)
                ->get();

            $relations[$name] = $records
                ->map(function ($record) use ($primaryKey) {

                    $item = (array) $record;

                    $item['id'] = $item[$primaryKey];

                    return $item;

                })
                ->toArray();

        }

        $this->relatedRecords = $relations;

        return $relations;
    }

    /*
    |--------------------------------------------------------------------------
    | حفظ سجل تابع عام
    |--------------------------------------------------------------------------
    */

    public function saveRelatedRecord(
        string $relationName,
        int $parentId,
        array $data,
        ?int $relatedId = null
    ): void {

        try {

            $relation = $this->config['relations'][$relationName] ?? null;

            if (!$relation || !($relation['enabled'] ?? true)) {
                throw new \RuntimeException('العلاقة غير معرفة أو غير مفعلة.');
            }

            $table = $relation['table'];

            $primaryKey = $relation['primary_key'];

            $foreignKey = $relation['foreign_key'];

            $this->validateIdentifier($table);
            $this->validateIdentifier($primaryKey);
            $this->validateIdentifier($foreignKey);

            $fields = $relation['fields'] ?? [];

            if (empty($fields) && $relationName === 'agents') {
                $fields = $this->config['sub_fields'] ?? [];
            }

            $rules = $this->getValidationRules($fields);

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {

                $this->dispatch(
                    'uf-error',
                    message: $validator->errors()->first()
                );

                return;
            }

            $record = $this->prepareRecordData($data, $fields);

            $record[$foreignKey] = $parentId;

            $parentExists = $this->baseQuery()
                ->where($this->getPrimaryKey(), $parentId)
                ->exists();

            if (!$parentExists) {
                throw new \RuntimeException('السجل الرئيسي غير موجود.');
            }

            if ($relatedId) {

                $relatedQuery = DB::table($table)
                    ->where($primaryKey, $relatedId)
                    ->where($foreignKey, $parentId);

                $relatedQuery->update(array_merge($record, [
                    'updated_at' => now(),
                ]));

                $message = 'تم تحديث السجل المرتبط بنجاح ✓';

            } else {

                $record['created_at'] = now();
                $record['updated_at'] = now();

                DB::table($table)->insert($record);

                $message = 'تمت إضافة السجل المرتبط بنجاح ✓';

            }

            $this->loadRelations($parentId);

            $this->dispatchGenericUpdated($parentId, $message);

        } catch (\Throwable $e) {

            Log::error('UniversalForm saveRelatedRecord error', [
                'entity' => $this->entityKey,
                'relation' => $relationName,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر حفظ البيانات المرتبطة.'
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | حذف سجل تابع عام
    |--------------------------------------------------------------------------
    */

    public function deleteRelatedRecord(
        string $relationName,
        int $parentId,
        int $relatedId
    ): void {

        try {

            $relation = $this->config['relations'][$relationName] ?? null;

            if (!$relation || !($relation['enabled'] ?? true)) {
                return;
            }

            $table = $relation['table'];

            $primaryKey = $relation['primary_key'];

            $foreignKey = $relation['foreign_key'];

            $this->validateIdentifier($table);
            $this->validateIdentifier($primaryKey);
            $this->validateIdentifier($foreignKey);

            $query = DB::table($table)
                ->where($primaryKey, $relatedId)
                ->where($foreignKey, $parentId);

            if ($relation['has_soft_deletes'] ?? false) {

                $deletedColumn = $relation['deleted_at_column'] ?? 'deleted_at';

                $this->validateIdentifier($deletedColumn);

                $query->update([
                    $deletedColumn => now(),
                    'updated_at' => now(),
                ]);

            } else {

                $query->delete();

            }

            $this->loadRelations($parentId);

            $this->dispatchGenericUpdated(
                $parentId,
                'تم حذف السجل المرتبط بنجاح ✓'
            );

        } catch (\Throwable $e) {

            Log::error('UniversalForm deleteRelatedRecord error', [
                'entity' => $this->entityKey,
                'relation' => $relationName,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر حذف السجل المرتبط.'
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | إرسال حدث تحديث البيانات العامة
    |--------------------------------------------------------------------------
    */

    protected function dispatchGenericUpdated(
        ?int $keepRecord = null,
        string $message = ''
    ): void {

        $this->dispatch(
            'uf-records-updated',
            records: $this->records,
            stats: $this->stats,
            keepRecord: $keepRecord,
            message: $message
        );

        // حدث التوافق مع واجهة الملاك القديمة
        $this->dispatchUpdated($keepRecord, $message);
    }

    /*
    |--------------------------------------------------------------------------
    | ================================================================
    | وظائف التوافق مع واجهة الملاك والوكلاء الحالية
    | ================================================================
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | جلب الملاك
    |--------------------------------------------------------------------------
    */

    public function getOwnersProperty(): array
    {
        if ($this->entityKey !== 'owners') {
            return [];
        }

        $owners = DB::table('owners')
            ->whereNull('deleted_at')
            ->orderBy('owner_id', 'desc')
            ->get();

        $result = [];

        foreach ($owners as $owner) {

            $status = $owner->is_blacklisted
                ? 'blocked'
                : ($owner->is_active ? 'active' : 'inactive');

            // اسم المؤسسة
            $agencyName = '';

            if (!empty($owner->organization_id)) {

                $org = DB::table('organizations')
                    ->where('organization_id', $owner->organization_id)
                    ->first();

                if ($org) {

                    $agencyName = $org->name
                        ?? $org->organization_name
                        ?? ('مؤسسة #' . $owner->organization_id);

                }
            }

            // الوكلاء
            $agents = DB::table('owner_agents')
                ->where('owner_id', $owner->owner_id)
                ->orderBy('agent_id', 'desc')
                ->get();

            $agentsArr = [];

            foreach ($agents as $a) {

                $agentsArr[] = [
                    'id'     => (int) $a->agent_id,
                    'name'   => $a->full_name ?? '',
                    'phone'  => $a->phone ?? '',
                    'email'  => $a->email ?? '',
                    'type'   => $a->role ?? 'administrative',
                    'notes'  => $a->notes ?? '',
                    'status' => $a->is_active ? 'active' : 'blocked',
                ];

            }

            $result[] = [

                'id'                  => (int) $owner->owner_id,

                'full_name'           => $owner->full_name ?? '',

                'phone'               => $owner->phone ?? '',

                'phone2'              => $owner->phone2 ?? '',

                'email'               => $owner->email ?? '',

                'nationality'         => $owner->nationality ?? 'سعودي',

                'id_type'             => $owner->id_type ?? 'national_id',

                'id_number'           => $owner->id_number ?? '',

                'address'             => $owner->address ?? '',

                'organization_id'     => $owner->organization_id,

                'agency'              => $agencyName,

                'bank_name'           => $owner->bank_name ?? '',

                'bank_account_name'   => $owner->bank_account_name ?? '',

                'bank_account_number' => $owner->bank_account_number ?? '',

                'bank_iban'           => $owner->bank_iban ?? '',

                'payment'             => $owner->preferred_payment_method ?? 'bank_transfer',

                'status'              => $status,

                'notes'               => $owner->notes ?? '',

                'created_at'          => $owner->created_at
                    ? Carbon::parse($owner->created_at)->format('Y-m-d')
                    : '',

                'agents'              => $agentsArr,

            ];

        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | جلب المؤسسات
    |--------------------------------------------------------------------------
    */

    public function getOrganizationsProperty(): array
    {
        if ($this->entityKey !== 'owners') {
            return [];
        }

        return DB::table('organizations')
            ->select('organization_id as id', 'name')
            ->get()
            ->map(fn ($organization) => [
                'id' => (int) $organization->id,
                'name' => $organization->name,
            ])
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | إحصائيات الملاك
    |--------------------------------------------------------------------------
    */

    public function getStatsProperty(): array
    {
        if ($this->entityKey !== 'owners') {

            return [
                'total' => $this->recordsMeta['total'] ?? 0,
                'active' => 0,
                'blocked' => 0,
            ];

        }

        $owners = $this->owners;

        return [

            'total' => count($owners),

            'active' => count(array_filter(
                $owners,
                fn ($owner) => $owner['status'] === 'active'
            )),

            'blocked' => count(array_filter(
                $owners,
                fn ($owner) => $owner['status'] === 'blocked'
            )),

            'agents' => array_sum(array_map(
                fn ($owner) => count($owner['agents']),
                $owners
            )),

            'agencies' => count(array_unique(
                array_filter(array_column($owners, 'agency'))
            )),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | حفظ المالك - للتوافق مع JavaScript الحالي
    |--------------------------------------------------------------------------
    */

    public function saveOwner(array $data, ?int $id = null): void
    {
        if ($this->entityKey !== 'owners') {
            return;
        }

        if (empty($data['full_name']) || empty($data['phone'])) {

            $this->dispatch(
                'uf-error',
                message: 'يرجى إدخال الاسم ورقم الجوال'
            );

            return;
        }

        try {

            $status = $data['status'] ?? 'active';

            $isBlacklisted = $status === 'blocked';

            $isActive = $status !== 'inactive';

            $record = [

                'full_name' => $data['full_name'],

                'phone' => $data['phone'],

                'phone2' => !empty($data['phone2'])
                    ? $data['phone2']
                    : null,

                'email' => !empty($data['email'])
                    ? $data['email']
                    : null,

                'id_type' => $data['id_type'] ?: 'national_id',

                'id_number' => !empty($data['id_number'])
                    ? $data['id_number']
                    : null,

                'nationality' => $data['nationality'] ?: 'سعودي',

                'organization_id' => !empty($data['organization_id'])
                    ? (int) $data['organization_id']
                    : null,

                'address' => !empty($data['address'])
                    ? $data['address']
                    : null,

                'bank_name' => !empty($data['bank_name'])
                    ? $data['bank_name']
                    : null,

                'bank_account_name' => !empty($data['bank_account_name'])
                    ? $data['bank_account_name']
                    : null,

                'bank_account_number' => !empty($data['bank_account_number'])
                    ? $data['bank_account_number']
                    : null,

                'bank_iban' => !empty($data['bank_iban'])
                    ? $data['bank_iban']
                    : null,

                'preferred_payment_method' => $data['payment'] ?: 'bank_transfer',

                'notes' => !empty($data['notes'])
                    ? $data['notes']
                    : null,

                'is_active' => $isActive,

                'is_blacklisted' => $isBlacklisted,

                'blacklisted_at' => $isBlacklisted ? now() : null,

                'updated_at' => now(),

            ];

            if ($id) {

                DB::table('owners')
                    ->where('owner_id', $id)
                    ->whereNull('deleted_at')
                    ->update($record);

                $newId = $id;

                $message = 'تم تحديث بيانات المالك بنجاح ✓';

            } else {

                $record['created_at'] = now();

                $newId = DB::table('owners')
                    ->insertGetId($record);

                $message = 'تم إضافة المالك بنجاح ✓';

            }

            $this->dispatchUpdated($newId, $message);

        } catch (\Throwable $e) {

            Log::error('UniversalForm saveOwner error', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر حفظ بيانات المالك.'
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | حذف المالك
    |--------------------------------------------------------------------------
    */

    public function deleteOwner(int $id): void
    {
        if ($this->entityKey !== 'owners') {
            return;
        }

        try {

            DB::transaction(function () use ($id) {

                DB::table('owner_agents')
                    ->where('owner_id', $id)
                    ->delete();

                DB::table('owners')
                    ->where('owner_id', $id)
                    ->update([
                        'deleted_at' => now(),
                        'updated_at' => now(),
                    ]);

            });

            $this->dispatchUpdated(
                null,
                'تم حذف المالك بنجاح ✓'
            );

        } catch (\Throwable $e) {

            Log::error('UniversalForm deleteOwner error', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر حذف المالك.'
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | حظر أو إلغاء حظر المالك
    |--------------------------------------------------------------------------
    */

    public function blockOwner(int $id): void
    {
        if ($this->entityKey !== 'owners') {
            return;
        }

        $owner = DB::table('owners')
            ->where('owner_id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$owner) {
            return;
        }

        $wasBlocked = (bool) $owner->is_blacklisted;

        DB::table('owners')
            ->where('owner_id', $id)
            ->update([

                'is_blacklisted' => !$wasBlocked,

                'blacklisted_at' => !$wasBlocked ? now() : null,

                'updated_at' => now(),

            ]);

        $this->dispatchUpdated(
            $id,
            $wasBlocked
                ? 'تم إلغاء حظر المالك ✓'
                : 'تم حظر المالك ✓'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | حفظ الوكيل
    |--------------------------------------------------------------------------
    */

    public function saveAgent(
        int $ownerId,
        array $data,
        ?int $agentId = null
    ): void {

        if ($this->entityKey !== 'owners') {
            return;
        }

        if (empty($data['name']) || empty($data['phone'])) {

            $this->dispatch(
                'uf-error',
                message: 'يرجى إدخال اسم الوكيل ورقم الجوال'
            );

            return;
        }

        try {

            $ownerExists = DB::table('owners')
                ->where('owner_id', $ownerId)
                ->whereNull('deleted_at')
                ->exists();

            if (!$ownerExists) {
                throw new \RuntimeException('المالك غير موجود.');
            }

            $record = [

                'owner_id' => $ownerId,

                'full_name' => $data['name'],

                'phone' => $data['phone'],

                'email' => !empty($data['email'])
                    ? $data['email']
                    : null,

                'role' => $data['type'] ?: 'administrative',

                'notes' => !empty($data['notes'])
                    ? $data['notes']
                    : null,

                'updated_at' => now(),

            ];

            if ($agentId) {

                DB::table('owner_agents')
                    ->where('agent_id', $agentId)
                    ->where('owner_id', $ownerId)
                    ->update($record);

                $message = 'تم تحديث بيانات الوكيل بنجاح ✓';

            } else {

                $record['is_active'] = true;

                $record['created_at'] = now();

                DB::table('owner_agents')->insert($record);

                $message = 'تم إضافة الوكيل بنجاح ✓';

            }

            $this->dispatchUpdated($ownerId, $message);

        } catch (\Throwable $e) {

            Log::error('UniversalForm saveAgent error', [
                'owner_id' => $ownerId,
                'agent_id' => $agentId,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch(
                'uf-error',
                message: 'تعذر حفظ بيانات الوكيل.'
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | حذف الوكيل
    |--------------------------------------------------------------------------
    */

    public function deleteAgent(int $agentId, int $ownerId): void
    {
        if ($this->entityKey !== 'owners') {
            return;
        }

        DB::table('owner_agents')
            ->where('agent_id', $agentId)
            ->where('owner_id', $ownerId)
            ->delete();

        $this->dispatchUpdated(
            $ownerId,
            'تم حذف الوكيل بنجاح ✓'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | حظر أو تفعيل الوكيل
    |--------------------------------------------------------------------------
    */

    public function blockAgent(int $agentId, int $ownerId): void
    {
        if ($this->entityKey !== 'owners') {
            return;
        }

        $agent = DB::table('owner_agents')
            ->where('agent_id', $agentId)
            ->where('owner_id', $ownerId)
            ->first();

        if (!$agent) {
            return;
        }

        DB::table('owner_agents')
            ->where('agent_id', $agentId)
            ->where('owner_id', $ownerId)
            ->update([

                'is_active' => !$agent->is_active,

                'updated_at' => now(),

            ]);

        $this->dispatchUpdated(
            $ownerId,
            $agent->is_active
                ? 'تم تعطيل الوكيل ✓'
                : 'تم تفعيل الوكيل ✓'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | إرسال حدث التحديث القديم للملاك
    |--------------------------------------------------------------------------
    */

    protected function dispatchUpdated(
        ?int $keepOwner = null,
        string $message = ''
    ): void {

        if ($this->entityKey !== 'owners') {
            return;
        }

        $this->dispatch(
            'uf-data-updated',

            owners: $this->owners,

            stats: $this->stats,

            keepOwner: $keepOwner,

            message: $message
        );
    }

    /*
    |--------------------------------------------------------------------------
    | تسميات الحقول
    |--------------------------------------------------------------------------
    */

    public function getLabelsProperty(): array
    {
        $idType = [];

        $payment = [];

        $agent = [];

        foreach (($this->config['main_fields'] ?? []) as $field) {

            $key = $field['key'] ?? '';

            if ($key === 'id_type') {

                foreach (($field['options'] ?? []) as $option) {

                    $idType[$option['value']] = $option['label'];

                }

            }

            if (in_array($key, [
                'preferred_payment_method',
                'payment',
            ], true)) {

                foreach (($field['options'] ?? []) as $option) {

                    $payment[$option['value']] = $option['label'];

                }

            }

        }

        foreach (($this->config['sub_fields'] ?? []) as $field) {

            if (($field['key'] ?? '') === 'role') {

                foreach (($field['options'] ?? []) as $option) {

                    $agent[$option['value']] = $option['label'];

                }

            }

        }

        return [

            'idType' => $idType,

            'payment' => $payment,

            'agent' => $agent,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | إعادة ضبط البيانات
    |--------------------------------------------------------------------------
    */

    public function refreshData(): void
    {
        $this->currentPage = 1;

        unset(
            $this->records,
            $this->recordsMeta,
            $this->owners,
            $this->stats,
            $this->organizations,
            $this->labels
        );

        $this->dispatch(
            'uf-refreshed',
            message: 'تم تحديث البيانات.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | عرض الواجهة
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'front-organizations.livewire.shared.universal-form',
            [

                // البيانات العامة
                'records' => $this->records,

                'recordsMeta' => $this->recordsMeta,

                // بيانات التوافق مع واجهة الملاك
                'owners' => $this->owners,

                'stats' => $this->stats,

                'organizations' => $this->organizations,

                'labels' => $this->labels,

                'config' => $this->config,

            ]
        )->layout('front-organizations.layout.master');
    }
}