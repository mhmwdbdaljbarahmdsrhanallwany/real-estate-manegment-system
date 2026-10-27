<?php

namespace App\Models;

use App\Models\Admin\Organizations\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessContext extends Model
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Model Configuration
    |--------------------------------------------------------------------------
    */
    /**
     * اسم الجدول.
     */
    protected $table = 'access_contexts';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    /**
     * الحقول القابلة للتعبئة الجماعية.
     */
    protected $fillable = [
        // بيانات سياق الوصول
        'name',
        'context_type',

        // بيانات الرابط
        'domain',
        'subdomain',

        // المؤسسة المرتبطة
        'organization_id',

        // حالة السياق
        'status',

        // بيانات التتبع
        'created_by',
        'updated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    /**
     * تحويل أنواع البيانات.
     */
    protected $casts = [
        'organization_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * المؤسسة المرتبطة بسياق الوصول.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id',
            'organization_id'
        );
    }

    /**
     * المستخدم الذي أنشأ سياق الوصول.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
    }

    /**
     * المستخدم الذي أجرى آخر تعديل.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by',
            'id'
        );
    }

    /**
     * تعيينات المستخدمين المرتبطة بسياق الوصول.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(
            UserAssignment::class,
            'access_context_id',
            'id'
        );
    }

  
/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

/**
 * روابط تبويبات عرض سياق الوصول.
 */
public function menuLinks(): array
{
    return [
        [
            'title' => 'معلومات السياق',
            'tab' => 'info',
        ],
        [
            'title' => 'التعيينات والمستخدمون',
            'tab' => 'assignments',
        ],
    ];
}

/**
 * رابط صفحة عرض سياق الوصول.
 */
public function getShowRouteAttribute(): string
{
    return route('admin.access_contexts.show', [
        'id' => $this->id,
    ]);
}

}
