<?php

namespace App\Models\Admin\Packages;
use Illuminate\Support\Facades\Route;

use App\Models\Admin\Organizations\Organization;
use App\Models\Admin\Subscriptions\Subscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';
    protected $primaryKey = 'package_id';

    protected $fillable = [
        'name',
        'code',
        'level',
        'sort_order',
        'description',
        'base_price',
        'discount_type',
        'discount_value',
        'tax_type',
        'tax_value',
        'billing_cycle',
        'duration_days',
        'reminder_before_days',
        'reminder_after_expiry_days',
        'grace_period_days',
        'penalty_type',
        'penalty_value',
        'penalty_repeat',
        'max_penalty',
        'suspend_after_days',
        'allow_login_after_expiry',
        'allow_readonly_mode',
        'max_users',
        'max_branches',
        'max_properties',
        'max_units',
        'storage_limit_mb',
        'max_api_requests',
        'is_featured',
        'is_active',
        'notes',
    ];


/**
 * اسم دورة الفوترة بالعربية
 */
public function getBillingCycleLabelAttribute()
{
    return [
        'daily'      => 'يومي',
        'weekly'     => 'أسبوعي',
        'monthly'    => 'شهري',
        'quarterly'  => 'ربع سنوي',
        'semiannual' => 'نصف سنوي',
        'yearly'     => 'سنوي',
    ][$this->billing_cycle] ?? '-';
}

/**
 * حالة الباقة
 */
public function getStatusLabelAttribute()
{
    return $this->is_active
        ? 'مفعلة'
        : 'غير مفعلة';
}

/**
 * لون حالة الباقة
 */
public function getStatusColorAttribute()
{
    return $this->is_active
        ? 'success'
        : 'danger';
}

/**
 * الباقة المميزة
 */
public function getFeaturedLabelAttribute()
{
    return $this->is_featured
        ? 'مميزة'
        : 'عادية';
}

/**
 * لون شارة الباقة المميزة
 */
public function getFeaturedColorAttribute()
{
    return $this->is_featured
        ? 'warning'
        : 'secondary';
}
public function organizations()
{
    return $this->belongsToMany(
        Organization::class,
        'subscriptions',
        'package_id',
        'organization_id'
    );
}


public function menuLinks()
{
    return [

        [
            'title' => 'معلومات الباقة',
            'tab'   => 'info',
        ],

        // [
        //     'title' => 'المميزات',
        //     'tab'   => 'features',
        // ],

        // [
        //     'title' => 'الحدود والاستخدام',
        //     'tab'   => 'limits',
        // ],

        [
            'title' => 'المؤسسات المشتركة',
            'tab'   => 'organizations',
        ],

    ];
}



public function getShowRouteAttribute()
{
    return route(
        'admin.packages.show',
        $this->package_id
    );
}

    public function subscriptions()
{
    return $this->hasMany(Subscription::class, 'package_id', 'package_id');
}
}
