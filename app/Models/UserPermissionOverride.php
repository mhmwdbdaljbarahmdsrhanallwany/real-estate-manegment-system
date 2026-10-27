<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermissionOverride extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_role_id',
        'permission_id',
        'effect',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * إسناد الدور الذي يتبع له الاستثناء.
     */
    public function userRole(): BelongsTo
    {
        return $this->belongsTo(UserRole::class);
    }

    /**
     * الصلاحية التي ينطبق عليها الاستثناء.
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * المستخدم الذي أنشأ الاستثناء.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * آخر مستخدم قام بتعديل الاستثناء.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
