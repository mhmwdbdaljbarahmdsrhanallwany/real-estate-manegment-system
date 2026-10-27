
@extends('admin.layout.master')

@section('title')
    تعديل الاشتراك التجريبي
@endsection

@section('contect')
    @include('admin.incloude.agencynavepare')

    @php
        $subscriptionId = data_get(
            $dataSubscription,
            'subscription_id',
            data_get($dataSubscription, 'id')
        );

        $subscriptionType = old(
            'subscription_type',
            data_get($dataSubscription, 'subscription_type')
        );

        $trialPeriodDays = old(
            'trial_period_days',
            data_get($dataSubscription, 'trial_period_days', 0)
        );
    @endphp

    <div class="card shadow-lg border-0 rounded-3">

        {{-- عنوان الصفحة --}}
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold text-primary">
                <i class="fas fa-edit me-2"></i>
                تعديل الاشتراك التجريبي
            </h4>

            <a href="{{ route('admin.subscriptions.index') }}"
               class="btn btn-light btn-sm">
                <i class="fas fa-arrow-right"></i>
                العودة للاشتراكات
            </a>
        </div>

        {{-- نموذج التعديل --}}
        <form
            action="{{ route('admin.subscriptions.update', $subscriptionId) }}"
            method="POST"
            id="editSubscriptionForm"
        >
            @csrf
            @method('PUT')

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>تعذر حفظ التعديلات:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    يمكنك تعديل مدة الفترة التجريبية فقط.
                    بقية بيانات الاشتراك معروضة للقراءة ولا تتغير من هذه الصفحة.
                </div>

                <div class="row">

                    {{-- ===========================
                         بيانات الاشتراك للعرض فقط
                    ============================ --}}
                    <div class="col-lg-8">

                        <div class="card shadow-sm border-0 mb-4">

                            <div class="card-header bg-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-signature text-primary"></i>
                                    بيانات الاشتراك
                                </h5>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    <x-constructor-form
                                        :rows="$dataSubscription"
                                        :selectes="[
                                            'organization_id' => [
                                                'label' => 'المؤسسة',
                                                'icon' => 'fas fa-building',
                                                'type' => 'relation',
                                                'name' => 'organization_id',
                                                'option' => $organizations,
                                                'value' => 'organization_id',
                                                'display' => 'name',
                                                'disabled' => true,
                                            ],
                                            'package_id' => [
                                                'label' => 'الباقة',
                                                'icon' => 'fas fa-box',
                                                'type' => 'relation',
                                                'name' => 'package_id',
                                                'id' => 'packageSelect',
                                                'option' => $packages,
                                                'value' => 'package_id',
                                                'display' => 'name',
                                                'meta' => [
                                                    'duration_days',
                                                    'grace_period_days',
                                                    'suspend_after_days',
                                                ],
                                                'disabled' => true,
                                            ],
                                            'subscription_type' => [
                                                'label' => 'نوع الاشتراك',
                                                'name' => 'subscription_type',
                                                'id' => 'subscriptionType',
                                                'icon' => 'fas fa-layer-group',
                                                'option' => [
                                                    'trial' => 'تجريبي',
                                                    'paid' => 'مدفوع',
                                                ],
                                                'disabled' => true,
                                            ],
                                            'status' => [
                                                'label' => 'الحالة',
                                                'name' => 'status',
                                                'icon' => 'fas fa-toggle-on',
                                                'option' => [
                                                    'pending' => 'قيد الانتظار',
                                                    'active' => 'نشط',
                                                ],
                                                'disabled' => true,
                                            ],
                                            'auto_renew' => [
                                                'label' => 'التجديد التلقائي',
                                                'name' => 'auto_renew',
                                                'icon' => 'fas fa-sync',
                                                'option' => [
                                                    1 => 'نعم',
                                                    0 => 'لا',
                                                ],
                                                'disabled' => true,
                                            ],
                                        ]"
                                    />

                                </div>
                            </div>
                        </div>

                        {{-- ===========================
                             الفترة التجريبية
                        ============================ --}}
                        <div class="card shadow-sm border-0 mb-4"
                             id="trialCard">

                            <div class="card-header bg-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-hourglass-half text-warning"></i>
                                    إعدادات الفترة التجريبية
                                </h5>
                            </div>

                            <div class="card-body">

                                <div class="alert alert-light border">
                                    عدّل عدد أيام الفترة التجريبية فقط.
                                    سيعرض القسم الجانبي تواريخ تقديرية للمراجعة.
                                    لا تعتمد على المعاينة لتحديث تواريخ الاشتراك المحفوظة.
                                </div>

                                <div class="row">

                                    <x-constructor-form
                                        :rows="$dataSubscription"
                                        :types="[
                                            'trial_period_days' => [
                                                'label' => 'مدة الفترة التجريبية بالأيام',
                                                'type' => 'number',
                                                'name' => 'trial_period_days',
                                                'id' => 'trialDays',
                                                'icon' => 'fas fa-clock',
                                                'min' => 0,
                                                'required' => true,
                                            ],
                                        ]"
                                    />

                                </div>
                            </div>
                        </div>

                        {{-- ===========================
                             الملاحظات للعرض فقط
                        ============================ --}}
                        <div class="card shadow-sm border-0">

                            <div class="card-header bg-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-sticky-note text-info"></i>
                                    الملاحظات الحالية
                                </h5>
                            </div>

                            <div class="card-body">

                                <x-constructor-form
                                    :rows="$dataSubscription"
                                    :types="[
                                        'notes' => [
                                            'label' => 'الملاحظات',
                                            'name' => 'notes',
                                            'icon' => 'fas fa-pen',
                                            'type' => 'textarea',
                                            'readonly' => true,
                                        ],
                                    ]"
                                />

                            </div>
                        </div>

                    </div>

                    {{-- ===========================
                         معاينة دورة حياة الاشتراك
                    ============================ --}}
                    <div class="col-lg-4">

                        <div class="card shadow-sm border-0 sticky-top">

                            <div class="card-header bg-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-eye text-success"></i>
                                    معاينة الاشتراك
                                </h5>
                            </div>

                            <div class="card-body">

                                <div class="subscription-journey">

                                    <h5 class="font-weight-bold mb-4 text-center">
                                        <i class="fas fa-route text-primary"></i>
                                        رحلة دورة حياة الاشتراك
                                    </h5>

                                    {{-- إنشاء الاشتراك --}}
                                    <div class="journey-step active">
                                        <div class="step-icon bg-primary">
                                            <i class="fas fa-file-signature text-white"></i>
                                        </div>

                                        <div class="step-card">
                                            <h6>إنشاء الاشتراك</h6>
                                            <small class="text-muted">تاريخ الإنشاء</small>

                                            <div class="font-weight-bold">
                                                {{ data_get($dataSubscription, 'created_at')
                                                    ? \Illuminate\Support\Carbon::parse(
                                                        data_get($dataSubscription, 'created_at')
                                                    )->format('Y-m-d')
                                                    : '--' }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- الفترة التجريبية --}}
                                    <div class="journey-step" id="trialStep">
                                        <div class="step-icon bg-warning">
                                            <i class="fas fa-flask text-white"></i>
                                        </div>

                                        <div class="step-card">
                                            <h6>الفترة التجريبية</h6>

                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-muted">المدة</small>
                                                    <div id="trialDaysPreview">
                                                        {{ $trialPeriodDays }} يوم
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <small class="text-muted">النهاية التقديرية</small>
                                                    <div id="trialEndPreview">--</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- بداية الاشتراك --}}
                                    <div class="journey-step">
                                        <div class="step-icon bg-success">
                                            <i class="fas fa-check text-white"></i>
                                        </div>

                                        <div class="step-card">
                                            <h6>بداية الاشتراك</h6>
                                            <div id="startPreview">
                                                {{ data_get($dataSubscription, 'start_date')
                                                    ? \Illuminate\Support\Carbon::parse(
                                                        data_get($dataSubscription, 'start_date')
                                                    )->format('Y-m-d')
                                                    : '--' }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- نهاية الاشتراك --}}
                                    <div class="journey-step">
                                        <div class="step-icon bg-danger">
                                            <i class="fas fa-flag-checkered text-white"></i>
                                        </div>

                                        <div class="step-card">
                                            <h6>نهاية الاشتراك</h6>

                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-muted">التاريخ الحالي</small>
                                                    <div id="endPreview">
                                                        {{ data_get($dataSubscription, 'end_date')
                                                            ? \Illuminate\Support\Carbon::parse(
                                                                data_get($dataSubscription, 'end_date')
                                                            )->format('Y-m-d')
                                                            : '--' }}
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <small class="text-muted">مدة الباقة</small>
                                                    <div id="durationPreview">--</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- فترة السماح --}}
                                    <div class="journey-step">
                                        <div class="step-icon bg-info">
                                            <i class="fas fa-hourglass-half text-white"></i>
                                        </div>

                                        <div class="step-card">
                                            <h6>فترة السماح</h6>
                                            <div>تُحسب وفق إعدادات الباقة</div>
                                        </div>
                                    </div>

                                    {{-- الغرامة --}}
                                    <div class="journey-step">
                                        <div class="step-icon bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>

                                        <div class="step-card">
                                            <h6>تطبيق الغرامة</h6>
                                            <div>وفق سياسة الاشتراك وإعدادات النظام</div>
                                        </div>
                                    </div>

                                    {{-- الإيقاف --}}
                                    <div class="journey-step">
                                        <div class="step-icon bg-dark">
                                            <i class="fas fa-lock text-white"></i>
                                        </div>

                                        <div class="step-card">
                                            <h6>إيقاف الاشتراك</h6>
                                            <div>وفق قواعد انتهاء الاشتراك</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- أزرار النموذج --}}
            <div class="card shadow-sm border-0 mt-2">
                <div class="card-body text-right">

                    <button type="submit" class="btn btn-primary" id="saveSubscriptionBtn">
                        <i class="fas fa-save"></i>
                        حفظ التعديلات
                    </button>

                    <a href="{{ route('admin.subscriptions.index') }}"
                       class="btn btn-light">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </a>

                </div>
            </div>

        </form>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const packageSelect = document.getElementById('packageSelect');
            const trialDaysInput = document.getElementById('trialDays');

            const trialDaysPreview = document.getElementById('trialDaysPreview');
            const trialEndPreview = document.getElementById('trialEndPreview');
            const startPreview = document.getElementById('startPreview');
            const endPreview = document.getElementById('endPreview');
            const durationPreview = document.getElementById('durationPreview');

            // التواريخ المحفوظة في قاعدة البيانات، لا نغيّرها من المعاينة.
            const savedStartDate = @json(
                data_get($dataSubscription, 'start_date')
                    ? \Illuminate\Support\Carbon::parse(
                        data_get($dataSubscription, 'start_date')
                    )->format('Y-m-d')
                    : null
            );

            const savedEndDate = @json(
                data_get($dataSubscription, 'end_date')
                    ? \Illuminate\Support\Carbon::parse(
                        data_get($dataSubscription, 'end_date')
                    )->format('Y-m-d')
                    : null
            );

            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                return `${year}-${month}-${day}`;
            }

            function parseLocalDate(value) {
                if (!value) {
                    return null;
                }

                const parts = value.split('-').map(Number);

                if (parts.length !== 3 || parts.some(Number.isNaN)) {
                    return null;
                }

                return new Date(parts[0], parts[1] - 1, parts[2]);
            }

            function calculatePreview() {
                const trialDays = Math.max(0, Number(trialDaysInput?.value) || 0);

                let duration = 0;

                if (packageSelect && packageSelect.selectedIndex >= 0) {
                    const option = packageSelect.options[packageSelect.selectedIndex];
                    duration = Number(option?.dataset.duration_days) || 0;
                }

                if (trialDaysPreview) {
                    trialDaysPreview.textContent = trialDays + ' يوم';
                }

                if (durationPreview) {
                    durationPreview.textContent = duration + ' يوم';
                }

                // المعاينة تقديرية فقط، ولا تُرسل هذه التواريخ إلى الخادم.
                const baseDate = parseLocalDate(savedStartDate) || new Date();
                const estimatedTrialEnd = new Date(baseDate);

                estimatedTrialEnd.setDate(estimatedTrialEnd.getDate() + trialDays);

                if (trialEndPreview) {
                    trialEndPreview.textContent = trialDays > 0
                        ? formatDate(estimatedTrialEnd)
                        : '--';
                }

                if (startPreview) {
                    startPreview.textContent = savedStartDate || '--';
                }

                if (endPreview) {
                    endPreview.textContent = savedEndDate || '--';
                }
            }

            if (trialDaysInput) {
                trialDaysInput.addEventListener('input', calculatePreview);
            }

            calculatePreview();
        });
    </script>
@endsection
