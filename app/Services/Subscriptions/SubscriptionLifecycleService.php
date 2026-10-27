<?php

namespace App\Services\Subscriptions;

use Carbon\Carbon;
use App\Models\Admin\Packages\Package;

class SubscriptionLifecycleService
{

    /**
     * حساب دورة حياة الاشتراك
     */
  
public function calculate(
    Package $package,
    string $subscriptionType,
    int $trialDays = 0,
    ?string $trialStartDate = null
): array {
    $today = Carbon::today();

    // بداية التجربة الأصلية، أو اليوم عند إنشاء اشتراك جديد.
    $trialStart = $trialStartDate
        ? Carbon::parse($trialStartDate)->startOfDay()
        : $today->copy();

    $trialEndDate = null;

    if ($subscriptionType === 'trial' && $trialDays > 0) {
        $trialEndDate = $trialStart
            ->copy()
            ->addDays($trialDays);
    }

    // الاشتراك المدفوع يبدأ عند انتهاء التجربة،
    // أو اليوم إذا لم توجد تجربة.
    $startDate = $trialEndDate
        ? $trialEndDate->copy()
        : $today->copy();

    $endDate = $startDate->copy()->addDays(
        $package->duration_days
    );

    $penaltyDate = $endDate->copy()->addDays(
        $package->grace_period_days
    );

    $suspendDate = $penaltyDate->copy()->addDays(
        $package->suspend_after_days
    );

    return [
        'trial_end_date' => $trialEndDate?->format('Y-m-d'),
        'start_date' => $startDate->format('Y-m-d'),
        'end_date' => $endDate->format('Y-m-d'),
        'penalty_date' => $penaltyDate->format('Y-m-d'),
        'suspend_date' => $suspendDate->format('Y-m-d'),
    ];
}


}