<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'status',
        'module_id',
    ];

    protected $casts = [
        'status' => 'string',
    ];
/**
 * إرجاع اسم الوحدة مترجمًا إلى العربية للعرض.
 */
public function getModuleDisplayAttribute(): string
{
    return $this->permissionModule?->display_name ?? 'غير محدد';
}
    /**
     * استثناءات هذه الصلاحية ضمن إسنادات الأدوار.
     */
    public function userPermissionOverrides(): HasMany
    {
        return $this->hasMany(UserPermissionOverride::class);
    }
    public function permissionModule(): BelongsTo
{
    return $this->belongsTo(
        PermissionModule::class,
        'module_id'
    );
}
}
