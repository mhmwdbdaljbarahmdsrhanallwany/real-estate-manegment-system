
@extends('admin.layout.master')

@section('title')
    إضافة صندوق مالي
@endsection

@section('contect')

<div class="card shadow-lg border-0 rounded-4 overflow-hidden"
     style="margin-top: 10px">

    {{-- رأس الصفحة --}}
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold text-primary">
            <i class="fas fa-cash-register ml-2"></i>
            إضافة صندوق مالي جديد
        </h4>

        <a href="{{ route('admin.cashboxes.index') }}"
           class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-right ml-1"></i>
            العودة إلى الصناديق
        </a>
    </div>

    {{-- النموذج --}}
    <form
        id="cashboxForm"
        method="POST"
        action="{{ route('admin.cashboxes.store') }}"
    >
        @csrf

        <div class="p-3">

            {{-- بيانات الصندوق --}}
            <div class="form-section mb-4"
                 style="margin: 10px 10px 20px">

                <h5 class="mb-3 text-primary">
                    <i class="fas fa-info-circle ml-2"></i>
                    بيانات الصندوق الأساسية
                </h5>

                <div class="row">

                    <x-constructor-form :types="[

                        'name' => [
                            'label' => 'اسم الصندوق',
                            'name' => 'name',
                            'type' => 'text',
                            'icon' => 'fas fa-cash-register',
                            'placeholder' => 'مثال: الصندوق الرئيسي',
                            'required' => true,
                            'maxlength' => 255,
                        ],

                        'code' => [
                            'label' => 'كود الصندوق',
                            'name' => 'code',
                            'type' => 'text',
                            'icon' => 'fas fa-barcode',
                            'placeholder' => 'مثال: CASH-MAIN',
                            'required' => true,
                            'maxlength' => 255,
                        ],

                        'opening_balance' => [
                            'label' => 'الرصيد الافتتاحي',
                            'name' => 'opening_balance',
                            'type' => 'number',
                            'icon' => 'fas fa-money-bill-wave',
                            'placeholder' => 'أدخل الرصيد الافتتاحي',
                            'required' => true,
                            'min' => 0,
                            'step' => '0.01',
                        ],

                    ]" />

                    <x-constructor-form :selectes="[

                        'currency' => [
                            'label' => 'عملة الصندوق',
                            'name' => 'currency',
                            'icon' => 'fas fa-coins',
                            'type' => 'select',
                            'required' => true,
                            'default' => 'YER',
                            'option' => [
                                'YER' => 'YER - ريال يمني',
                                'USD' => 'USD - دولار أمريكي',
                                'SAR' => 'SAR - ريال سعودي',
                            ],
                        ],

                    ]" />

                </div>
            </div>

            {{-- إعدادات الصندوق --}}
            <div class="form-section mb-4"
                 style="margin: 10px">

                <h5 class="mb-3 text-primary">
                    <i class="fas fa-sliders-h ml-2"></i>
                    إعدادات الصندوق
                </h5>

                <div class="row">
                    <div class="col-12 col-md-6 sForm-field"
                         style="margin-top: 10px">

                        <label class="sForm-label" for="is_main">
                            <i class="fas fa-star"></i>
                            الصندوق الرئيسي
                        </label>

                        <div class="custom-control custom-checkbox">
                            <input
                                type="checkbox"
                                name="is_main"
                                id="is_main"
                                value="1"
                                class="custom-control-input"
                                {{ old('is_main') ? 'checked' : '' }}
                            >

                            <label class="custom-control-label"
                                   for="is_main">
                                تعيين هذا الصندوق صندوقًا رئيسيًا
                            </label>
                        </div>

                        <small class="text-muted d-block mt-2">
                            لا يمكن تعيين أكثر من صندوق رئيسي واحد.
                        </small>

                        @error('is_main')
                            <span class="sForm-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle ml-1"></i>
                    سيُعيّن الرصيد الحالي مساويًا للرصيد الافتتاحي عند إنشاء الصندوق.
                    أما التغييرات المالية اللاحقة فتُسجّل من خلال العمليات المالية.
                </div>
            </div>

        </div>

        {{-- أزرار النموذج --}}
        <div class="card-footer d-flex flex-wrap" style="gap: 8px">

            <button type="submit"
                    id="saveCashboxBtn"
                    class="btn btn-primary px-3">
                <i class="fas fa-save ml-1"></i>
                حفظ الصندوق
            </button>

            <button type="reset"
                    class="btn btn-outline-secondary px-3">
                <i class="fas fa-undo ml-1"></i>
                تفريغ الحقول
            </button>

            <a href="{{ route('admin.cashboxes.index') }}"
               class="btn btn-secondary px-3">
                إلغاء
            </a>

        </div>
    </form>
</div>

@endsection
