<div class="row">
    {{-- ===========================
        معلومات الاشتراك
    ============================ --}}
    <div class="col-lg-7 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <i class="fas fa-id-card text-primary"></i>
                <span class="font-weight-bold">
                    معلومات الاشتراك
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $data = [
                            'المؤسسة' => $subscription->organization->name ?? '-',

                            'الباقة' => $subscription->package->name ?? '-',

                            'نوع الاشتراك' => $subscription->subscription_type_label,

                            'حالة الاشتراك' => $subscription->status_label,

                            'تاريخ البداية' => $subscription->start_date?->format('Y-m-d'),

                            'تاريخ النهاية' => $subscription->end_date?->format('Y-m-d'),

                            'مدة التجربة' => $subscription->trial_period_days . ' يوم',

                            'التجديد التلقائي' => $subscription->auto_renew_label,
                        ];
                    @endphp
                    @foreach ($data as $label => $value)
                        <div class="col-md-6 mb-4">
                            <div class="small text-muted">
                                {{ $label }}
                            </div>
                            <div class="font-weight-bold">
                                {{ $value ?? '-' }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr>
                <div>
                    <div class="small text-muted mb-2">
                        ملاحظات الاشتراك
                    </div>
                    <p class="mb-0">
                        {{ $subscription->notes ?? 'لا توجد ملاحظات' }}

                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===========================
        ملخص الاشتراك
    ============================ --}}
    <div class="col-lg-5 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <i class="fas fa-chart-line text-success"></i>
                <span class="font-weight-bold">
                    ملخص الاشتراك
                </span>
            </div>
            <div class="card-body">
                @php
                    $summary = [
                        'الباقة الحالية' => [
                            'icon' => 'fa-crown',
                            'value' => $subscription->package->name ?? '-',
                        ],
                        'السعر' => [
                            'icon' => 'fa-money-bill',
                            'value' => number_format($subscription->package->base_price ?? 0, 2),
                        ],
                        'مدة الاشتراك' => [
                            'icon' => 'fa-calendar',
                            'value' => ($subscription->package->duration_days ?? 0) . ' يوم',
                        ],
                        'التجديد التلقائي' => [
                            'icon' => 'fa-sync',
                            'value' => $subscription->auto_renew_label,
                        ],
                    ];
                @endphp
                @foreach ($summary as $label => $item)
                    <div class="d-flex align-items-center mb-4">
                        <div class="mr-3 text-primary">
                            <i class="fas {{ $item['icon'] }} fa-lg"></i>
                        </div>
                        <div>
                            <div class="small text-muted">
                                {{ $label }}
                            </div>
                            <strong>
                                {{ $item['value'] }}
                            </strong>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
