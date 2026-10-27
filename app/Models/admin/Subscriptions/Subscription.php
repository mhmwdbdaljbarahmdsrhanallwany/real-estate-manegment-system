<?php

namespace App\Models\Admin\Subscriptions;

use App\Models\Admin\Organizations\Organization;
use App\Models\Admin\Packages\Package;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin\Invoices\Invoice;
class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * اسم الجدول
     */
    protected $table = 'subscriptions';

    /**
     * المفتاح الأساسي
     */
    protected $primaryKey = 'subscription_id';

    /**
     * الحقول القابلة للإدخال
     */
    protected $fillable = [

        'organization_id',

        'package_id',

        'subscription_type',
        'billing_mode',
        'trial_period_days',

        'trial_end_date',

        'start_date',

        'end_date',

        'status',
'package_base_price',
'discount_type',
'discount_value',
'discount_amount',
'tax_type',
'tax_value',
'tax_amount',
'final_price',
        'activated_at',

        'auto_renew',

        'notes',
    ];

    /**
     * تحويل أنواع البيانات
     */
    protected $casts = [

        'trial_end_date' => 'date',

        'start_date' => 'date',

        'end_date' => 'date',

        'activated_at' => 'datetime',
'package_base_price' => 'decimal:2',
'discount_value'     => 'decimal:2',
'discount_amount'   => 'decimal:2',
'tax_value'          => 'decimal:2',
'tax_amount'         => 'decimal:2',
'final_price'        => 'decimal:2',
        'auto_renew' => 'boolean',
    ];

    /**
     * تحديث تاريخ المؤسسة عند تعديل الاشتراك
     */
    protected $touches = ['organization'];


    public function getTrialPeriodLabelAttribute()
{
    return $this->trial_period_days . ' يوم';
}

public function getAutoRenewLabelAttribute()
{
    return $this->auto_renew ? 'نعم' : 'لا';
}

    /**
     * المؤسسة
     */
    public function organization()
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id',
            'organization_id'
        );
    }

    /**
     * الباقة
     */
    public function package()
    {
        return $this->belongsTo(
            Package::class,
            'package_id',
            'package_id'
        );
    }
    public function invoices()
    {
        return $this->hasMany(
            Invoice::class,
            'subscription_id',
            'subscription_id'
        );
    }
    /**
     * اسم نوع الاشتراك بالعربية
     */
    public function getSubscriptionTypeLabelAttribute()
    {
        return [
            'trial' => 'تجريبي',
            'paid' => 'مدفوع',
        ][$this->subscription_type] ?? '-';
    }

    /**
     * اسم الحالة بالعربية
     */
    public function getStatusLabelAttribute()
    {
        return [
            'pending'   => 'قيد الانتظار',
            'active'    => 'نشط',
            'expired'   => 'منتهي الصلاحية',
            'cancelled' => 'ملغي',
        ][$this->status] ?? '-';
    }
    /**
 * روابط تنقل صفحة الاشتراك
 */
public function menuLinks()
{
    return [

        [
            'title' => 'معلومات الاشتراك',
            'tab'   => 'info',
        ],

        [
            'title' => 'الاجراءات',
            'tab'   => 'action',
        ],

        // [
        //     'title' => 'المدفوعات',
        //     'tab'   => 'payments',
        // ],

        // [
        //     'title' => 'التجديدات',
        //     'tab'   => 'renewals',
        // ],

        // [
        //     'title' => 'النشاطات',
        //     'tab'   => 'activity',
        // ],

    ];
}


/**
 * رابط صفحة العرض
 */
public function getShowRouteAttribute()
{
    return route(
        'admin.subscriptions.show',
        $this->subscription_id
    );
}

}
