<?php

namespace App\Services\Subscriptions;

use App\Models\Admin\Subscriptions\Subscription;
use Carbon\Carbon;
use InvalidArgumentException;

class PenaltyService
{
    /**
     * حساب إجمالي الغرامة المستحقة حتى تاريخ محدد.
     *
     * النسبة تُحسب دائمًا من final_price المحفوظ.
     * max_penalty = 0 يعني عدم وجود سقف.
     *
     * هذه الدالة للحساب فقط، ولا تنشئ فاتورة.
     */
    public function calculateAccruedPenalty(
        Subscription $subscription,
        ?Carbon $asOf = null
    ): array {
        $subscription->loadMissing('package');

        $package = $subscription->package;

        if (!$package) {
            throw new InvalidArgumentException(
                'لا توجد باقة مرتبطة بالاشتراك.'
            );
        }

        if ($subscription->final_price === null) {
            throw new InvalidArgumentException(
                'السعر النهائي المحفوظ للاشتراك غير موجود.'
            );
        }

        $finalPrice = round(
            (float) $subscription->final_price,
            2
        );

        $penaltyType = $package->penalty_type;
        $penaltyValue = (float) $package->penalty_value;
        $repeat = $package->penalty_repeat;
        $maxPenalty = (float) $package->max_penalty;

        $asOf = ($asOf ?? Carbon::today())->copy()->startOfDay();

        if (
            $finalPrice < 0 ||
            $penaltyValue < 0 ||
            $maxPenalty < 0
        ) {
            throw new InvalidArgumentException(
                'لا يجوز أن تكون قيمة السعر أو الغرامة أو سقفها سالبة.'
            );
        }

        if (
            $penaltyType === null ||
            $penaltyValue == 0
        ) {
            return $this->emptyResult(
                $finalPrice,
                $maxPenalty,
                $asOf
            );
        }

        if (!in_array($penaltyType, ['fixed', 'percent'], true)) {
            throw new InvalidArgumentException(
                'نوع الغرامة غير صالح.'
            );
        }

        if (
            $penaltyType === 'percent' &&
            $penaltyValue > 100
        ) {
            throw new InvalidArgumentException(
                'نسبة الغرامة لا يمكن أن تتجاوز 100%.'
            );
        }

        if (!in_array(
            $repeat,
            ['once', 'daily', 'weekly', 'monthly'],
            true
        )) {
            throw new InvalidArgumentException(
                'تكرار الغرامة غير صالح.'
            );
        }

        if (!$subscription->end_date) {
            throw new InvalidArgumentException(
                'تاريخ انتهاء الاشتراك غير موجود.'
            );
        }

        // تبدأ الغرامة في بداية يوم penalty_date.
        $penaltyStartDate = Carbon::parse(
            $subscription->end_date
        )->startOfDay()->addDays(
            (int) $package->grace_period_days
        );

        // لم يحن موعد الغرامة بعد.
        if ($asOf->lt($penaltyStartDate)) {
            return $this->emptyResult(
                $finalPrice,
                $maxPenalty,
                $asOf,
                $penaltyStartDate
            );
        }

        // قيمة كل غرامة مستقلة، دون تركيب النسب على الغرامات السابقة.
        $periodAmount = $penaltyType === 'percent'
            ? round($finalPrice * $penaltyValue / 100, 2)
            : round($penaltyValue, 2);

        $periodCount = $this->countDuePeriods(
            $penaltyStartDate,
            $asOf,
            $repeat
        );

        $uncappedTotal = round(
            $periodAmount * $periodCount,
            2
        );

        // الصفر يعني عدم تطبيق سقف.
        $totalPenalty = $maxPenalty == 0
            ? $uncappedTotal
            : min($uncappedTotal, $maxPenalty);

        $totalPenalty = round($totalPenalty, 2);

        return [
            'subscription_id' => $subscription->subscription_id,
            'penalty_start_date' => $penaltyStartDate->toDateString(),
            'as_of_date' => $asOf->toDateString(),
            'final_price' => $finalPrice,
            'penalty_type' => $penaltyType,
            'penalty_value' => $penaltyValue,
            'penalty_repeat' => $repeat,
            'period_amount' => $periodAmount,
            'period_count' => $periodCount,
            'uncapped_total' => $uncappedTotal,
            'max_penalty' => $maxPenalty,
            'total_penalty' => $totalPenalty,
        ];
    }

    /**
     * حساب عدد مرات استحقاق الغرامة حتى التاريخ المحدد.
     */
    private function countDuePeriods(
        Carbon $start,
        Carbon $asOf,
        string $repeat
    ): int {
        if ($repeat === 'once') {
            return 1;
        }

        $days = (int) $start->diffInDays($asOf);

        if ($repeat === 'daily') {
            return $days + 1;
        }

        if ($repeat === 'weekly') {
            return intdiv($days, 7) + 1;
        }

        // كل شهر محسوب انطلاقًا من تاريخ البداية الأصلي،
        // لتجنب انزياح موعد التكرار في نهاية الشهر.
        $count = 0;
        $index = 0;

        while (true) {
            $dueDate = $start->copy()
                ->addMonthsNoOverflow($index);

            if ($dueDate->gt($asOf)) {
                break;
            }

            $count++;
            $index++;
        }

        return $count;
    }

    private function emptyResult(
        float $finalPrice,
        float $maxPenalty,
        Carbon $asOf,
        ?Carbon $penaltyStartDate = null
    ): array {
        return [
            'penalty_start_date' => $penaltyStartDate?->toDateString(),
            'as_of_date' => $asOf->toDateString(),
            'final_price' => $finalPrice,
            'period_amount' => 0.0,
            'period_count' => 0,
            'uncapped_total' => 0.0,
            'max_penalty' => $maxPenalty,
            'total_penalty' => 0.0,
        ];
    }
}
