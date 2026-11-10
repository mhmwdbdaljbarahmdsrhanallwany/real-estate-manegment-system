<?php

namespace App\Models\FrontOrganizations\AgencyBranch;

use App\Models\Admin\Organizations\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\FrontOrganizations\Building\BuildingModel;
class AgencyBranchModel extends Model
{
    
use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | إعدادات الجدول
    |--------------------------------------------------------------------------
    */

    protected $table = 'agency_branches';

    protected $primaryKey = 'branch_id';

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

        'organization_id',
        'branch_name',
        'branch_code',
        'branch_type',

        'address',
        'city',
        'district',

        'phone',
        'mobile',
        'email',

        'manager_id',

        'status',
        'opening_hours',

        'latitude',
        'longitude',
    ];

    /*
    |--------------------------------------------------------------------------
    | تحويل أنواع البيانات
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'branch_id' => 'integer',
            'organization_id' => 'integer',
            'manager_id' => 'integer',

            'opening_hours' => 'array',

            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',

            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | علاقة الفرع بالمؤسسة
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
    | علاقة الفرع بالمباني
    |--------------------------------------------------------------------------
    */

    public function buildings(): HasMany
    {
        return $this->hasMany(
            BuildingModel::class,
            'branch_id',
            'branch_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | نطاق الفروع النشطة
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /*
    |--------------------------------------------------------------------------
    | نطاق الفروع الرئيسية
    |--------------------------------------------------------------------------
    */

    public function scopeMain($query)
    {
        return $query->where('branch_type', 'main');
    }

    /*
    |--------------------------------------------------------------------------
    | نطاق الفروع الفرعية
    |--------------------------------------------------------------------------
    */

    public function scopeSub($query)
    {
        return $query->where('branch_type', 'sub');
    }

    /*
    |--------------------------------------------------------------------------
    | نطاق فروع مؤسسة محددة
    |--------------------------------------------------------------------------
    */

    public function scopeForOrganization($query, int $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /*
    |--------------------------------------------------------------------------
    | نطاق الفروع حسب الحالة
    |--------------------------------------------------------------------------
    */

    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
