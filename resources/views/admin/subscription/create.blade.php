@extends('admin.layout.master')

@section('title')
    باقات الاشتراك
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
                <i class="fas fa-box-open me-2"></i> اضافة باقة اشتراك جديدة
            </h4>
        </div>

        <div class="card-body">
            <form class="row g-4" method="POST" action="{{ route('admin.subscription.store') }}">
                @csrf


                <x-constructor-form :types="[
                    'plan_name' => [
                        'label' => 'اسم الباقة',
                        'name' => 'plan_name',
                        'icon' => 'fas fa-tag '
                    ],
                    'late_fee' => [
                        'label' => 'غرامة التاخير',
                        'type' => 'number',
                        'name' => 'late_fee',
                        'icon' => 'fas fa-exclamation-triangle '
                    ],
                    'grace_period' => [
                        'label' => ' فترة السماح',
                        'type' => 'number',
                        'name' => 'grace_period',
                        'icon' => 'fas fa-hourglass-half',
                    ],
                    'plan_price' => [
                        'label' => ' السعر الاساسي',
                        'type' => 'number',
                        'name' => 'plan_price',
                        'icon' => 'fas fa-dollar-sign',
                    ],
                    'tax_rate' => [
                        'label' => 'نسبة الضريبة',
                        'type' => 'number',
                        'name' => 'tax_rate',
                        'step'=>'0.01',
                        'icon' => 'fas fa-percentage'
                    ],
                    'discount_rate' => [
                        'label' => 'نسبة الخصم',
                        'type' => 'number',
                        'name' => 'discount_rate',
                        'step'=>'0.01',
                        'icon' => 'fas fa-percent'
                    ],
                
                    'max_users' => [
                        'label' => 'الحد الاقصى للمستخدمين',
                        'type' => 'number',
                        'name' => 'max_users',
                        'icon' => 'fas fa-users',
                    ],
                    'max_branches' => [
                        'label' => 'الحد الاقصى للفروع',
                        'type' => 'number',
                        'name' => 'max_branches',
                        'icon' => 'fas fa-code-branch',
                    ],
                    'storage_space' => [
                        'label' => 'مساحة التخزين(GB)',
                        'type' => 'number',
                        'name' => 'storage_space',
                        'icon' => 'fas fa-hdd',
                    ],
                ]" :selectes="[
                    'plan_tier' => [
                        'label' => 'مستوى الباقة',
                        'name' => 'plan_tier',
                        'icon' => 'fas fa-layer-group',
                        'option' => [
                            'Basic' => 'أساسية',
                            'Standard' => 'قياسية',
                            'Premium' => 'متقدمة',
                            'Enterprise' => 'مؤسسات',
                        ],
                    ],
                    'subscription_type' => [
                        'label' => 'نوع الباقة',
                        'name' => 'subscription_type',
                        'icon' => 'fas fa-calendar-alt',
                        'option' => [
                            'monthly' => 'شهري',
                            'yearly' => 'سنوي',
                            'long_term' => 'اكثر من سنة',
                        ],
                    ],
                    'trial_period_value' => [
                        'label' => 'الفترة التجريبية',
                        'icon' => 'fas fa-clock',
                        'type' => 'number',
                        'type_name' => 'trial_period_value',
                        'select_name' => 'trial_period_unit',
                        'option' => [
                            'days' => 'ايام ',
                            'weeks' => 'اسابيع',
                            'months' => 'اشهر',
                        ],
                    ],
                    'currency' => [
                        'label' => 'العملة',
                        'name' => 'currency',
                        'icon' => 'fas fa-coins',
                        'option' => [
                            'USD' => 'دولار أمريكي (USD) ',
                            'EUR' => 'يورو (EUR)',
                            'SAR' => 'ريال سعودي (SAR)',
                            'GBP' => 'جنيه إسترليني (GBP) ',
                            'YER' => 'ريال يمني (YER)',
                        ],
                    ],
                    'duration' => [
                        'label' => 'مدة الباقة',
                        'name' => 'duration',
                        'icon' => 'fas fa-clock',
                        'option' => [
                            '30' => 'شهر واحد',
                            '90' => '3 اشهر',
                            '180' => '6 اشهر',
                            '365' => 'سنة كاملة',
                        ],
                    ],
                    'status' => [
                        'label' => ' حالة الباقة',
                        'name' => 'status',
                        'icon' => 'fas fa-toggle-on',
                        'option' => [
                            'pending_approval' => 'قيد الموافقة ',
                            'active' => 'نشطة ',
                            'suspended' => 'موقوفة ',
                            'blocked' => 'محضورة ',
                            'expired' => 'منتهية ',
                        ],
                    ],
                ]" />





                <!-- الميزات -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold"><i class="fas fa-list text-info me-1"></i> الميزات</label>
                    <textarea name="features" class="form-control shadow-sm" rows="3" placeholder="أدخل قائمة الميزات...">{{ old('features') }}</textarea>
                </div>

                <!-- زر الحفظ -->
                <div class="col-12 text-end">
                    <x-button :save='true' text='اضافة باقة جديدة' />
                </div>
            </form>
        </div>
    </div>
@endsection
