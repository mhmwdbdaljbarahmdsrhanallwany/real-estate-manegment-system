
@extends('admin.layout.master')

@section('title')
تعديل بيانات مؤسسة
@endsection

@section('contect')

@include('admin.incloude.agencynavepare')

@if ($errors->any())
    @section('script')
        <script>
            toastr.error("{{ $errors->first() }}");
        </script>
    @endsection
@endif

<div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="margin-top: 10px">
    {{-- =============================================================== --}}
    {{-- الهيدر --}}
    {{-- =============================================================== --}}
    <div class="card-header bg-light">
        <h4 class="mb-0 fw-bold text-primary">
            <i class="fas fa-building me-2"></i>
            تعديل مؤسسة {{ $data['name'] }}
        </h4>
    </div>
    {{-- =============================================================== --}}
    {{-- شريط التبويبات --}}
    {{-- =============================================================== --}}
    <ul class="nav nav-tabs" id="formTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#section1">
                بيانات المؤسسة
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#section2">
                بيانات التواصل
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#section3">
                البيانات البنكية
            </a>
        </li>
    </ul>
    {{-- =============================================================== --}}
    {{-- النموذج --}}
    {{-- =============================================================== --}}
    <form
        id="agencyForm"
        enctype="multipart/form-data"
        method="POST"
        action="{{ route('admin.organizations.update', $data['organization_id']) }}">
        @csrf
        @method('PUT')
        <div class="tab-content p-3">
            {{-- ======================================================= --}}
            {{-- القسم الأول --}}
            {{-- ======================================================= --}}
            <div class="tab-pane fade show active" id="section1">
                <h5 class="mb-3 text-primary">
                    بيانات المؤسسة
                </h5>
                <div class="row g-3">
                    <x-constructor-form
                        :rows="$data"
                        :types="[
                            'name' => [
                                'label' => 'اسم المؤسسة',
                                'name' => 'name',
                            ],
                            'commercial_name' => [
                                'label' => 'الاسم التجاري',
                                'name' => 'commercial_name',
                            ],
                            'commercial_registration' => [
                                'label' => 'السجل التجاري',
                                'type' => 'text',
                                'name' => 'commercial_registration',
                            ],
                            'tax_number' => [
                                'label' => 'الرقم الضريبي',
                                'type' => 'text',
                                'name' => 'tax_number',
                            ],
                            'unified_national_number' => [
                                'label' => 'الرقم الوطني الموحد',
                                'type' => 'text',
                                'name' => 'unified_national_number',
                            ],
                            'phone' => [
                                'label' => 'الهاتف',
                                'type' => 'text',
                                'name' => 'phone',
                            ],
                            'mobile' => [
                                'label' => 'الجوال',
                                'type' => 'text',
                                'name' => 'mobile',
                            ],
                        ]"
                    />
                    {{-- ================================================= --}}
                    {{-- شعار المؤسسة --}}
                    {{-- ================================================= --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-image text-info me-1"></i>
                            شعار المؤسسة
                        </label>
                        <input type="file" name="logo" class="form-control">
                        @if(!empty($data->logo))
                            <div class="file-box mt-2">
                                <img src="{{ asset('storage/'.$data->logo) }}" width="100" class="img-thumbnail mb-2">
                                <br>
                                <a href="{{ asset('storage/'.$data->logo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    عرض
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger deleteFileBtn"data-url="{{ route('admin.organizations.deleteFile', 
                                ['id' => $data->organization_id,'field' => 'logo']) }}">
                                    حذف
                                </button>
                            </div>
                        @endif
                    </div>
                    {{-- ================================================= --}}
                    {{-- رخصة البلدية --}}
                    {{-- ================================================= --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-city text-primary me-1"></i>
                            رخصة البلدية
                        </label>
                        <input type="file" name="municipality_license" class="form-control">
                        @if(!empty($data->municipality_license))
                            <div class="file-box mt-2">
                                <a href="{{ asset('storage/'.$data->municipality_license) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    عرض
                                </a>
                                <button type="button"class="btn btn-sm btn-outline-danger deleteFileBtn"
                                    data-url="{{ route('admin.organizations.deleteFile', [
                                        'id' => $data->organization_id,
                                        'field' => 'municipality_license']) }}">
                                    حذف
                                </button>
                            </div>
                        @else
                            <p class="text-muted mt-2">
                                لا توجد رخصة بلدية
                            </p>
                        @endif
                    </div>
                    {{-- ================================================= --}}
                    {{-- رخصة الوساطة العقارية --}}
                    {{-- ================================================= --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-user-tie text-success me-1"></i>
                            رخصة الوساطة العقارية
                        </label>
                        <input type="file" name="real_estate_broker_license" class="form-control">
                        @if(!empty($data->real_estate_broker_license))
                            <div class="file-box mt-2">
                                <a href="{{ asset('storage/'.$data->real_estate_broker_license) }}"
                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                    عرض
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger deleteFileBtn"
                                    data-url="{{ route('admin.organizations.deleteFile', [
                                        'id' => $data->organization_id,
                                        'field' => 'real_estate_broker_license']) }}">
                                    حذف
                                </button>
                            </div>
                        @else
                            <p class="text-muted mt-2">
                                لا توجد رخصة وساطة عقارية
                            </p>
                        @endif
                    </div>
                </div>
                {{-- زر التالي --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-outline-primary next-btn" data-next="#section2">
                        التالي
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>
            {{-- ======================================================= --}}
            {{-- القسم الثاني --}}
            {{-- ======================================================= --}}
            <div class="tab-pane fade" id="section2">
                <h5 class="mb-3 text-success">
                    بيانات التواصل
                </h5>
                <div class="row g-3">
                    <x-constructor-form
                        :rows="$data"
                        :types="[
                            'email' => [
                                'label' => 'البريد الإلكتروني',
                                'type' => 'email',
                                'name' => 'email',
                            ],
                            'website' => [
                                'label' => 'الموقع الإلكتروني',
                                'type' => 'url',
                                'name' => 'website',
                            ],
                            'country' => [
                                'label' => 'الدولة',
                                'name' => 'country',
                            ],
                            'state' => [
                                'label' => 'المحافظة / المنطقة',
                                'name' => 'state',
                            ],
                            'city' => [
                                'label' => 'المدينة',
                                'name' => 'city',
                            ],
                            'district' => [
                                'label' => 'الحي',
                                'name' => 'district',
                            ],
                            'street' => [
                                'label' => 'الشارع',
                                'name' => 'street',
                            ],
                            'address' => [
                                'label' => 'العنوان التفصيلي',
                                'name' => 'address',
                            ],
                            'postal_code' => [
                                'label' => 'الرمز البريدي',
                                'type' => 'text',
                                'name' => 'postal_code',
                            ],
                            'po_box' => [
                                'label' => 'صندوق البريد',
                                'type' => 'text',
                                'name' => 'po_box',
                            ],
                            'legal_representative_name' => [
                                'label' => 'اسم الممثل القانوني',
                                'name' => 'legal_representative_name',
                            ],
                            'legal_representative_id' => [
                                'label' => 'رقم هوية الممثل القانوني',
                                'type' => 'text',
                                'name' => 'legal_representative_id',
                            ],
                            'legal_representative_phone' => [
                                'label' => 'هاتف الممثل القانوني',
                                'type' => 'text',
                                'name' => 'legal_representative_phone',
                            ],
                        ]"
                    />
                </div>
                {{-- ================================================= --}}
                {{-- خريطة المؤسسة --}}
                {{-- ================================================= --}}
                <div class="mt-4">
                    <h6 class="mb-3 text-primary">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        موقع المؤسسة
                    </h6>
                    <div id="organization-map" style="height: 400px; width: 100%;border-radius: 8px;"></div>
                    {{-- ================================================= --}}
                    {{-- الإحداثيات --}}
                    {{-- ================================================= --}}
                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label for="latitude" class="form-label fw-semibold">
                                خط العرض
                            </label>
                            <input type="text" id="latitude" name="latitude" class="form-control"
                                value="{{ old('latitude', $data->latitude) }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="longitude" class="form-label fw-semibold">
                                خط الطول
                            </label>
                            <input type="text" id="longitude" name="longitude" class="form-control"value="{{ old('longitude', $data->longitude) }}"
                                readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="map_url" class="form-label fw-semibold">
                                رابط الموقع على الخريطة
                            </label>
                            <input type="text" id="map_url" name="map_url" class="form-control"value="{{ old('map_url', $data->map_url) }}" readonly>
                        </div>
                    </div>
                </div>
                {{-- ================================================ --}}
                {{-- أزرار التنقل --}}
                {{-- ================================================= --}}
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-btn" data-prev="#section1">
                        <i class="fas fa-arrow-right"></i>
                        السابق
                    </button>
                    <button type="button" class="btn btn-outline-primary next-btn" data-next="#section3">
                        التالي
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>
            {{-- ======================================================= --}}
            {{-- القسم الثالث --}}
            {{-- ======================================================= --}}
            <div class="tab-pane fade" id="section3">
                <h5 class="mb-3 text-warning">
                    البيانات البنكية
                </h5>
                <div class="row g-3">
                    <x-constructor-form
                        :rows="$data"
                        :types="[
                            'bank_name' => [
                                'label' => 'اسم البنك',
                                'name' => 'bank_name',
                            ],
                            'bank_account_name' => [
                                'label' => 'اسم صاحب الحساب',
                                'name' => 'bank_account_name',
                            ],
                            'bank_account_number' => [
                                'label' => 'رقم الحساب البنكي',
                                'type' => 'text',
                                'name' => 'bank_account_number',
                            ],
                            'bank_iban' => [
                                'label' => 'رقم الآيبان',
                                'type' => 'text',
                                'name' => 'bank_iban',
                            ],
                        ]"
                    />
                </div>
                {{-- ================================================= --}}
                {{-- أزرار التنقل والحفظ --}}
                {{-- ================================================= --}}
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-btn" data-prev="#section2">
                        <i class="fas fa-arrow-right"></i>
                        السابق
                    </button>
                    <x-button :save="true" text="تحديث المؤسسة"/>
                </div>
            </div>
        </div>
    </form>
</div>


{{-- ================================================================= --}}
{{-- Leaflet CSS --}}
{{-- ================================================================= --}}

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

{{-- ================================================================= --}}
{{-- CSS الخاص بالخريطة --}}
{{-- ================================================================= --}}
<link rel="stylesheet" href="{{ asset('assets/css/maps/map.css') }}"/>

{{-- ================================================================= --}}
{{-- Leaflet JS --}}
{{-- ================================================================= --}}
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
</script>
{{-- ================================================================= --}}
{{-- MapManager --}}
{{-- ================================================================= --}}
<script
    src="{{ asset('assets/css/maps/map.js') }}">
</script>
{{-- ================================================================= --}}
{{-- تهيئة الخريطة --}}
{{-- ================================================================= --}}
<script>
    document.addEventListener(
        "DOMContentLoaded",
        function () {
            let organizationMapManager = null;
            /*
            |--------------------------------------------------------------------------
            | تهيئة MapManager مرة واحدة فقط
            |--------------------------------------------------------------------------
            */
            if (
                typeof MapManager !== "undefined" &&
                document.getElementById("organization-map")
            ) {
                organizationMapManager = MapManager.init({
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
            |--------------------------------------------------------------------------
            | إعادة حساب حجم الخريطة عند فتح التبويب
            |--------------------------------------------------------------------------
            */
            document.querySelectorAll(
                'a[data-bs-toggle="tab"]'
            ).forEach(
                function (tab) {
                    tab.addEventListener(
                        "shown.bs.tab",
                        function (event) {
                            const targetId =
                                event.target.getAttribute("href");
                            const targetSection =
                                document.querySelector(targetId);
                            const mapElement =
                                document.getElementById(
                                    "organization-map"
                                );
                            if (organizationMapManager && targetSection && mapElement && targetSection.contains(mapElement)
                            ) {
                                setTimeout(
                                    function () {
                                        organizationMapManager
                                            .refreshSize();
                                    },
                                    200
                                );
                            }
                        }
                    );
                }
            );
        }
    );

</script>

{{-- ================================================================= --}}
{{-- التنقل بين التبويبات --}}
{{-- ================================================================= --}}

<script>
    document.addEventListener(
        "DOMContentLoaded",
        function () {

            /*
            |--------------------------------------------------------------------------
            | إظهار تبويب معين
            |--------------------------------------------------------------------------
            */
            function showTab(targetId) {
                const tabTrigger =document.querySelector( `a[href="${targetId}"]`);
                if (tabTrigger) {
                    const tab =new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            }


            /*
            |--------------------------------------------------------------------------
            | أزرار التالي
            |--------------------------------------------------------------------------
            */
            document.querySelectorAll(
                ".next-btn"
            ).forEach(
                function (btn) {
                    btn.addEventListener(
                        "click",
                        function () {
                            const nextId =this.getAttribute( "data-next");
                            showTab( nextId);
                        }
                    );
                }
            );

            /*
            |--------------------------------------------------------------------------
            | أزرار السابق
            |--------------------------------------------------------------------------
            */
            document.querySelectorAll(".prev-btn").forEach(
                function (btn) {
                    btn.addEventListener(
                        "click",
                        function () {
                            const prevId =this.getAttribute("data-prev");
                            showTab(prevId);
                        }
                    );
                }
            );
        }
    );

</script>
{{-- ================================================================= --}}
{{-- حذف الملفات --}}
{{-- ================================================================= --}}
@section('script')
<script>
    $(document).on('click', '.deleteFileBtn', function (e) {
        e.preventDefault();
        let button = $(this);
        let url = button.data('url');
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'سيتم حذف الملف نهائيًا!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function () {
                    button.prop('disabled', true);
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم الحذف',
                        text: response.message
                            ?? 'تم حذف الملف بنجاح.',
                        confirmButtonText: 'حسنًا'
                    });

                    /*
                    |--------------------------------------------------------------------------
                    | إزالة الملف من الصفحة بعد نجاح الحذف
                    |--------------------------------------------------------------------------
                    */
                    button.closest('.file-box').remove();

                },
                error: function (xhr) {
                    let message = 'تعذر حذف الملف.';
                    /*
                    |--------------------------------------------------------------------------
                    | رسالة الخطأ القادمة من Backend
                    |--------------------------------------------------------------------------
                    */

                    if (xhr.responseJSON && xhr.responseJSON.message
                    ) {
                        message = xhr.responseJSON.message;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | أخطاء Laravel Validation
                    |--------------------------------------------------------------------------
                    */
                    else if (
                        xhr.responseJSON &&
                        xhr.responseJSON.errors
                    ) {

                        let errors = xhr.responseJSON.errors;
                        message = Object.values(errors)
                            .flat()
                            .join('\n');
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | عرض الرسالة حسب نوع الخطأ
                    |--------------------------------------------------------------------------
                    */
                    let icon = 'error';
                    let title = 'خطأ';
                    if (xhr.status === 422) {
                        icon = 'warning';
                        title = 'تعذر تنفيذ العملية';
                    } else if (xhr.status === 403) {
                        icon = 'error';
                        title = 'عملية غير مسموحة';
                    } else if (xhr.status === 404) {
                        icon = 'info';
                        title = 'الملف غير موجود';
                    } else if (xhr.status >= 500) {
                        icon = 'error';
                        title = 'خطأ في الخادم';
                    }
                    Swal.fire({
                        icon: icon,
                        title: title,
                        text: message,
                        confirmButtonText: 'حسنًا'
                    });
                    console.error(
                        '❌ خطأ في حذف الملف:',xhr
                    );
                },
                complete: function () {
                    button.prop('disabled', false);
                }
            });
        });
    });

</script>


@endsection


@endsection

