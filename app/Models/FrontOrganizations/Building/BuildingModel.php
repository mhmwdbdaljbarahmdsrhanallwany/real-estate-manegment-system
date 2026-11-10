<?php

namespace App\Models\FrontOrganizations\Building;

use App\Http\Livewire\FrontOrganizations\Owner\Owner;
use App\Models\Admin\Organizations\Organization;
use App\Models\FrontOrganizations\AgencyBranch\AgencyBranchModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuildingModel extends Model
{
   
use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | إعدادات الجدول
    |--------------------------------------------------------------------------
    */

    protected $table = 'buildings';

    protected $primaryKey = 'building_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    /*
    |--------------------------------------------------------------------------
    | الحقول القابلة للتعبئة
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        // العلاقات الأساسية
        'owner_id',
        'organization_id',
        'branch_id',

        // البيانات الأساسية
        'building_name',
        'building_number',

        // العنوان
        'address_line1',
        'address_line2',
        'city',
        'district',
        'street',
        'postal_code',
        'country',

        // الموقع الجغرافي
        'latitude',
        'longitude',

        // مواصفات المبنى
        'number_of_floors',
        'number_of_units',
        'year_built',
        'land_area',
        'building_area',

        // نوع العقار
        'property_type',

        // الخدمات والمرافق
        'has_elevator',
        'has_parking',
        'has_security',
        'has_maintenance',

        // الملاحظات والمرفقات
        'notes',
        'images',
        'documents',

        // الحالة
        'is_active',
    ];

    /*
    |--------------------------------------------------------------------------
    | تحويل أنواع البيانات
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'owner_id' => 'integer',
            'organization_id' => 'integer',
            'branch_id' => 'integer',

            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',

            'number_of_floors' => 'integer',
            'number_of_units' => 'integer',
            'year_built' => 'integer',

            'land_area' => 'decimal:2',
            'building_area' => 'decimal:2',

            'has_elevator' => 'boolean',
            'has_parking' => 'boolean',
            'has_security' => 'boolean',
            'has_maintenance' => 'boolean',

            'images' => 'array',
            'documents' => 'array',

            'is_active' => 'boolean',

            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | علاقة المبنى بالمالك
    |--------------------------------------------------------------------------
    */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(
            Owner::class,
            'owner_id',
            'owner_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | علاقة المبنى بالمؤسسة
    |--------------------------------------------------------------------------
    */

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id',
            'organization_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | علاقة المبنى بالفرع
    |--------------------------------------------------------------------------
    */

    public function branch(): BelongsTo
    {
        return $this->belongsTo(
             AgencyBranchModel::class,
            'branch_id',
            'branch_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | نطاق المباني النشطة
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | نطاق مباني مؤسسة محددة
    |--------------------------------------------------------------------------
    */

    public function scopeForOrganization($query, int $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /*
    |--------------------------------------------------------------------------
    | نطاق مباني مالك محدد
    |--------------------------------------------------------------------------
    */

    public function scopeForOwner($query, int $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    /*
    |--------------------------------------------------------------------------
    | نطاق مباني فرع محدد
    |--------------------------------------------------------------------------
    */

    public function scopeForBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }
}
