
@extends('admin.layout.master')

@section('title')
اضافة بيانات مؤسسة
@endsection

@section('contect')

@include('admin.incloude.agencynavepare')

<div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="margin-top: 10px">

    {{-- رسائل الأخطاء --}}
    @if ($errors->any())
        @section('script')
            <script>
                toastr.error("{{ $errors->first() }}");
            </script>
        @endsection
    @endif


    {{-- ================== الهيدر ================== --}}
    <div class="card-header bg-light">
        <h4 class="mb-0 fw-bold text-primary">
            <i class="fas fa-building me-2"></i>
            إضافة مؤسسة جديدة
        </h4>
    </div>


    {{-- ================== النموذج ================== --}}
    <form
        id="agencyForm"
        enctype="multipart/form-data"
        method="POST"
        action="{{ route('admin.organizations.store') }}"
    >

        @csrf
{{-- ========================================================= --}}
{{-- القسم 1 : بيانات المؤسسة الأساسية --}}
{{-- ========================================================= --}}
<div
    class="form-section mb-4"
    style="margin-right: 20px; margin-top: 20px; margin-left: 20px;"
>

    <h5 class="mb-3 text-primary">
        <i class="fas fa-building ml-2"></i>
        بيانات المؤسسة الأساسية
    </h5>

    <div class="row">

        <x-constructor-form :types="[

            'name' => [
                'label' => 'اسم المؤسسة',
                'name' => 'name',
                'icon' => 'fas fa-building',
                'required' => true,
            ],

            'commercial_name' => [
                'label' => 'الاسم التجاري',
                'name' => 'commercial_name',
                'icon' => 'fas fa-store',
            ],

            'commercial_registration' => [
                'label' => 'السجل التجاري',
                'type' => 'text',
                'name' => 'commercial_registration',
                'icon' => 'fas fa-file-signature',
                'required' => true,
            ],

            'tax_number' => [
                'label' => 'الرقم الضريبي',
                'type' => 'text',
                'name' => 'tax_number',
                'icon' => 'fas fa-receipt',
                'required' => true,
            ],

            'unified_national_number' => [
                'label' => 'الرقم الوطني الموحد',
                'type' => 'text',
                'name' => 'unified_national_number',
                'icon' => 'fas fa-id-card',
            ],

            'phone' => [
                'label' => 'الهاتف',
                'type' => 'text',
                'name' => 'phone',
                'icon' => 'fas fa-phone',
                'required' => true,
            ],

            'mobile' => [
                'label' => 'الجوال',
                'type' => 'text',
                'name' => 'mobile',
                'icon' => 'fas fa-mobile-alt',
            ],

            'logo' => [
                'label' => 'شعار المؤسسة',
                'type' => 'file',
                'name' => 'logo',
                'icon' => 'fas fa-image',
            ],

            'municipality_license' => [
                'label' => 'رخصة البلدية',
                'type' => 'file',
                'name' => 'municipality_license',
                'icon' => 'fas fa-file-contract',
                'required' => true,
            ],

            'real_estate_broker_license' => [
                'label' => 'رخصة الوساطة العقارية',
                'type' => 'file',
                'name' => 'real_estate_broker_license',
                'icon' => 'fas fa-certificate',
                'required' => true,
            ],

        ]" />

    </div>
</div>


{{-- ========================================================= --}}
{{-- القسم 2 : بيانات التواصل --}}
{{-- ========================================================= --}}
<div
    class="form-section d-none mb-4"
    style="margin-right: 20px; margin-left: 20px;"
>

    <h5 class="mb-3 text-success">
        <i class="fas fa-address-book ml-2"></i>
        بيانات التواصل
    </h5>

    <div class="row">

        <x-constructor-form :types="[

            'email' => [
                'label' => 'البريد الإلكتروني',
                'type' => 'email',
                'name' => 'email',
                'icon' => 'fas fa-envelope',
                'required' => true,
            ],

            'website' => [
                'label' => 'الموقع الإلكتروني',
                'type' => 'url',
                'name' => 'website',
                'icon' => 'fas fa-globe',
            ],

        ]" />

    </div>
</div>


{{-- ========================================================= --}}
{{-- القسم 3 : عنوان المؤسسة --}}
{{-- ========================================================= --}}
<div
    class="form-section d-none mb-4"
    style="margin-right: 20px; margin-left: 20px;"
>

    <h5 class="mb-3 text-info">
        <i class="fas fa-map-marker-alt ml-2"></i>
        عنوان المؤسسة
    </h5>

    <div class="row">

        <x-constructor-form :types="[

            'country' => [
                'label' => 'الدولة',
                'name' => 'country',
                'icon' => 'fas fa-flag',
                'required' => true,
            ],

            'state' => [
                'label' => 'المحافظة / المنطقة',
                'name' => 'state',
                'icon' => 'fas fa-map',
                'required' => true,
            ],

            'city' => [
                'label' => 'المدينة',
                'name' => 'city',
                'icon' => 'fas fa-city',
                'required' => true,
            ],

            'district' => [
                'label' => 'الحي',
                'name' => 'district',
                'icon' => 'fas fa-map-marker-alt',
                'required' => true,
            ],

            'street' => [
                'label' => 'الشارع',
                'name' => 'street',
                'icon' => 'fas fa-road',
            ],

            'address' => [
                'label' => 'العنوان التفصيلي',
                'name' => 'address',
                'icon' => 'fas fa-address-card',
            ],

            'postal_code' => [
                'label' => 'الرمز البريدي',
                'type' => 'text',
                'name' => 'postal_code',
                'icon' => 'fas fa-mail-bulk',
            ],

            'po_box' => [
                'label' => 'صندوق البريد',
                'type' => 'text',
                'name' => 'po_box',
                'icon' => 'fas fa-inbox',
            ],

        ]" />

    </div>
</div>


{{-- ========================================================= --}}
{{-- القسم 4 : موقع المؤسسة --}}
{{-- ========================================================= --}}
<div
    class="form-section d-none mb-4"
    style="margin-right: 20px; margin-left: 20px;"
>

    <h5 class="mb-3 text-danger">
        <i class="fas fa-map-marked-alt ml-2"></i>
        موقع المؤسسة
    </h5>

    <div class="col-12 mb-4">

        <label class="sForm-label">
            <i class="fas fa-map-marker-alt"></i>
            موقع المؤسسة

            <span class="text-danger">*</span>
        </label>

        <div
            id="organization-map"
            style="
                height: 400px;
                width: 100%;
                border-radius: 10px;
            "
        ></div>

        <div class="row mt-2">

            <x-constructor-form :types="[

                'latitude' => [
                    'label' => 'خط العرض',
                    'type' => 'text',
                    'name' => 'latitude',
                    'id' => 'latitude',
                    'icon' => 'fas fa-arrows-alt-h',
                    'required' => true,
                ],

                'longitude' => [
                    'label' => 'خط الطول',
                    'type' => 'text',
                    'name' => 'longitude',
                    'id' => 'longitude',
                    'icon' => 'fas fa-arrows-alt-v',
                    'required' => true,
                ],

                'map_url' => [
                    'label' => 'رابط الموقع على الخريطة',
                    'type' => 'url',
                    'name' => 'map_url',
                    'id' => 'map_url',
                    'icon' => 'fas fa-map-marked-alt',
                    'required' => true,
                ],

            ]" />

        </div>

    </div>
</div>


{{-- ========================================================= --}}
{{-- القسم 5 : الممثل القانوني --}}
{{-- ========================================================= --}}
<div
    class="form-section d-none mb-4"
    style="margin-right: 20px; margin-left: 20px;"
>

    <h5 class="mb-3 text-secondary">
        <i class="fas fa-user-tie ml-2"></i>
        الممثل القانوني
    </h5>

    <div class="row">

        <x-constructor-form :types="[

            'legal_representative_name' => [
                'label' => 'اسم الممثل القانوني',
                'name' => 'legal_representative_name',
                'icon' => 'fas fa-user-tie',
            ],

            'legal_representative_id' => [
                'label' => 'رقم هوية الممثل القانوني',
                'type' => 'text',
                'name' => 'legal_representative_id',
                'icon' => 'fas fa-id-badge',
            ],

            'legal_representative_phone' => [
                'label' => 'جوال الممثل القانوني',
                'type' => 'text',
                'name' => 'legal_representative_phone',
                'icon' => 'fas fa-phone-square-alt',
            ],

        ]" />

    </div>
</div>


{{-- ========================================================= --}}
{{-- القسم 6 : البيانات البنكية --}}
{{-- ========================================================= --}}
<div
    class="form-section d-none mb-4"
    style="margin-right: 20px; margin-left: 20px;"
>

    <h5 class="mb-3 text-warning">
        <i class="fas fa-university ml-2"></i>
        البيانات البنكية
    </h5>

    <div class="row">

        <x-constructor-form :types="[

            'bank_name' => [
                'label' => 'اسم البنك',
                'name' => 'bank_name',
                'icon' => 'fas fa-university',
                'required' => true,
            ],

            'bank_account_name' => [
                'label' => 'اسم صاحب الحساب',
                'name' => 'bank_account_name',
                'icon' => 'fas fa-user',
                'required' => true,
            ],

            'bank_account_number' => [
                'label' => 'رقم الحساب البنكي',
                'type' => 'text',
                'name' => 'bank_account_number',
                'icon' => 'fas fa-credit-card',
                'required' => true,
            ],

            'bank_iban' => [
                'label' => 'رقم الآيبان',
                'type' => 'text',
                'name' => 'bank_iban',
                'icon' => 'fas fa-money-check-alt',
            ],

        ]" />

    </div>
</div>

        {{-- ========================================================= --}}
        {{-- زر الحفظ --}}
        {{-- ========================================================= --}}
        <div
            class="text-end mt-4"
            style="margin-right: 20px; margin-bottom: 10px;"
        >

            <button
                type="submit"
                class="btn btn-primary px-3"
            >
                اضافة مؤسسة
            </button>

        </div>

    </form>


    {{-- ========================================================= --}}
    {{-- Leaflet CSS --}}
    {{-- ========================================================= --}}
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >

    {{-- CSS الخاص بالخريطة --}}
    <link
        rel="stylesheet" href="{{ asset('assets/css/maps/map.css') }}"
    >


    {{-- ========================================================= --}}
    {{-- Leaflet JS --}}
    {{-- ========================================================= --}}
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
    </script>

    {{-- JavaScript الخاص بالخريطة --}}
    <script
        src="{{ asset('assets/css/maps/map.js') }}">
    </script>


    {{-- ========================================================= --}}
    {{-- تهيئة الخريطة والانتقال بين الأقسام --}}
    {{-- ========================================================= --}}
    <script>

        document.addEventListener(
            "DOMContentLoaded",
            function () {

                /*
                |==========================================================
                | تهيئة الخريطة
                |==========================================================
                */

                if (
                    typeof MapManager !== "undefined" &&
                    document.getElementById("organization-map")
                ) {

                    MapManager.init({

                        mapElement:
                            "organization-map",

                        latitudeInput:
                            "latitude",

                        longitudeInput:
                            "longitude",

                        mapUrlInput:
                            "map_url",

                        apiKey:
                            "{{ config('services.maptiler.api_key') }}"

                    });

                }


                /*
                |==========================================================
                | أقسام النموذج
                |==========================================================
                */

                const sections =
                    document.querySelectorAll(
                        ".form-section"
                    );


                const mapElement =
                    document.getElementById(
                        "organization-map"
                    );
/*
|==========================================================
| الانتقال التلقائي بين الأقسام
|==========================================================
*/

function checkSection(section, index) {

/*
|==========================================================
| الحصول على الحقول المطلوبة فقط
|==========================================================
*/

const requiredInputs =
    section.querySelectorAll(
        "input[required], select[required], textarea[required]"
    );


/*
|==========================================================
| التحقق من الحقول المطلوبة
|==========================================================
*/

const filled =
    Array.from(requiredInputs).every(
        function (el) {

            /*
            | الحقول من نوع file
            */

            if (el.type === "file") {

                return (
                    el.files &&
                    el.files.length > 0
                );

            }


            /*
            | باقي الحقول
            */

            return el.value.trim() !== "";

        }
    );


/*
|==========================================================
| إذا اكتملت الحقول المطلوبة
| إظهار القسم التالي
|==========================================================
*/

if (
    filled &&
    sections[index + 1]
) {

    const nextSection =
        sections[index + 1];


    nextSection.classList.remove(
        "d-none"
    );


    /*
    |======================================================
    | تحديث حجم الخريطة بعد إظهار قسم الموقع
    |======================================================
    */

    if (
        mapElement &&
        nextSection.contains(mapElement)
    ) {

        setTimeout(
            function () {

                if (
                    typeof MapManager !== "undefined" &&
                    MapManager.refreshSize
                ) {

                    MapManager.refreshSize();

                }

            },
            200
        );

    }

}

}


/*
|==========================================================
| الحقول النصية والاختيارات
|==========================================================
*/

document.addEventListener(
"input",
function () {

    sections.forEach(
        function (section, index) {

            if (
                section.classList.contains("d-none")
            ) {

                return;

            }

            checkSection(section, index);

        }
    );

}
);


/*
|==========================================================
| الملفات و Select
|==========================================================
*/

document.addEventListener(
"change",
function () {

    sections.forEach(
        function (section, index) {

            if (
                section.classList.contains("d-none")
            ) {

                return;

            }

            checkSection(section, index);

        }
    );

}
);

            }
        );

    </script>

</div>

@endsection

