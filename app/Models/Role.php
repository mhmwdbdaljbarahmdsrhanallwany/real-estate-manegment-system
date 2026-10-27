<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * الصلاحيات المرتبطة بالدور.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_has_permissions',
            'role_id',
            'permission_id'
        );
    }

    /**
     * إسنادات هذا الدور داخل تعيينات المستخدمين.
     */
    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * روابط تبويبات عرض الدور.
     */
    public function menuLinks(): array
    {
        return [
            [
                'title' => 'معلومات الدور',
                'tab' => 'info',
            ],
            [
                'title' => 'الصلاحيات',
                'tab' => 'permissions',
            ],
            [
                'title' => 'المستخدمون',
                'tab' => 'users',
            ],
        ];
    }

    /**
     * رابط صفحة عرض الدور.
     */
    public function getShowRouteAttribute(): string
    {
        return route('admin.roles.show', [
            'id' => $this->id,
        ]);
    }
}
