<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAssignment extends Model
{
    use SoftDeletes;

    /**
     * المستخدم صاحب التعيين.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * سياق الوصول.
     */
    public function accessContext(): BelongsTo
    {
        return $this->belongsTo(AccessContext::class);
    }

    /**
     * الأدوار المسندة إلى هذا التعيين.
     */
    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * منشئ التعيين.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * آخر من عدّل التعيين.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
