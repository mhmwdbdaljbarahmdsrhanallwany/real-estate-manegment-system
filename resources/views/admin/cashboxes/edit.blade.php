
@extends('admin.layout.master')

@section('title')
    تعديل الصندوق المالي
@endsection

@section('contect')

<div class="container-fluid" dir="rtl">

    {{-- عنوان الصفحة --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>
                    <h3 class="mb-1 text-primary">
                        <i class="fas fa-edit ml-2"></i>
                        تعديل الصندوق المالي
                    </h3>

                    <p class="text-muted mb-0">
                        تعديل بيانات الصندوق وإعداداته الأساسية.
                    </p>
                </div>

                <a href="{{ route('admin.cashboxes.index') }}"
                   class="btn btn-secondary mt-2">
                    <i class="fas fa-arrow-right ml-1"></i>
                    العودة إلى الصناديق
                </a>

            </div>
        </div>
    </div>

    {{-- بيانات الصندوق --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-light">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-cash-register ml-2"></i>
                بيانات الصندوق:
                {{ $cashbox->name }}
            </h5>
        </div>

        <form
            method="POST"
            action="{{ route('admin.cashboxes.update', $cashbox->cashboxe_id) }}"
        >
            @csrf
            @method('PUT')

            <div class="card-body">

                {{-- البيانات الأساسية --}}
                <div class="form-section mb-4"
                     style="margin: 10px">

                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-info-circle ml-2"></i>
                        البيانات الأساسية
                    </h5>

                    <div class="row">

                        <x-constructor-form :types="[
                            'name'=>[
                                'label'=>'اسم الصندوق',
                                'name'=>'name',
                                'type'=>'text',
                                'icon'=>'fas fa-cash-register',
                                'placeholder'=>'أدخل اسم الصندوق',
                                'required'=>true,
                                'maxlength'=>255
                            ],

                            'code'=>[
                                'label'=>'كود الصندوق',
                                'name'=>'code',
                                'type'=>'text',
                                'icon'=>'fas fa-barcode',
                                'placeholder'=>'أدخل كود الصندوق',
                                'required'=>true,
                                'maxlength'=>255
                            ],
                        ]" :rows="$cashbox" />

                        <x-constructor-form :selectes="[
                            'currency'=>[
                                'label'=>'عملة الصندوق',
                                'name'=>'currency',
                                'icon'=>'fas fa-coins',
                                'type'=>'select',
                                'required'=>true,
                                'default'=>'YER',
                                'option'=>[
                                    'YER'=>'YER - ريال يمني',
                                    'USD'=>'USD - دولار أمريكي',
                                    'SAR'=>'SAR - ريال سعودي'
                                ]
                            ],

                            'status'=>[
                                'label'=>'حالة الصندوق',
                                'name'=>'status',
                                'icon'=>'fas fa-toggle-on',
                                'type'=>'select',
                                'required'=>true,
                                'default'=>'active',
                                'option'=>[
                                    'active'=>'نشط',
                                    'inactive'=>'غير نشط'
                                ]
                            ],
                        ]" :rows="$cashbox" />

                    </div>
                </div>

                <hr>

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
                                <i class="fas fa-star ml-1"></i>
                                الصندوق الرئيسي
                            </label>

                            <div class="custom-control custom-checkbox">

                                <input
                                    type="checkbox"
                                    name="is_main"
                                    id="is_main"
                                    value="1"
                                    class="custom-control-input"
                                    {{ old('is_main', $cashbox->is_main) ? 'checked' : '' }}
                                >

                                <label class="custom-control-label"
                                       for="is_main">
                                    تعيين هذا الصندوق صندوقًا رئيسيًا
                                </label>

                            </div>

                            <small class="text-muted d-block mt-2">
                                لا يمكن وجود أكثر من صندوق رئيسي واحد.
                            </small>

                            @error('is_main')
                                <span class="sForm-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>
                </div>

                {{-- عرض الأرصدة دون السماح بتعديلها --}}
                <div class="form-section mb-3"
                     style="margin: 10px">

                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-wallet ml-2"></i>
                        معلومات الأرصدة
                    </h5>

                    <div class="row">

                        <div class="col-12 col-md-6 mb-3">
                            <label class="sForm-label">
                                الرصيد الافتتاحي
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ number_format((float) $cashbox->opening_balance, 2) }}"
                                readonly
                            >
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="sForm-label">
                                الرصيد الحالي
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ number_format((float) $cashbox->current_balance, 2) }}"
                                readonly
                            >
                        </div>

                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle ml-1"></i>
                        الأرصدة للعرض فقط، ويتم تغيير الرصيد الحالي من خلال
                        العمليات المالية المعتمدة، وليس من صفحة تعديل الصندوق.
                    </div>

                </div>

            </div>

            {{-- أزرار الإجراءات --}}
            <div class="card-footer d-flex flex-wrap"
                 style="gap: 8px">

                <button type="submit"
                        class="btn btn-primary px-3">
                    <i class="fas fa-save ml-1"></i>
                    حفظ التعديلات
                </button>

                <a href="{{ route('admin.cashboxes.index') }}"
                   class="btn btn-secondary px-3">
                    <i class="fas fa-times ml-1"></i>
                    إلغاء
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
