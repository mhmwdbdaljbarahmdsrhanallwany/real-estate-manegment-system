<?php

namespace App\Models\admin\Subscription;

use App\Models\admin\AgencySubscription\AgencySubscription;
use Illuminate\Database\Eloquent\Model;

class SubscriptionModel extends Model
{
    // اسم الجدول المرتبط بالموديل
    protected $table = 'subscription_plans';

    // المفتاح الأساسي
    protected $primaryKey = 'plan_id';

    // الحقول القابلة للتعبئة
    protected $fillable = [
        'plan_name',          // اسم الباقة
        'plan_tier',          // مستوى الباقة (Basic, Standard, Premium, Enterprise)
        'plan_price',         // السعر الأساسي
        'currency',           // العملة
        'tax_rate',           // نسبة الضريبة
        'duration',           // مدة الباقة (أيام)
        'status',             // حالة الباقة (نشطة، موقوفة...)
        'discount_rate',      // نسبة الخصم
        'discount_amount',    // قيمة الخصم
        'max_users',          // الحد الأقصى للمستخدمين
        'max_branches',       // الحد الأقصى للفروع
        'storage_space',      // مساحة التخزين
        'features',           // الميزات

        // الحقول الجديدة
        'subscription_type',  // نوع الباقة (monthly, yearly, long_term)
        'trial_period_value', // قيمة الفترة التجريبية (مثلاً 7 أو 14)
        'trial_period_unit',  // وحدة الفترة التجريبية (days, weeks, months)
        'late_fee',           // غرامة التأخير
        'grace_period',       // فترة السماح
    ];

    // Accessors لعرض القيم بالعربية
    public function getPlanTierLabelAttribute()
    {
        return [
            'Basic'=> 'أساسية',
            'Standard'=> 'قياسية',
            'Premium'=> 'متقدمة',
            'Enterprise'=> 'مؤسسات',
        ][$this->plan_tier] ?? $this->plan_tier;
    }

    public function getCurrencyLabelAttribute()
    {
        return [
            'USD'=>'دولار أمريكي',
            'EUR'=>'يورو',
            'SAR'=>'ريال سعودي',
            'GBP'=>'جنيه إسترليني',
            'YER'=>'ريال يمني',
        ][$this->currency] ?? $this->currency;
    }

    public function getStatusLabelAttribute()
    {
        return [
            'pending_approval' => 'قيد الموافقة',
            'active'=> 'نشطة',
            'suspended'=> 'موقوفة',
            'blocked'=> 'محظورة',
            'expired'=> 'منتهية',
        ][$this->status] ?? $this->status;
    }

    // Accessor لعرض نوع الباقة بالعربية
    public function getSubscriptionTypeLabelAttribute()
    {
        return [
            'monthly'   => 'شهري',
            'yearly'    => 'سنوي',
            'long_term' => 'أكثر من سنة',
        ][$this->subscription_type] ?? $this->subscription_type;
    }

    // Accessor لعرض وحدة الفترة التجريبية بالعربية
    public function getTrialPeriodUnitLabelAttribute()
    {
        return [
            'days'   => 'أيام',
            'weeks'  => 'أسابيع',
            'months' => 'أشهر',
        ][$this->trial_period_unit] ?? $this->trial_period_unit;
    }
    public function subscriptions()
{
    return $this->hasMany(AgencySubscription::class,'plan_id');
}
}
