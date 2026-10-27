
@extends('admin.layout.master')

@section('title')
    تعديل باقة الاشتراك
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
                <i class="fas fa-box-open me-2"></i>
                تعديل باقة الاشتراك
            </h4>
        </div>

        <div class="card-body">
            <form
                class="row g-4"
                method="POST"
                action="{{ route('admin.packages.update', $data['package_id']) }}"
            >
                @csrf

                <x-constructor-form
                    :rows="$data"

                    :types="[
                        'name' => [
                            'label' => 'اسم الباقة',
                            'name' => 'name',
                            'icon' => 'fas fa-box'
                        ],
                        'code' => [
                            'label' => 'الرمز التعريفي للباقة',
                            'name' => 'code',
                            'icon' => 'fas fa-barcode'
                        ],
                        'level' => [
                            'label' => 'مستوى الباقة (1 أساسية، 2 متوسطة، 3 متقدمة)',
                            'type' => 'number',
                            'name' => 'level',
                            'icon' => 'fas fa-layer-group'
                        ],
                        'sort_order' => [
                            'label' => 'ترتيب ظهور الباقة',
                            'type' => 'number',
                            'name' => 'sort_order',
                            'icon' => 'fas fa-sort'
                        ],
                        'description' => [
                            'label' => 'وصف الباقة ومميزاتها',
                            'type' => 'text',
                            'name' => 'description',
                            'icon' => 'fas fa-align-left'
                        ],
                        'base_price' => [
                            'label' => 'السعر الأساسي قبل الخصم والضريبة',
                            'type' => 'number',
                            'step' => '0.01',
                            'name' => 'base_price',
                            'icon' => 'fas fa-dollar-sign'
                        ],
                        'discount_value' => [
                            'label' => 'قيمة الخصم (مبلغ أو نسبة حسب النوع)',
                            'type' => 'number',
                            'step' => '0.01',
                            'name' => 'discount_value',
                            'icon' => 'fas fa-tags'
                        ],
                        'tax_value' => [
                            'label' => 'قيمة الضريبة (مبلغ أو نسبة حسب النوع)',
                            'type' => 'number',
                            'step' => '0.01',
                            'name' => 'tax_value',
                            'icon' => 'fas fa-percentage'
                        ],
                        'duration_days' => [
                            'label' => 'مدة الاشتراك بالأيام',
                            'type' => 'number',
                            'name' => 'duration_days',
                            'icon' => 'fas fa-calendar'
                        ],
                        'reminder_before_days' => [
                            'label' => 'عدد الأيام قبل انتهاء الاشتراك لإرسال التنبيه',
                            'type' => 'number',
                            'name' => 'reminder_before_days',
                            'icon' => 'fas fa-bell'
                        ],
                        'reminder_after_expiry_days' => [
                            'label' => 'عدد الأيام بعد انتهاء الاشتراك لإرسال تنبيه بغرامة التاخير ',
                            'type' => 'number',
                            'name' => 'reminder_after_expiry_days',
                            'icon' => 'fas fa-bell-slash'
                        ],
                        'grace_period_days' => [
                            'label' => 'فترة السماح بعد انتهاء الاشتراك قبل تطبيق الغرامه',
                            'type' => 'number',
                            'name' => 'grace_period_days',
                            'icon' => 'fas fa-hourglass-half'
                        ],
                        'penalty_value' => [
                            'label' => 'قيمة غرامة التأخير (مبلغ أو نسبة حسب النوع)',
                            'type' => 'number',
                            'step' => '0.01',
                            'name' => 'penalty_value',
                            'icon' => 'fas fa-exclamation-triangle'
                        ],
                        'max_penalty' => [
                            'label' => 'الحد الأقصى لغرامات التأخير',
                            'type' => 'number',
                            'step' => '0.01',
                            'name' => 'max_penalty',
                            'icon' => 'fas fa-ban'
                        ],
                        'suspend_after_days' => [
                            'label' => 'عدد الأيام قبل إيقاف الحساب بعد انتهاء الاشتراك وتطبيق الغرامه',
                            'type' => 'number',
                            'name' => 'suspend_after_days',
                            'icon' => 'fas fa-power-off'
                        ],
                        'max_users' => [
                            'label' => 'أقصى عدد للمستخدمين المسموح بهم',
                            'type' => 'number',
                            'name' => 'max_users',
                            'icon' => 'fas fa-users'
                        ],
                        'max_branches' => [
                            'label' => 'أقصى عدد للفروع المسموح بها',
                            'type' => 'number',
                            'name' => 'max_branches',
                            'icon' => 'fas fa-code-branch'
                        ],
                        'max_properties' => [
                            'label' => 'أقصى عدد للعقارات المسموح بها',
                            'type' => 'number',
                            'name' => 'max_properties',
                            'icon' => 'fas fa-building'
                        ],
                        'max_units' => [
                            'label' => 'أقصى عدد للوحدات العقارية المسموح بها',
                            'type' => 'number',
                            'name' => 'max_units',
                            'icon' => 'fas fa-home'
                        ],
                        'storage_limit_mb' => [
                            'label' => 'الحد الأقصى لمساحة التخزين (ميجابايت)',
                            'type' => 'number',
                            'name' => 'storage_limit_mb',
                            'icon' => 'fas fa-database'
                        ],
                        'max_api_requests' => [
                            'label' => 'أقصى عدد لطلبات API المسموح بها',
                            'type' => 'number',
                            'name' => 'max_api_requests',
                            'icon' => 'fas fa-plug'
                        ],
                        'notes' => [
                            'label' => 'ملاحظات داخلية حول الباقة',
                            'type' => 'text',
                            'name' => 'notes',
                            'icon' => 'fas fa-sticky-note'
                        ],
                    ]"

                    :selectes="[
                        'billing_cycle' => [
                            'label' => 'دورة تحصيل رسوم الاشتراك',
                            'name' => 'billing_cycle',
                            'icon' => 'fas fa-sync',
                            'option' => [
                                'monthly' => 'شهري',
                                'yearly' => 'سنوي',
                                'weekly' => 'أسبوعي',
                            ],
                        ],

                        'discount_type' => [
                            'label' => 'طريقة احتساب الخصم',
                            'name' => 'discount_type',
                            'icon' => 'fas fa-tags',
                            'option' => [
                                'fixed' => 'مبلغ ثابت',
                                'percent' => 'نسبة مئوية',
                            ],
                        ],

                        'tax_type' => [
                            'label' => 'طريقة احتساب الضريبة',
                            'name' => 'tax_type',
                            'icon' => 'fas fa-percentage',
                            'option' => [
                                'fixed' => 'مبلغ ثابت',
                                'percent' => 'نسبة مئوية',
                            ],
                        ],

                        'penalty_type' => [
                            'label' => 'طريقة احتساب غرامة التأخير',
                            'name' => 'penalty_type',
                            'icon' => 'fas fa-gavel',
                            'option' => [
                                'fixed' => 'مبلغ ثابت',
                                'percent' => 'نسبة مئوية',
                            ],
                        ],

                        'penalty_repeat' => [
                            'label' => 'معدل تكرار غرامة التأخير',
                            'name' => 'penalty_repeat',
                            'icon' => 'fas fa-redo',
                            'option' => [
                                'once' => 'مرة واحدة',
                                'daily' => 'يومي',
                                'weekly' => 'أسبوعي',
                                'monthly' => 'شهري',
                            ],
                        ],

                        'allow_login_after_expiry' => [
                            'label' => 'السماح بتسجيل الدخول بعد انتهاء الاشتراك',
                            'name' => 'allow_login_after_expiry',
                            'icon' => 'fas fa-sign-in-alt',
                            'option' => [
                                1 => 'نعم',
                                0 => 'لا',
                            ],
                        ],

                        'allow_readonly_mode' => [
                            'label' => 'السماح بوضع القراءة فقط بعد انتهاء الاشتراك',
                            'name' => 'allow_readonly_mode',
                            'icon' => 'fas fa-eye',
                            'option' => [
                                1 => 'نعم',
                                0 => 'لا',
                            ],
                        ],

                        'is_active' => [
                            'label' => 'حالة إتاحة الباقة للاشتراك',
                            'name' => 'is_active',
                            'icon' => 'fas fa-toggle-on',
                            'option' => [
                                1 => 'متاحة',
                                0 => 'غير متاحة',
                            ],
                        ],

                        'is_featured' => [
                            'label' => 'إبراز الباقة ضمن الباقات المميزة',
                            'name' => 'is_featured',
                            'icon' => 'fas fa-star',
                            'option' => [
                                1 => 'نعم',
                                0 => 'لا',
                            ],
                        ],
                    ]"
                />

                <div class="col-12 text-end">
                    <x-button :save="true" text="تحديث الباقة" />
                </div>
            </form>
        </div>
    </div>
@endsection
