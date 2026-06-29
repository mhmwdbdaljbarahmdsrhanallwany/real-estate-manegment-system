<?php

namespace App\Models\admin\AgencySubscription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencySubscription extends Model
{
    use HasFactory;

    protected $table = 'agency_subscriptions';
    protected $primaryKey = 'AgencySubscriptionID';
    protected $touches = ['agency'];

    protected $fillable = [
        'AgencyID',
        'PlanID',
        'SubscriptionType',
        'StartDate',
        'EndDate',
        'RenewalDate',
        'TrialPeriod',
        'PlanStatus',
        'PlanPrice',
        'TaxRate',
        'LateFee',
        'LateFeePaid',
        'LateFeePaymentDate',
        'GracePeriod',
        'WarningSent',
        'WarningCount',
        'LastWarningDate',
        'DiscountRate',
        'DiscountAmount',
        'DiscountReason',
        'AmountPaid',
        'AmountRemaining',
        'AdminNotes',
    ];

    // العلاقات
    public function agency()
    {
        return $this->belongsTo(\App\Models\admin\Agency\Agencymodel::class, 'AgencyID', 'agency_id');
    }

    public function plan()
    {
        return $this->belongsTo(\App\Models\admin\Subscription\SubscriptionModel::class, 'PlanID', 'plan_id');
    }
// Accessor لعرض الحالة بشكل أوضح بالعربية
public function getPlanStatusAttribute($value)
{
    $statuses = [
        'trial'=> 'تجريبي',
        'active' => 'نشطة',
        'expired'=> 'منتهية',
        'grace_period'=> 'فترة سماح',
    ];

    return $statuses[$value] ?? $value;
}


    // Accessor لعرض الملاحظات الإدارية بشكل منسق
    public function getAdminNotesAttribute($value)
    {
        return $value ? nl2br($value) : 'لا توجد ملاحظات';
    }
}
