@extends('admin.layout.master')

@section('title')
    اضافة الاشتراك
@endsection

@section('contect')
    @include('admin.incloude.agencynavepare')

    <div class="card shadow-lg border-0 rounded-3">
        @if ($errors->any())
            @section('script')
                <script>
                    toastr.error("{{ $errors->first() }}");
                </script>
            @endsection
        @endif


        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-semibold text-primary">
                <i class="fas fa-box-open me-2"></i> اضافة اشتراك جديد
            </h4>
        </div>

        <div class="card-body">
            <form class="row g-4" method="POST" action="{{ route('admin.agencysubscription.store') }}">
                @csrf

                <!-- المؤسسة -->



                <x-constructor-form :selectes="[
                    'AgencyID' => [
                        'label' => 'المؤسسة',
                        'icon' => 'fas fa-building',
                        'type' => 'relation',
                        'name' => 'AgencyID',
                        'option' => $agency,
                        'value' => 'agency_id',
                        'display' => 'agency_name',
                    ],
                    'PlanID' => [
                        'label' => 'الباقة',
                        'icon' => 'fas fa-building',
                        'type' => 'relation',
                        'id' => 'planSelect',
                        'name' => 'PlanID',
                        'option' => $subscription,
                        'value' => 'plan_id',
                        'display' => 'plan_name',
                        'meta' => [
                            'duration',
                            'subscription_type_label',
                            'tax_rate',
                            'plan_price',
                            'discount_rate',
                            'trial_period_value',
                            'trial_period_unit',
                            'late_fee',
                            'grace_period',
                        ],
                    ],
                    'trial_period_value' => [
                        'label' => 'الفترة التجريبية',
                        'icon' => 'fas fa-clock',
                        'type' => 'number',
                        'type_name' => 'TrialPeriod',
                        'type_id' => 'trialPeriod',
                        'select_name' => 'TrialUnit',
                        'select_id' => 'trialUnit',
                        'option' => [
                            'days' => 'ايام ',
                            'weeks' => 'اسابيع',
                            'months' => 'اشهر',
                        ],
                    ],
                ]" />

                <x-constructor-form :types="[
                    'LateFee' => [
                        'label' => 'غرامة التاخير',
                        'type' => 'number',
                        'name' => 'LateFee',
                        'id' => 'lateFee',
                        'icon' => 'fas fa-exclamation-triangle ',
                    ],
                    'GracePeriod' => [
                        'label' => ' فترة السماح',
                        'type' => 'number',
                        'name' => 'GracePeriod',
                        'id' => 'gracePeriod',
                        'icon' => 'fas fa-hourglass-half',
                    ],
                    'SubscriptionType' => [
                        'label' => 'نوع الاشتراك',
                        'name' => 'SubscriptionType',
                        'id' => 'subscriptionDuration',
                        'icon' => 'fas fa-clock',
                    ],
                    'StartDate' => [
                        'label' => 'تاريخ البداية',
                        'type' => 'date',
                        'name' => 'StartDate',
                        'id' => 'startDate',
                        'icon' => 'fas fa-calendar-day',
                    ],
                    'EndDate' => [
                        'label' => 'تاريخ الانتهاء',
                        'type' => 'date',
                        'name' => 'EndDate',
                        'id' => 'endDate',
                        'icon' => 'fas fa-calendar-times',
                    ],
                    'RenewalDate' => [
                        'label' => 'تاريخ التجديد',
                        'type' => 'date',
                        'name' => 'RenewalDate',
                        'id' => 'renewalDate',
                        'icon' => 'fas fa-sync',
                    ],
                    'PlanPrice' => [
                        'label' => ' السعر ',
                        'type' => 'number',
                        'name' => 'PlanPrice',
                        'id' => 'planPrice',
                        'icon' => 'fas fa-dollar-sign',
                    ],
                    'TaxRate' => [
                        'label' => 'نسبة الضريبة',
                        'type' => 'number',
                        'name' => 'TaxRate',
                        'id' => 'taxRate',
                        'step' => '0.01',
                        'icon' => 'fas fa-percentage',
                    ],
                   
                ]" />
                <x-constructor-form :selectes="[
                    'LateFeePaid' => [
                        'label' => ' هل تم دفع الغرامة',
                        'icon' => 'fas fa-check-circle',
                        'name' => 'LateFeePaid',
                        'option' => [
                            '0' => 'لا ',
                            '1' => 'نعم',
                        ],
                    ],
                ]" />
                <x-constructor-form :types="[
                    'LateFeePaymentDate' => [
                        'label' => 'تاريخ دفع الغرامة',
                        'type' => 'date',
                        'name' => 'LateFeePaymentDate',
                        'icon' => 'fas fa-calendar-check',
                    ],
                ]" />
                <x-constructor-form :selectes="[
                    'WarningSent' => [
                        'label' => ' هل تم ارسال تحذير',
                        'icon' => 'fas fa-bell',
                        'name' => 'WarningSent',
                        'option' => [
                            '0' => 'لا ',
                            '1' => 'نعم',
                        ],
                    ],
                ]" />
                <x-constructor-form :types="[
                    'WarningCount' => [
                        'label' => '  عدد التحذيرات',
                        'type' => 'number',
                        'name' => 'WarningCount',
                        'icon' => 'fas fa-bell',
                    ],
                ]" />



                <x-constructor-form :types="[
                    'LastWarningDate' => [
                        'label' => 'تاريخ اخر تحذير',
                        'type' => 'date',
                        'name' => 'LastWarningDate',
                        'icon' => 'fas fa-calendar-day',
                    ],
                ]" />


                <x-constructor-form :types="[
                    'DiscountRate' => [
                        'label' => ' نسبة الخصم',
                        'type' => 'number',
                        'name' => 'DiscountRate',
                        'id' => 'discountRate',
                        'step' => '0.01',
                        'show' => 'readonly',
                        'icon' => 'fas fa-percent ',
                    ],
                    'DiscountAmount' => [
                        'label' => '  قيمة الخصم',
                        'type' => 'number',
                        'name' => 'DiscountAmount',
                        'id' => 'discountAmount',
                        'show' => 'readonly',
                        'icon' => 'fas fa-money-bill-wave',
                    ],
                    'DiscountReason' => [
                        'label' => 'سبب الخصم',
                        'name' => 'DiscountReason',
                        'icon' => 'fas fa-comment',
                    ],
                    'AmountPaid' => [
                        'label' => ' المبلغ المدفوع',
                        'type' => 'number',
                        'name' => 'AmountPaid',
                        'id' => 'amountPaid',
                        'icon' => 'fas fa-money-check',
                    ],
                    'AmountRemaining' => [
                        'label' => ' المبلغ المتبقي',
                        'type' => 'number',
                        'name' => 'AmountRemaining',
                        'id' => 'amountRemaining',
                        'show' => 'readonly',
                        'icon' => 'fas fa-balance-scale',
                    ],
                    'FinalPrice' => [
                        'label' => 'الاجمالي بعدالخصم والضريبة',
                        'type' => 'number',
                        'name' => 'FinalPrice',
                        'id' => 'finalPrice',
                        'show' => 'readonly',
                        'icon' => 'fas fa-calculator',
                    ],
                ]" />









                <!-- ملاحظات إدارية -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold"><i class="fas fa-sticky-note text-info me-1"></i> ملاحظات
                        إدارية</label>
                    <textarea name="AdminNotes" class="form-control shadow-sm" rows="3">{{ old('AdminNotes') }}</textarea>
                    @error('AdminNotes')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <!-- زر الحفظ -->
                <div class="col-12 text-end">
                    <x-button :save="true" text="اضافة اشتراك جديد" />
                </div>
            </form>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const planSelect = document.getElementById("planSelect");
            const trialPeriod = document.getElementById("trialPeriod");
            const trialUnit = document.getElementById("trialUnit");
            const lateFee = document.getElementById("lateFee");
            const gracePeriod = document.getElementById("gracePeriod");

            planSelect.addEventListener("change", function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    trialPeriod.value = selectedOption.getAttribute("data-trial_period_value") ?? '';
                    trialUnit.value = selectedOption.getAttribute("data-trial_period_unit") ?? 'days';
                    lateFee.value = selectedOption.getAttribute("data-late_fee") ?? '';
                    gracePeriod.value = selectedOption.getAttribute("data-grace_period") ?? '';
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const planSelect = document.getElementById('planSelect');
            const subscriptionDuration = document.getElementById('subscriptionDuration');

            planSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const subscriptionType = selectedOption.getAttribute('data-subscription_type_label');

                if (subscriptionType) {
                    subscriptionDuration.value = subscriptionType;
                } else {
                    subscriptionDuration.value = "";
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const planSelect = document.getElementById('planSelect');
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            const renewalDateInput = document.getElementById('renewalDate');

            // اجعل تاريخ البداية اليوم افتراضيًا
            const today = new Date();
            startDateInput.value = today.toISOString().split('T')[0];

            planSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const duration = selectedOption.getAttribute('data-duration');

                if (duration) {
                    // حساب تاريخ النهاية
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(startDate);
                    endDate.setDate(endDate.getDate() + parseInt(duration));

                    const formattedEnd = endDate.toISOString().split('T')[0];
                    endDateInput.value = formattedEnd;
                    renewalDateInput.value = formattedEnd;
                } else {
                    endDateInput.value = "";
                    renewalDateInput.value = "";
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const planSelect = document.getElementById('planSelect');
            const planPriceInput = document.getElementById('planPrice');
            const taxRateInput = document.getElementById('taxRate');
            const discountRateInput = document.getElementById('discountRate');
            const discountAmountInput = document.getElementById('discountAmount');
            const finalPriceInput = document.getElementById('finalPrice');
            const amountPaidInput = document.getElementById('amountPaid');
            const amountRemainingInput = document.getElementById('amountRemaining');

            // عند اختيار الباقة
            planSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute('data-plan_price')) || 0;
                const taxRate = parseFloat(selectedOption.getAttribute('data-tax_rate')) ||
                    0; // نسبة عشرية مثل 0.04
                const discountRate = parseFloat(selectedOption.getAttribute('data-discount_rate')) ||
                    0; // نسبة عشرية مثل 0.04

                planPriceInput.value = price;
                taxRateInput.value = taxRate;
                discountRateInput.value = discountRate;

                // حساب قيمة الخصم (السعر × النسبة)
                const discountAmount = price * discountRate;
                discountAmountInput.value = discountAmount.toFixed(2);

                // السعر بعد الخصم
                const priceAfterDiscount = price - discountAmount;

                // حساب الضريبة على السعر بعد الخصم
                const taxAmount = priceAfterDiscount * taxRate;

                // السعر النهائي بعد الخصم والضريبة
                const finalPrice = priceAfterDiscount + taxAmount;
                finalPriceInput.value = finalPrice.toFixed(2);

                // إعادة تعيين المبلغ المتبقي
                amountRemainingInput.value = "";
            });

            // عند إدخال المبلغ المدفوع
            amountPaidInput.addEventListener('input', function() {
                const finalPrice = parseFloat(finalPriceInput.value) || 0;
                const paid = parseFloat(this.value) || 0;
                let remaining = finalPrice - paid;

                if (remaining < 0) remaining = 0; // لا يكون سالب
                amountRemainingInput.value = remaining.toFixed(2);
            });
        });
    </script>



@endsection
