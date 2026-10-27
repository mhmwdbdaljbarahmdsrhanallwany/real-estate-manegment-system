<?php

namespace App\Console\Commands;

use App\Models\Admin\Subscriptions\Subscription;
use App\Services\Actions\ActionService;
use App\Services\Subscriptions\SubscriptionStatusService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DiscoverSubscriptionEvents extends Command
{
    protected $signature = 'subscriptions:discover-events';

    protected $description = 'Discover subscription timeline events and record them once';

    public function handle(
        SubscriptionStatusService $statusService,
        ActionService $actionService
    ): int {
       

        $recorded = 0;
        $skipped = 0;

        Subscription::query()
            ->with(['package', 'organization'])
            ->where('subscription_type', 'paid')
            ->whereIn('status', ['active', 'expired'])
            ->chunkById(
                100,
                function ($subscriptions) use (
                    $statusService,
                    $actionService,
                    &$recorded,
                    &$skipped
                ) {
                   
foreach ($subscriptions as $subscription) {
    $today = now()->startOfDay();

    $endDate = $statusService->subscriptionEndDate($subscription);
    $penaltyDate = $statusService->penaltyDate($subscription);
    $suspendDate = $statusService->suspendDate($subscription);

    if (!$endDate || !$penaltyDate || !$suspendDate) {
        $skipped++;
        continue;
    }

    $milestones = [
        [
            'date' => $endDate,
            'code' => 'subscription_entered_grace',
            'title' => 'دخول الاشتراك فترة السماح',
            'state' => 'grace',
        ],
        [
            'date' => $penaltyDate,
            'code' => 'subscription_entered_penalty',
            'title' => 'بدء فترة الغرامة',
            'state' => 'penalty',
        ],
        [
            'date' => $suspendDate,
            'code' => 'subscription_reached_suspension',
            'title' => 'بلوغ موعد إيقاف الاشتراك',
            'state' => 'suspension_due',
        ],
    ];

    foreach ($milestones as $milestone) {
        // لا نسجل حدثًا لم يحِن موعده بعد.
        if ($today->lt($milestone['date'])) {
            continue;
        }

        try {
            $action = $actionService->logOnceForSubscription(
                $milestone['code'],
                (int) $subscription->subscription_id,
                [
                    'organization_id' => $subscription->organization_id,
                    'title' => $milestone['title'],
                    'description' => $milestone['title'],
                    'metadata' => [
                        'state' => $milestone['state'],
                        'milestone_date' => $milestone['date']->toDateString(),
                        'detected_at' => now()->toDateTimeString(),
                    ],
                ]
            );

            if ($action) {
                $recorded++;
            } else {
                $skipped++;
            }
        } catch (ModelNotFoundException $exception) {
            // نوع الإجراء غير موجود أو غير نشط.
            $skipped++;
        }
    }
}

                },
                'subscription_id'
            );

        $this->info("تم تسجيل أحداث جديدة: {$recorded}");
        $this->line("تم تجاوز أحداث أو أنواع إجراءات غير نشطة: {$skipped}");

        return self::SUCCESS;
    }
}
