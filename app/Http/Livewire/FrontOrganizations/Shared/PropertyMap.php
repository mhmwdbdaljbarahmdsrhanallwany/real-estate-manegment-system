<?php

namespace App\Http\Livewire\FrontOrganizations\Shared;

use App\Services\Access\OrganizationContextService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PropertyMap extends Component
{
    #[Locked]
    public string $entityKey = 'buildings';

    #[Locked]
    public array $config = [];

    #[Locked]
    public ?int $organizationId = null;

    public array $filters = [];

    public string $search = '';

    public ?int $selectedId = null;

    public int $height = 600;

    public array $markers = [];

    public int $mapVersion = 0;

    /*
    |--------------------------------------------------------------------------
    | Initialization
    |--------------------------------------------------------------------------
    */

    public function mount(
        string $entityKey = 'buildings',
        int $height = 600
    ): void {
        $this->entityKey = $entityKey;
        $this->height = max(250, min($height, 1500));

        $this->config = $this->loadEntityConfig($entityKey);

        $this->organizationId = $this->resolveOrganizationId();

        foreach (($this->config['filters'] ?? []) as $filter) {
            $key = $filter['key'] ?? null;

            if ($key) {
                $this->filters[$key] = $filter['default'] ?? 'all';
            }
        }

        $this->refreshMarkers();
    }

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    */

    protected function loadEntityConfig(string $entityKey): array
    {
        $path = __DIR__ . '/UniversalForm/configs.php';

        if (!is_file($path)) {
            throw new \RuntimeException('ملف إعدادات الكيانات configs.php غير موجود.');
        }

        $all = require $path;

        if (!isset($all[$entityKey]) || !is_array($all[$entityKey])) {
            abort(404, "الكيان [{$entityKey}] غير معرّف.");
        }

        $config = $this->mergeConfig(
            $all['_defaults'] ?? [],
            $all[$entityKey]
        );

        $location = $config['location_picker'] ?? [];

        if (empty($location['enabled'])) {
            throw new AuthorizationException(
                "الكيان [{$entityKey}] لا يدعم عرض الخريطة."
            );
        }

        foreach (['table', 'primary_key'] as $key) {
            if (empty($config[$key])) {
                throw new AuthorizationException(
                    "إعداد {$key} مطلوب للكيان {$entityKey}."
                );
            }

            $this->assertSafeIdentifier($config[$key]);
        }

        $table = $config['table'];

        if (!Schema::hasTable($table)) {
            throw new AuthorizationException(
                "جدول {$table} غير موجود."
            );
        }

        if (!Schema::hasColumn($table, $config['primary_key'])) {
            throw new AuthorizationException(
                "المفتاح الأساسي غير موجود في جدول {$table}."
            );
        }

        $latitude = $location['latitude_field'] ?? 'latitude';
        $longitude = $location['longitude_field'] ?? 'longitude';

        $this->assertSafeIdentifier($latitude);
        $this->assertSafeIdentifier($longitude);

        if (
            !Schema::hasColumn($table, $latitude) ||
            !Schema::hasColumn($table, $longitude)
        ) {
            throw new AuthorizationException(
                "حقول الإحداثيات غير موجودة في جدول {$table}."
            );
        }

        return $config;
    }

    protected function mergeConfig(array $defaults, array $custom): array
    {
        foreach ($custom as $key => $value) {
            if (
                is_array($value) &&
                isset($defaults[$key]) &&
                is_array($defaults[$key])
            ) {
                $defaults[$key] = array_is_list($value)
                    ? $value
                    : $this->mergeConfig($defaults[$key], $value);
            } else {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }

    protected function assertSafeIdentifier(string $identifier): void
    {
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $identifier)) {
            throw new AuthorizationException(
                'اسم جدول أو حقل غير صالح.'
            );
        }
    }

    protected function resolveOrganizationId(): int
    {
        $id = app(OrganizationContextService::class)
            ->currentOrganizationId();

        if (!$id) {
            throw new AuthorizationException(
                'تعذر تحديد المؤسسة الحالية.'
            );
        }

        return (int) $id;
    }

    /*
    |--------------------------------------------------------------------------
    | Tenant Isolation
    |--------------------------------------------------------------------------
    */

    protected function tenantConfig(): array
    {
        if (isset($this->config['tenant']) &&
            is_array($this->config['tenant'])) {
            return $this->config['tenant'];
        }

        if (!empty($this->config['tenant_scoped'])) {
            return [
                'type' => 'direct',
                'column' => $this->config['organization_column']
                    ?? $this->config['tenant_column']
                    ?? null,
            ];
        }

        return ['type' => 'none'];
    }

    protected function applyTenantScope($query, string $table)
    {
        $tenant = $this->tenantConfig();

        $type = $tenant['type'] ?? 'none';

        if ($type === 'direct') {
            $column = $tenant['column'] ?? null;

            if (!$column) {
                throw new AuthorizationException(
                    'لم يتم تحديد حقل عزل المؤسسة.'
                );
            }

            $this->assertSafeIdentifier($column);

            if (!Schema::hasColumn($table, $column)) {
                throw new AuthorizationException(
                    "حقل عزل المؤسسة {$column} غير موجود."
                );
            }

            $query->where(
                $table . '.' . $column,
                $this->organizationId
            );
        }

        /*
         * عزل الكيانات التابعة من خلال جدول مرتبط.
         * مثال: الوحدات التابعة للمباني.
         */
        if ($type === 'relation') {
            $relatedTable = $tenant['table'] ?? null;
            $localKey = $tenant['local_key'] ?? null;
            $relatedKey = $tenant['related_key'] ?? null;
            $organizationColumn = $tenant['organization_column'] ?? null;

            foreach ([
                $relatedTable,
                $localKey,
                $relatedKey,
                $organizationColumn,
            ] as $identifier) {
                if (!$identifier) {
                    throw new AuthorizationException(
                        'إعدادات عزل المؤسسة عبر العلاقة غير مكتملة.'
                    );
                }

                $this->assertSafeIdentifier($identifier);
            }

            if (
                !Schema::hasTable($relatedTable) ||
                !Schema::hasColumn($table, $localKey) ||
                !Schema::hasColumn($relatedTable, $relatedKey) ||
                !Schema::hasColumn($relatedTable, $organizationColumn)
            ) {
                throw new AuthorizationException(
                    'تعذر التحقق من علاقة عزل المؤسسة.'
                );
            }

            $query->whereExists(function ($sub) use (
                $table,
                $relatedTable,
                $localKey,
                $relatedKey,
                $organizationColumn
            ) {
                $sub->selectRaw('1')
                    ->from($relatedTable)
                    ->whereColumn(
                        $relatedTable . '.' . $relatedKey,
                        $table . '.' . $localKey
                    )
                    ->where(
                        $relatedTable . '.' . $organizationColumn,
                        $this->organizationId
                    );

                if (
                    !empty($tenant['has_soft_deletes']) &&
                    Schema::hasColumn(
                        $relatedTable,
                        $tenant['deleted_at_column'] ?? 'deleted_at'
                    )
                ) {
                    $sub->whereNull(
                        $relatedTable . '.' .
                        ($tenant['deleted_at_column'] ?? 'deleted_at')
                    );
                }
            });
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Entity Query
    |--------------------------------------------------------------------------
    */

    protected function entityQuery()
    {
        $table = $this->config['table'];

        $query = DB::table($table);

        if (!empty($this->config['has_soft_deletes'])) {
            $deletedColumn = $this->config['deleted_at_column']
                ?? 'deleted_at';

            $this->assertSafeIdentifier($deletedColumn);

            if (Schema::hasColumn($table, $deletedColumn)) {
                $query->whereNull($table . '.' . $deletedColumn);
            }
        }

        return $this->applyTenantScope($query, $table);
    }

    /*
    |--------------------------------------------------------------------------
    | Search and Filters
    |--------------------------------------------------------------------------
    */

    protected function filteredQuery()
    {
        $table = $this->config['table'];

        $query = $this->entityQuery();

        $term = trim($this->search);

        if (
            $term !== '' &&
            !empty($this->config['search']['enabled'])
        ) {
            $fields = $this->config['search']['fields']
                ?? $this->config['search_fields']
                ?? [];

            $validFields = [];

            foreach ($fields as $field) {
                $this->assertSafeIdentifier($field);

                if (Schema::hasColumn($table, $field)) {
                    $validFields[] = $field;
                }
            }

            if ($validFields) {
                $query->where(function ($sub) use (
                    $validFields,
                    $table,
                    $term
                ) {
                    foreach ($validFields as $field) {
                        $sub->orWhere(
                            $table . '.' . $field,
                            'LIKE',
                            '%' . $term . '%'
                        );
                    }
                });
            }
        }

        foreach (($this->config['filters'] ?? []) as $filter) {
            $key = $filter['key'] ?? null;

            if (
                !$key ||
                !array_key_exists($key, $this->filters)
            ) {
                continue;
            }

            $value = $this->filters[$key];

            if (
                $value === null ||
                $value === '' ||
                $value === 'all'
            ) {
                continue;
            }

            $column = $filter['column'] ?? $key;

            $this->assertSafeIdentifier($column);

            if (!Schema::hasColumn($table, $column)) {
                continue;
            }

            $type = $filter['type'] ?? 'select';

            if ($type === 'text') {
                $query->where(
                    $table . '.' . $column,
                    'LIKE',
                    '%' . trim((string) $value) . '%'
                );
            } else {
                $query->where(
                    $table . '.' . $column,
                    $value
                );
            }
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Dynamic Marker Fields
    |--------------------------------------------------------------------------
    */

    protected function markerFields(): array
    {
        $location = $this->config['location_picker'] ?? [];

        return $location['marker_fields'] ?? [
            'price' => 'price',
            'area' => 'area',
            'bedrooms' => 'bedrooms',
            'bathrooms' => 'bathrooms',
            'city' => 'city',
            'district' => 'district',
            'property_type' => 'property_type',
            'image' => 'image',
        ];
    }

    protected function readField(
        object $row,
        ?string $field,
        mixed $default = null
    ): mixed {
        if (!$field) {
            return $default;
        }

        $this->assertSafeIdentifier($field);

        return $row->{$field} ?? $default;
    }

    protected function resolveStatusKey(
        mixed $raw,
        array $status
    ): string {
        $map = $status['boolean_map'] ?? null;

        if ($map) {
            $bool = in_array(
                $raw,
                [true, 1, '1', 'true', 'TRUE'],
                true
            );

            return (string) (
                $map[$bool ? 'true' : 'false']
                ?? ($bool ? 'active' : 'inactive')
            );
        }

        $allowed = $status['values'] ?? [];

        if (is_string($raw) && in_array($raw, $allowed, true)) {
            return $raw;
        }

        return $raw ? 'active' : 'inactive';
    }

    protected function resolveStatusLabel(
        mixed $raw,
        array $status
    ): string {
        $key = $this->resolveStatusKey($raw, $status);

        return $status['labels'][$key] ?? $key;
    }

    /*
    |--------------------------------------------------------------------------
    | Load Map Data
    |--------------------------------------------------------------------------
    */

    public function refreshMarkers(): void
    {
        $location = $this->config['location_picker'] ?? [];

        $table = $this->config['table'];

        $latitudeField = $location['latitude_field'] ?? 'latitude';
        $longitudeField = $location['longitude_field'] ?? 'longitude';

        $primaryKey = $this->config['primary_key'];
        $nameField = $this->config['name_field'] ?? $primaryKey;

        $statusConfig = $this->config['status'] ?? [];
        $statusField = $statusConfig['column'] ?? null;

        $fields = $this->markerFields();

        $rows = $this->filteredQuery()
            ->whereNotNull($table . '.' . $latitudeField)
            ->whereNotNull($table . '.' . $longitudeField)
            ->whereBetween($table . '.' . $latitudeField, [-90, 90])
            ->whereBetween($table . '.' . $longitudeField, [-180, 180])
            ->orderByDesc($table . '.' . $primaryKey)
            ->limit(2000)
            ->get();

        $this->markers = $rows->map(function ($row) use (
            $latitudeField,
            $longitudeField,
            $primaryKey,
            $nameField,
            $statusConfig,
            $statusField,
            $fields
        ) {
            $rawStatus = $this->readField(
                $row,
                $statusField
            );

            $imageField = $fields['image'] ?? null;

            $imagePath = $this->readField(
                $row,
                $imageField
            );

            $imageUrl = null;

            if (is_string($imagePath) && $imagePath !== '') {
                $imageUrl = Storage::disk('public')->url($imagePath);
            }

            $extra = [];

            foreach ($fields as $key => $field) {
                if ($key === 'image' || !$field) {
                    continue;
                }

                $value = $this->readField($row, $field);

                $extra[$key] = $value;
            }

            return array_merge([
                'id' => (int) $row->{$primaryKey},

                'title' => (string) (
                    $this->readField($row, $nameField, 'بدون اسم')
                ),

                'lat' => (float) $row->{$latitudeField},

                'lng' => (float) $row->{$longitudeField},

                'status' => $this->resolveStatusKey(
                    $rawStatus,
                    $statusConfig
                ),

                'status_label' => $this->resolveStatusLabel(
                    $rawStatus,
                    $statusConfig
                ),

                'image' => $imageUrl,

            ], $extra);
        })->values()->all();

        if (
            $this->selectedId &&
            !collect($this->markers)->contains(
                'id',
                $this->selectedId
            )
        ) {
            $this->selectedId = null;
        }

        if (!$this->selectedId && count($this->markers)) {
            $this->selectedId = $this->markers[0]['id'];
        }

        $this->mapVersion++;
    }

    /*
    |--------------------------------------------------------------------------
    | Livewire Actions
    |--------------------------------------------------------------------------
    */

    public function updatedSearch(): void
    {
        $this->refreshMarkers();
    }

    public function updatedFilters(): void
    {
        $this->refreshMarkers();
    }

    public function selectRecord(int $id): void
    {
        $exists = $this->entityQuery()
            ->where($this->config['primary_key'], $id)
            ->whereNotNull(
                $this->config['location_picker']['latitude_field']
            )
            ->exists();

        if (!$exists) {
            return;
        }

        $this->selectedId = $id;
    }

    public function clearFilters(): void
    {
        $this->search = '';

        foreach (($this->config['filters'] ?? []) as $filter) {
            $key = $filter['key'] ?? null;

            if ($key) {
                $this->filters[$key] = $filter['default'] ?? 'all';
            }
        }

        $this->refreshMarkers();
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render(): View
    {
        return view(
            'front-organizations.livewire.shared.property-map',
            [
                'markers' => $this->markers,

                'selectedId' => $this->selectedId,

                'mapConfig' => $this->config['location_picker'] ?? [],

                'componentId' => $this->getId(),

                'height' => $this->height,

                'mapVersion' => $this->mapVersion,

                'searchEnabled' => !empty(
                    $this->config['search']['enabled']
                ),

                'filterDefs' => $this->config['filters'] ?? [],

                'statusLabels' => $this->config['status']['labels'] ?? [],

                'totalCount' => count($this->markers),

                'entityTitle' => $this->config['title']
                    ?? $this->config['entity_name']
                    ?? 'الخريطة العقارية',

                'entitySingular' => $this->config['entity_singular']
                    ?? $this->config['singular']
                    ?? 'عنصر',
            ]
        );
    }
}
