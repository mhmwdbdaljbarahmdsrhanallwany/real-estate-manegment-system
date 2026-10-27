<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRole extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_assignment_id',
        'role_id',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * التعيين الذي ينتمي إليه إسناد الدور.
     */
    public function userAssignment(): BelongsTo
    {
        return $this->belongsTo(UserAssignment::class);
    }

    /**
     * الدور المرتبط.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * المستخدم الذي أنشأ السجل.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * آخر مستخدم قام بالتعديل.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * استثناءات الصلاحيات الخاصة بإسناد الدور.
     */
    public function permissionOverrides(): HasMany
    {
        return $this->hasMany(UserPermissionOverride::class);
    }
}
