<?php

namespace App\Services\Invoices;

use App\Models\Admin\Invoices\Invoice;
use App\Models\Admin\Packages\Package;
use App\Models\Admin\Subscriptions\Subscription;
use App\Services\Subscriptions\PenaltyService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class InvoiceService
{
    public function findGeneralInvoice(
        Subscription $subscription
    ): ?Invoice {
        return Invoice::where(
            'subscription_id',
            $subscription->subscription_id
        )
            ->whereRaw(
                "JSON_UNQUOTE(JSON_EXTRACT(calculation_details, '$.invoice_kind')) = ?",
                ['general']
            )
            ->first();
    }

    /**
     * حساب لقطة التسعير مرة واحدة عند إنشاء الاشتراك.
     */
    public function createForSubscription(
        Subscription $subscription,
        Package $package
    ): Invoice {
        return DB::transaction(function () use ($subscription) {

            // 1. قفل سجل الاشتراك قبل فحص الفاتورة.
            // الطلبات الأخرى التي تستخدم القفل نفسه ستنتظر.
            $lockedSubscription = Subscription::where(
                'subscription_id',
                $subscription->subscription_id
            )
                ->lockForUpdate()
                ->firstOrFail();

            // 2. التأكد من وجود لقطة التسعير.
            if (
                $lockedSubscription->package_base_price === null ||
                $lockedSubscription->final_price === null
            ) {
                throw new InvalidArgumentException(
                    'لا توجد لقطة تسعير محفوظة لهذا الاشتراك. يجب معالجة الاشتراكات القديمة قبل إصدار فواتيرها.'
                );
            }

            // 3. التأكد من وجود تواريخ الاشتراك.
            if (
                $lockedSubscription->start_date === null ||
                $lockedSubscription->end_date === null
            ) {
                throw new InvalidArgumentException(
                    'يجب تحديد تاريخ بداية ونهاية الاشتراك قبل إصدار الفاتورة.'
                );
            }

            // 4. البحث عن الفاتورة العامة بعد الحصول على القفل.
            $existingInvoice = Invoice::where(
                'subscription_id',
                $lockedSubscription->subscription_id
            )
                ->whereRaw(
                    "JSON_UNQUOTE(JSON_EXTRACT(calculation_details, '$.invoice_kind')) = ?",
                    ['general']
                )
                ->first();

            // 5. إذا كانت الفاتورة موجودة، نعيدها دون إنشاء أخرى.
            if ($existingInvoice) {
                return $existingInvoice;
            }

            // 6. استخدام لقطة التسعير المحفوظة في الاشتراك.
            $totalAmount = (float) $lockedSubscription->package_base_price;
            $discountAmount = (float) $lockedSubscription->discount_amount;
            $taxAmount = (float) $lockedSubscription->tax_amount;
            $finalPrice = (float) $lockedSubscription->final_price;

            // 7. إنشاء الفاتورة العامة إذا لم تكن موجودة.
            return Invoice::create([
                'subscription_id' => $lockedSubscription->subscription_id,
                'invoice_number' => $this->generateInvoiceNumber(),

                'total_amount' => round($totalAmount, 2),
                'discount_amount' => round($discountAmount, 2),
                'tax_amount' => round($taxAmount, 2),
                'net_amount' => round($finalPrice, 2),
                'penalty_amount' => 0,

                'billing_period_start' => $lockedSubscription->start_date,
                'billing_period_end' => $lockedSubscription->end_date,

                'calculation_details' => [
                    'invoice_kind' => 'general',
                    'coverage_type' => 'full_subscription',
                    'pricing_source' => 'subscription_snapshot',
                    'subscription_start_date' => $lockedSubscription->start_date,
                    'subscription_end_date_exclusive' => $lockedSubscription->end_date,
                    'package_base_price' => round($totalAmount, 2),
                    'discount_amount' => round($discountAmount, 2),
                    'tax_amount' => round($taxAmount, 2),
                    'final_price_before_penalties' => round($finalPrice, 2),
                ],

                'status' => 'pending',
                'issue_date' => now()->toDateString(),
                'due_date' => null,
            ]);
        });
    }

    /**
     * إنشاء فاتورة الدورة الأولى فور إنشاء الاشتراك الدوري.
     *
     * تعتمد على لقطة السعر المحفوظة في الاشتراك،
     * وتمنع إنشاء فاتورة ثانية للدورة الأولى.
     */
    public function createFirstPeriodicInvoice(
        Subscription $subscription,
        Package $package
    ): Invoice {
        return DB::transaction(function () use ($subscription, $package) {

            // قفل الاشتراك قبل فحص الفواتير.
            $lockedSubscription = Subscription::where(
                'subscription_id',
                $subscription->subscription_id
            )
                ->lockForUpdate()
                ->firstOrFail();

            // التأكد من اكتمال لقطة السعر والتواريخ.
            if (
                $lockedSubscription->final_price === null ||
                $lockedSubscription->package_base_price === null ||
                $lockedSubscription->start_date === null ||
                $lockedSubscription->end_date === null
            ) {
                throw new InvalidArgumentException(
                    'بيانات الاشتراك أو لقطة السعر غير مكتملة لإصدار الفاتورة.'
                );
            }

            // حساب دورات الاشتراك ومبالغها.
            $periods = $this->calculateBillingPeriods(
                $lockedSubscription,
                $package
            );

            // لا يمكن إصدار فاتورة إذا لم توجد دورة صالحة.
            if (empty($periods)) {
                throw new InvalidArgumentException(
                    'تعذر تحديد الدورة الأولى للاشتراك.'
                );
            }

            // الدورة الأولى هي الفترة ذات الرقم 1.
            $firstPeriod = $periods[0];

            // منع تكرار فاتورة الدورة الأولى.
            $existingInvoice = Invoice::where(
                'subscription_id',
                $lockedSubscription->subscription_id
            )
                ->whereRaw(
                    "JSON_UNQUOTE(JSON_EXTRACT(
                    calculation_details, '$.invoice_kind'
                )) = ?",
                    ['periodic']
                )
                ->whereRaw(
                    "JSON_UNQUOTE(JSON_EXTRACT(
                    calculation_details, '$.period_number'
                )) = ?",
                    ['1']
                )
                ->first();

            if ($existingInvoice) {
                return $existingInvoice;
            }

            $amount = round((float) $firstPeriod['amount'], 2);

            return Invoice::create([
                'subscription_id' => $lockedSubscription->subscription_id,

                'invoice_number' => $this->generateInvoiceNumber(),

                'total_amount' => $amount,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'net_amount' => $amount,
                'penalty_amount' => 0,

                'billing_period_start' => $firstPeriod['billing_period_start'],

                'billing_period_end' => $firstPeriod['billing_period_end'],

                'calculation_details' => [
                    'invoice_kind' => 'periodic',
                    'coverage_type' => 'billing_period',
                    'pricing_source' => 'subscription_snapshot',

                    'period_number' => 1,
                    'period_days' => $firstPeriod['days'],
                    'period_amount' => $amount,

                    'subscription_start_date' => $lockedSubscription->start_date,

                    'subscription_end_date_exclusive' => $lockedSubscription->end_date,

                    'final_price_before_penalties' => (float) $lockedSubscription->final_price,
                ],

                'status' => 'pending',
                'issue_date' => now()->toDateString(),
                'due_date' => null,
            ]);
        });
    }

    /**
     * إنشاء فواتير الفترات المكتملة فقط.
     *
     * - لا ينشئ فواتير للفترات المستقبلية.
     * - يمنع التكرار باستخدام قفل الاشتراك ورقم الفترة.
     * - يعتمد على السعر النهائي المحفوظ في الاشتراك.
     * - يحتفظ بالفاتورة العامة كسجل تاريخي عند بدء الفوترة الدورية.
     */
    public function createCompletedPeriodicInvoices(
        Subscription $subscription,
        Package $package
    ): array {
        return DB::transaction(function () use ($subscription, $package) {

            // 1. قفل الاشتراك لمنع مسارات الإنشاء المتزامنة
            // التي تستخدم القفل نفسه من معالجة الاشتراك بالتوازي.
            $lockedSubscription = Subscription::where(
                'subscription_id',
                $subscription->subscription_id
            )
                ->lockForUpdate()
                ->firstOrFail();

            // 2. حساب الفترات اعتمادًا على بيانات الاشتراك المحفوظة.
            $periods = $this->calculateBillingPeriods(
                $lockedSubscription,
                $package
            );

            // 3. اختيار الفترات التي انتهت بالفعل فقط.
            // billing_period_end حد غير شامل؛ إذا كان اليوم هو
            // تاريخ النهاية، فقد اكتملت الفترة عند بداية هذا اليوم.
            $today = Carbon::today();

            $completedPeriods = array_values(array_filter(
                $periods,
                function (array $period) use ($today): bool {
                    return Carbon::parse(
                        $period['billing_period_end']
                    )->startOfDay()->lte($today);
                }
            ));

            $invoices = [];

            // 4. معالجة كل فترة مكتملة بصورة مستقلة.
            foreach ($completedPeriods as $period) {

                // البحث عن فاتورة الفترة نفسها لمنع التكرار.
                $existingInvoice = Invoice::where(
                    'subscription_id',
                    $lockedSubscription->subscription_id
                )
                    ->whereRaw(
                        "JSON_UNQUOTE(JSON_EXTRACT(
                        calculation_details, '$.invoice_kind'
                    )) = ?",
                        ['periodic']
                    )
                    ->whereRaw(
                        "JSON_UNQUOTE(JSON_EXTRACT(
                        calculation_details, '$.period_number'
                    )) = ?",
                        [(string) $period['period_number']]
                    )
                    ->first();

                if ($existingInvoice) {
                    $invoices[] = $existingInvoice;

                    continue;
                }

                // 5. قيمة الفترة محسوبة مسبقًا من السعر النهائي
                // المحفوظ في الاشتراك، ولا يعاد حساب سعر الباقة.
                $amount = round((float) $period['amount'], 2);

                $invoice = Invoice::create([
                    'subscription_id' => $lockedSubscription->subscription_id,

                    'invoice_number' => $this->generateInvoiceNumber(),

                    // قيمة الفاتورة تخص هذه الفترة فقط.
                    'total_amount' => $amount,
                    'discount_amount' => 0,
                    'tax_amount' => 0,
                    'net_amount' => $amount,
                    'penalty_amount' => 0,

                    'billing_period_start' => $period['billing_period_start'],

                    'billing_period_end' => $period['billing_period_end'],

                    'calculation_details' => [
                        'invoice_kind' => 'periodic',
                        'coverage_type' => 'billing_period',
                        'pricing_source' => 'subscription_snapshot',

                        'period_number' => $period['period_number'],
                        'period_days' => $period['days'],
                        'period_amount' => $amount,

                        'subscription_start_date' => $lockedSubscription->start_date,

                        'subscription_end_date_exclusive' => $lockedSubscription->end_date,

                        'final_price_before_penalties' => (float) $lockedSubscription->final_price,
                    ],

                    'status' => 'pending',
                    'issue_date' => now()->toDateString(),
                    'due_date' => null,
                ]);

                $invoices[] = $invoice;
            }

            // 6. عند وجود فترة مكتملة، نعلّم الفاتورة العامة
            // بأنها لم تعد مطالبة مالية مستقلة.
            // لا نحذفها ولا نغيّر حالة الفاتورة غير المدعومة
            // في قاعدة البيانات؛ بل نحتفظ بها كسجل تاريخي.
            if (! empty($completedPeriods)) {
                $generalInvoice = $this->findGeneralInvoice(
                    $lockedSubscription
                );

                if ($generalInvoice) {
                    $details = $generalInvoice->calculation_details ?? [];

                    if (! is_array($details)) {
                        $details = [];
                    }

                    if (
                        empty(
                            $details['billing_replaced_by_periodic_billing']
                        )
                    ) {
                        $details['billing_replaced_by_periodic_billing'] = true;
                        $details['periodic_billing_started_at'] =
                            now()->toDateString();

                        $generalInvoice->calculation_details = $details;
                        $generalInvoice->save();
                    }
                }
            }

            return $invoices;
        });
    }

    /**
     * إنشاء فاتورة غرامات واحدة لكل اشتراك وتحديثها
     * ما دامت غير مسددة بالكامل.
     *
     * لا تُنشأ فاتورة قبل استحقاق غرامة فعلية.
     * عند تسوية الفاتورة، يتوقف تراكم الغرامة نهائيًا.
     */
   
/**
 * إنشاء فاتورة غرامة واحدة لكل اشتراك أو تحديثها.
 *
 * - لا تُنشأ فاتورة قبل استحقاق الغرامة.
 * - المدفوعات الناجحة فقط تُحتسب.
 * - لا يُخفض إجمالي الغرامة المسجل سابقًا.
 * - عند اكتمال التسوية، تُثبت الفاتورة نهائيًا.
 */
public function syncPenaltyInvoice(
    Subscription $subscription,
    ?Carbon $asOf = null
): ?Invoice {
    return DB::transaction(function () use ($subscription, $asOf) {

        // قفل الاشتراك لمنع تنفيذ المزامنة بالتوازي.
        $lockedSubscription = Subscription::query()
            ->where('subscription_id', $subscription->subscription_id)
            ->lockForUpdate()
            ->firstOrFail();

        $lockedSubscription->load('package');

        if (!$lockedSubscription->package) {
            throw new InvalidArgumentException(
                'لا توجد باقة مرتبطة بالاشتراك.'
            );
        }

        // البحث عن فاتورة الغرامة الموجودة لهذا الاشتراك.
        $invoice = Invoice::query()
            ->where(
                'subscription_id',
                $lockedSubscription->subscription_id
            )
            ->whereRaw(
                "JSON_UNQUOTE(JSON_EXTRACT(
                    calculation_details, '$.invoice_kind'
                )) = ?",
                ['penalty']
            )
            ->lockForUpdate()
            ->first();

        // لا نعيد فتح فاتورة سبق تثبيتها بعد التسوية.
        if (
            $invoice &&
            data_get(
                $invoice->calculation_details,
                'penalty_frozen'
            ) === true
        ) {
            return $invoice;
        }

        // المدفوعات الناجحة فقط تُعد أموالًا محصلة.
        $paidAmount = $invoice
            ? round(
                (float) $invoice->payments()
                    ->where('status', 'success')
                    ->sum('amount'),
                2
            )
            : 0.0;

        // إذا كانت الفاتورة مسددة بالفعل وفق حالتها،
        // نثبتها ولا نعيد احتساب غرامات جديدة.
      // إذا غطّت المدفوعات الناجحة كامل قيمة الفاتورة الحالية،
// نثبت الفاتورة نهائيًا ولا نضيف غرامات جديدة بعدها.
if (
    $invoice &&
    round((float) $invoice->net_amount, 2) > 0 &&
    $paidAmount >= round((float) $invoice->net_amount, 2)
) {
    $details = $invoice->calculation_details ?? [];

    if (!is_array($details)) {
        $details = [];
    }

    $details['penalty_frozen'] = true;
    $details['penalty_frozen_at'] ??= now()->toDateTimeString();

    $invoice->status = 'paid';
    $invoice->calculation_details = $details;
    $invoice->save();

    return $invoice;
}

      

        // حساب الغرامة حتى التاريخ المحدد أو تاريخ اليوم.
        $penaltyService = app(PenaltyService::class);

        $calculation = $penaltyService->calculateAccruedPenalty(
            $lockedSubscription,
            $asOf
        );

        $calculatedPenalty = round(
            (float) ($calculation['total_penalty'] ?? 0),
            2
        );

        $previousPenalty = $invoice
            ? round((float) $invoice->penalty_amount, 2)
            : 0.0;

        // لا نخفض الغرامة المسجلة، ولا نجعل إجمالي الفاتورة
        // أقل من المدفوعات الناجحة المسجلة.
        $totalPenalty = max(
            $calculatedPenalty,
            $previousPenalty,
            $paidAmount
        );

        // لا ننشئ فاتورة بمبلغ صفري.
        if (!$invoice && $totalPenalty <= 0) {
            return null;
        }

        // إذا لم توجد غرامة جديدة، نحتفظ بالفاتورة السابقة.
        if ($invoice && $totalPenalty <= 0) {
            return $invoice;
        }

        $details = $invoice?->calculation_details ?? [];

        if (!is_array($details)) {
            $details = [];
        }

        $details['invoice_kind'] = 'penalty';
        $details['coverage_type'] = 'subscription_penalty';
        $details['pricing_source'] = 'subscription_snapshot';
        $details['calculation'] = $calculation;
        $details['last_calculated_at'] =
            now()->toDateTimeString();

        // إنشاء الفاتورة لأول مرة فقط.
        if (!$invoice) {
            $invoice = Invoice::create([
                'subscription_id' =>
                    $lockedSubscription->subscription_id,

                'invoice_number' => $this->generateInvoiceNumber(),

                'total_amount' => $totalPenalty,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'net_amount' => $totalPenalty,
                'penalty_amount' => $totalPenalty,

                'calculation_details' => array_merge($details, [
                    'penalty_frozen' => false,
                ]),

                'status' => 'pending',
                'issue_date' => now()->toDateString(),
                'due_date' => null,
            ]);

            return $invoice;
        }

        // تحديث الفاتورة نفسها دون إصدار فاتورة ثانية.
        $invoice->total_amount = $totalPenalty;
        $invoice->discount_amount = 0;
        $invoice->tax_amount = 0;
        $invoice->net_amount = $totalPenalty;
        $invoice->penalty_amount = $totalPenalty;

        // تحديد الحالة وفق المدفوعات الناجحة.
        if ($paidAmount >= $totalPenalty) {
            $details['penalty_frozen'] = true;
            $details['penalty_frozen_at'] =
                now()->toDateTimeString();

            $invoice->status = 'paid';
        } elseif ($paidAmount > 0) {
            $details['penalty_frozen'] = false;
            $invoice->status = 'partial';
        } else {
            $details['penalty_frozen'] = false;
            $invoice->status = 'pending';
        }

        $invoice->calculation_details = $details;
        $invoice->save();

        return $invoice;
    });
}



    private function calculateDiscount(
        ?string $type,
        float $value,
        float $basePrice
    ): float {
        if ($value < 0) {
            throw new InvalidArgumentException(
                'قيمة الخصم لا يمكن أن تكون سالبة.'
            );
        }

        if ($value == 0 || ! $type) {
            return 0;
        }

        if ($type === 'fixed') {
            return round(min($value, $basePrice), 2);
        }

        if ($type === 'percent') {
            if ($value > 100) {
                throw new InvalidArgumentException(
                    'نسبة الخصم لا يمكن أن تتجاوز 100%.'
                );
            }

            return round($basePrice * $value / 100, 2);
        }

        throw new InvalidArgumentException('نوع الخصم غير صحيح.');
    }

    private function calculateTax(
        ?string $type,
        float $value,
        float $amountAfterDiscount
    ): float {
        if ($value < 0) {
            throw new InvalidArgumentException(
                'قيمة الضريبة لا يمكن أن تكون سالبة.'
            );
        }

        if ($value == 0 || ! $type) {
            return 0;
        }

        if ($type === 'fixed') {
            return round($value, 2);
        }

        if ($type === 'percent') {
            if ($value > 100) {
                throw new InvalidArgumentException(
                    'نسبة الضريبة لا يمكن أن تتجاوز 100%.'
                );
            }

            return round($amountAfterDiscount * $value / 100, 2);
        }

        throw new InvalidArgumentException('نوع الضريبة غير صحيح.');
    }

    private function generateInvoiceNumber(): string
    {
        do {
            $number = 'INV-'.now()->format('Ymd').'-'
                .Str::upper(Str::random(8));
        } while (
            Invoice::where('invoice_number', $number)->exists()
        );

        return $number;
    }

    /**
     * تقسيم السعر الإجمالي للاشتراك إلى فترات فوترة.
     *
     * end_date حد غير شامل.
     * عند الإلغاء، يكون effectiveEndDate هو تاريخ الإلغاء
     * ويبدأ الإيقاف المالي من بداية ذلك اليوم.
     *
     * لا تنشئ هذه الدالة فواتير؛ بل تحسب الفترات والمبالغ فقط.
     */
    public function calculateBillingPeriods(
        Subscription $subscription,
        Package $package,
        ?string $effectiveEndDate = null
    ): array {
        if (
            $subscription->start_date === null ||
            $subscription->end_date === null ||
            $subscription->final_price === null
        ) {
            throw new InvalidArgumentException(
                'بيانات الاشتراك أو لقطة السعر غير مكتملة.'
            );
        }

        $start = Carbon::parse($subscription->start_date)->startOfDay();
        $subscriptionEnd = Carbon::parse($subscription->end_date)->startOfDay();

        // تاريخ النهاية الفعلي: نهاية الاشتراك أو تاريخ الإلغاء، أيهما أسبق.
        $end = $effectiveEndDate !== null
            ? Carbon::parse($effectiveEndDate)->startOfDay()
            : $subscriptionEnd->copy();

        if ($end->gt($subscriptionEnd)) {
            $end = $subscriptionEnd->copy();
        }

        $totalDays = $start->diffInDays($subscriptionEnd);

        if ($totalDays <= 0 || $end->lte($start)) {
            return [];
        }

        $usedDays = $start->diffInDays($end);

        $finalPrice = round((float) $subscription->final_price, 2);

        $earnedTotal = round(
            $finalPrice * $usedDays / $totalDays,
            2
        );

        $cycle = $package->billing_cycle;

        if (! in_array($cycle, ['weekly', 'monthly', 'yearly'], true)) {
            throw new InvalidArgumentException(
                'دورة الفوترة غير مدعومة في قاعدة البيانات الحالية.'
            );
        }

        $periods = [];
        $periodStart = $start->copy();
        $index = 1;
        $allocatedTotal = 0;

        while ($periodStart->lt($end)) {
            // نحسب حدود الدورة انطلاقًا من تاريخ البداية الأصلي
            // حتى لا يتغير موعد الفوترة بسبب قصر أحد الأشهر.
            $nextStart = match ($cycle) {
                'weekly' => $start->copy()->addWeeks($index),
                'monthly' => $start->copy()->addMonthsNoOverflow($index),
                'yearly' => $start->copy()->addYearsNoOverflow($index),
            };

            $periodEnd = $nextStart->lt($end)
                ? $nextStart->copy()
                : $end->copy();

            $days = $periodStart->diffInDays($periodEnd);

            if ($periodEnd->lte($periodStart) || $days <= 0) {
                throw new InvalidArgumentException(
                    'تعذر حساب حدود دورة الفوترة.'
                );
            }

            // نوزع السعر الإجمالي على أيام مدة الاشتراك الأصلية.
            // الدورة الأخيرة تستوعب فرق التقريب.
            $isLastPeriod = $periodEnd->equalTo($end);

            $amount = $isLastPeriod
                ? round($earnedTotal - $allocatedTotal, 2)
                : round($finalPrice * $days / $totalDays, 2);

            $periods[] = [
                'period_number' => $index,
                'billing_period_start' => $periodStart->toDateString(),
                'billing_period_end' => $periodEnd->toDateString(),
                'days' => $days,
                'amount' => $amount,
            ];

            $allocatedTotal = round($allocatedTotal + $amount, 2);
            $periodStart = $periodEnd->copy();
            $index++;
        }

        return $periods;
    }

    /**
     * حساب لقطة التسعير من بيانات الباقة الحالية.
     * تحفظ النتيجة داخل الاشتراك عند إنشائه.
     */
    public function createPricingSnapshot(Package $package): array
    {
        $basePrice = round((float) $package->base_price, 2);

        $discountType = $package->discount_type;
        $discountValue = max(0, (float) $package->discount_value);

        $discountAmount = match ($discountType) {
            'percent' => $basePrice * $discountValue / 100,
            'fixed' => $discountValue,
            default => 0,
        };

        // لا يمكن أن يتجاوز الخصم السعر الأساسي.
        $discountAmount = round(
            min($basePrice, max(0, $discountAmount)),
            2
        );

        $taxType = $package->tax_type;
        $taxValue = max(0, (float) $package->tax_value);

        // حساب الضريبة على السعر بعد الخصم.
        $taxableAmount = round($basePrice - $discountAmount, 2);

        $taxAmount = match ($taxType) {
            'percent' => $taxableAmount * $taxValue / 100,
            'fixed' => $taxValue,
            default => 0,
        };

        $taxAmount = round(max(0, $taxAmount), 2);

        $finalPrice = round(
            $taxableAmount + $taxAmount,
            2
        );

        return [
            'package_base_price' => $basePrice,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'discount_amount' => $discountAmount,
            'tax_type' => $taxType,
            'tax_value' => $taxValue,
            'tax_amount' => $taxAmount,
            'final_price' => $finalPrice,
        ];
    }
}
