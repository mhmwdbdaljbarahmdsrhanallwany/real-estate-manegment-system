<?php

namespace App\Services\Subscriptions;

use Carbon\Carbon;
use App\Models\Admin\Subscriptions\Subscription;


class SubscriptionStatusService
{
    /**
     * تاريخ انتهاء الفترة التجريبية
     */
    public function trialEndDate(Subscription $subscription)
    {
        return $subscription->trial_end_date? Carbon::parse($subscription->trial_end_date)->startOfDay(): null;
    }
    /**
     * الأيام المتبقية للفترة التجريبية
     */
    public function trialRemainingDays(Subscription $subscription): int
    {
        $date = $this->trialEndDate($subscription);
        if(!$date){
            return 0;
        }
        return (int) max(0,Carbon::now()->startOfDay()->diffInDays($date,false));
    }
    /**
     * تاريخ انتهاء الاشتراك
     */
    public function subscriptionEndDate(Subscription $subscription)
    {
        return $subscription->end_date? Carbon::parse($subscription->end_date)->startOfDay(): null;
    }
    /**
     * الأيام المتبقية لانتهاء الاشتراك
     */
    public function remainingDays(Subscription $subscription): int
    {
        $date = $this->subscriptionEndDate($subscription);
        if(!$date){
            return 0;
        }
        return (int) max(0,Carbon::now()->startOfDay()->diffInDays($date,false));
    }
    /**
     * تاريخ بداية تطبيق الغرامة
     *
     * نهاية الاشتراك + فترة السماح
     */
    public function penaltyDate(Subscription $subscription)
    {
        $endDate = $this->subscriptionEndDate($subscription);
        if(!$endDate){
            return null;
        }
        return $endDate->copy()->addDays($subscription->package->grace_period_days ?? 0);
    }
    /**
     * الأيام المتبقية قبل تطبيق الغرامة
     */
    public function penaltyRemainingDays(Subscription $subscription): int
    {
        $date = $this->penaltyDate($subscription);
        if(!$date){
            return 0;
        }
        return (int) max(0,Carbon::now()->startOfDay()->diffInDays($date,false));
    }
    /**
     * تاريخ إيقاف الاشتراك
     *
     * نهاية الاشتراك
     * + فترة السماح
     * + مدة التأخير قبل الإيقاف
     */
    public function suspendDate(Subscription $subscription)
    {
        $penaltyDate = $this->penaltyDate($subscription);
        if(!$penaltyDate){
            return null;
        }
        return $penaltyDate->copy()->addDays($subscription->package->suspend_after_days ?? 0);
    }
    /**
     * الأيام المتبقية قبل إيقاف الاشتراك
     */
    public function suspendRemainingDays(Subscription $subscription): int
    {
        $date = $this->suspendDate($subscription);
        if(!$date){
            return 0;
        }
        return (int) max(0,Carbon::now()->startOfDay()->diffInDays($date,false)
        );
    }
    /**
     * الحالة الحالية للاشتراك
     */
/**
 * الحالة الحالية للاشتراك
 */

/**
 * حساب المرحلة الزمنية للاشتراك مع مراعاة حالته الإدارية.
 * هذه الدالة لا تغيّر حالة الاشتراك المحفوظة في قاعدة البيانات.
 */
public function currentState(Subscription $subscription): array
{
    // الحالات الإدارية التي لا ينبغي أن تدخل دورة المراحل الزمنية.
    if ($subscription->status === 'cancelled') {
        return [
            'key' => 'cancelled',
            'label' => 'ملغي',
            'color' => 'secondary',
        ];
    }

    if ($subscription->status === 'pending') {
        return [
            'key' => 'pending',
            'label' => 'قيد الانتظار',
            'color' => 'warning',
        ];
    }

    // لا نحسب مراحل الغرامة دون باقة مرتبطة بالاشتراك.
    if (!$subscription->package) {
        return [
            'key' => 'unknown',
            'label' => 'تعذّر تحديد المرحلة',
            'color' => 'secondary',
        ];
    }

    $today = Carbon::today();
    $endDate = $this->subscriptionEndDate($subscription);

    if (!$endDate) {
        return [
            'key' => 'unknown',
            'label' => 'تعذّر تحديد المرحلة',
            'color' => 'secondary',
        ];
    }

    // الاشتراك التجريبي له نهاية تجريبية مستقلة.
    if ($subscription->subscription_type === 'trial') {
        $trialEndDate = $this->trialEndDate($subscription);

        if (!$trialEndDate) {
            return [
                'key' => 'unknown',
                'label' => 'تاريخ انتهاء التجربة غير محدد',
                'color' => 'secondary',
            ];
        }

        if ($today->lessThan($trialEndDate)) {
            return [
                'key' => 'trial',
                'label' => 'فترة تجريبية',
                'color' => 'info',
            ];
        }

        // لا نعامل انتهاء التجربة وحده كاشتراك مدفوع متأخر.
        return [
            'key' => 'trial_ended',
            'label' => 'انتهت الفترة التجريبية',
            'color' => 'warning',
        ];
    }

    // من هنا نتعامل مع الاشتراك المدفوع فقط.
    if ($today->lessThan($endDate)) {
        return [
            'key' => 'active',
            'label' => 'نشط',
            'color' => 'success',
        ];
    }

    $penaltyDate = $this->penaltyDate($subscription);
    $suspendDate = $this->suspendDate($subscription);

    if (!$penaltyDate || !$suspendDate) {
        return [
            'key' => 'unknown',
            'label' => 'تعذّر تحديد المرحلة',
            'color' => 'secondary',
        ];
    }

    if ($today->lessThan($penaltyDate)) {
        return [
            'key' => 'grace',
            'label' => 'فترة سماح',
            'color' => 'warning',
        ];
    }

    if ($today->lessThan($suspendDate)) {
        return [
            'key' => 'penalty',
            'label' => 'فترة غرامة',
            'color' => 'danger',
        ];
    }

    return [
        'key' => 'suspension_due',
        'label' => 'بلغ موعد الإيقاف',
        'color' => 'dark',
    ];
}



}