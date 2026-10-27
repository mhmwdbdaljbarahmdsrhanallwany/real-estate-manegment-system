@php
    $subscription = $organization->subscriptions->last();
    $package = $subscription?->package;
    
    /*
    |--------------------------------------------------------------------------
    | الاستخدام الحالي
    |--------------------------------------------------------------------------
    */
    $currentUsers = 0;
    $currentBranches = 0;
    $currentProperties = 0;

    /*
    |--------------------------------------------------------------------------
    | حدود الباقة
    |--------------------------------------------------------------------------
    */
    $maxUsers = $package->max_users ?? 0;
    $maxBranches = $package->max_branches ?? 0;
    $maxProperties = $package->max_properties ?? 0;

    /*
    |--------------------------------------------------------------------------
    | نسب الاستخدام
    |--------------------------------------------------------------------------
    */
    $usersPercentage = $maxUsers > 0 ? min(100, ($currentUsers / $maxUsers) * 100) : 0;
    $branchesPercentage = $maxBranches > 0 ? min(100, ($currentBranches / $maxBranches) * 100) : 0;
    $propertiesPercentage = $maxProperties > 0 ? min(100, ($currentProperties / $maxProperties) * 100) : 0;
    $statusLabel = $statusLabels[$organization->status] ?? $organization->status ?? '-';
@endphp

{{-- ستايلات مخصصة لتحسين مظهر الكروت والعناصر --}}
<style>
    .custom-card {
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 0 0.125rem 0.25rem rgba(11, 19, 43, 0.075) !important;
        transition: all 0.2s ease-in-out;
        background: #ffffff;
    }
    .custom-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(11, 19, 43, 0.05) !important;
    }
    .custom-card .card-header {
        background-color: transparent !important;
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 1rem 1.25rem;
    }
    .data-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        height: 100%;
    }
</style>

{{-- ===================================================================== --}}
{{-- الصف الأول: البيانات الأساسية + الاستخدام --}}
{{-- ===================================================================== --}}
<div class="row">
    {{-- البيانات الأساسية والتجارية --}}
    <div class="col-lg-7 mb-4">
        <div class="card custom-card h-100">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-building text-primary ml-2 fa-lg"></i>
                <span class="font-weight-bold" style="color: #0B132B;">
                    البيانات الأساسية والتجارية
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $basicData = [
                            'اسم المؤسسة' => $organization->name,
                            'الاسم التجاري' => $organization->commercial_name,
                            'السجل التجاري' => $organization->commercial_registration,
                            'الرقم الضريبي' => $organization->tax_number,
                            'الرقم الوطني الموحد' => $organization->unified_national_number,
                        ];
                    @endphp
                    @foreach ($basicData as $label => $value)
                        <div class="col-md-6 mb-3">
                            <div class="data-item">
                                <div class="small text-muted mb-1">
                                    {{ $label }}
                                </div>
                                <div class="font-weight-bold" style="color: #0B132B;">
                                    {{ $value ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ملخص الاستخدام --}}
    <div class="col-lg-5 mb-4">
        <div class="card custom-card h-100">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-chart-line text-success ml-2 fa-lg"></i>
                <span class="font-weight-bold" style="color: #0B132B;">
                    الاستخدام
                </span>
            </div>
            <div class="card-body d-flex flex-column justify-content-around">
                {{-- المستخدمون --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted font-weight-bold">المستخدمون</span>
                        <strong style="color: #0B132B;">{{ $currentUsers }} / {{ $maxUsers }}</strong>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 4px; background-color: #f1f5f9;">
                        <div class="progress-bar bg-primary" style="width: {{ $usersPercentage }}%; border-radius: 4px;"></div>
                    </div>
                </div>
                {{-- الفروع --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted font-weight-bold">الفروع</span>
                        <strong style="color: #0B132B;">{{ $currentBranches }} / {{ $maxBranches }}</strong>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 4px; background-color: #f1f5f9;">
                        <div class="progress-bar bg-success" style="width: {{ $branchesPercentage }}%; border-radius: 4px;"></div>
                    </div>
                </div>
                {{-- العقارات --}}
                <div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted font-weight-bold">العقارات</span>
                        <strong style="color: #0B132B;">{{ $currentProperties }} / {{ $maxProperties }}</strong>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 4px; background-color: #f1f5f9;">
                        <div class="progress-bar bg-warning" style="width: {{ $propertiesPercentage }}%; border-radius: 4px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================================================================== --}}
{{-- بيانات التواصل --}}
{{-- ===================================================================== --}}
<div class="row">
    <div class="col-12 mb-4">
        <div class="card custom-card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-address-book text-info ml-2 fa-lg"></i>
                <span class="font-weight-bold" style="color: #0B132B;">
                    بيانات التواصل
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $contactData = [
                            'الهاتف' => $organization->phone,
                            'الجوال' => $organization->mobile,
                            'البريد الإلكتروني الرسمي' => $organization->email,
                            'الموقع الإلكتروني' => $organization->website,
                        ];
                    @endphp
                    @foreach ($contactData as $label => $value)
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="data-item">
                                <div class="small text-muted mb-1">
                                    {{ $label }}
                                </div>
                                @if($label === 'الموقع الإلكتروني' && $value)
                                    <a href="{{ $value }}" target="_blank" rel="noopener noreferrer" class="font-weight-bold text-primary text-truncate d-block">
                                        {{ $value }}
                                    </a>
                                @else
                                    <div class="font-weight-bold text-truncate" style="color: #0B132B;" title="{{ $value ?? '-' }}">
                                        {{ $value ?? '-' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================================================================== --}}
{{-- العنوان والموقع والخريطة --}}
{{-- ===================================================================== --}}
<div class="row">
    {{-- العنوان --}}
    <div class="col-lg-5 mb-4">
        <div class="card custom-card h-100">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-map-marker-alt text-danger ml-2 fa-lg"></i>
                <span class="font-weight-bold" style="color: #0B132B;">
                    العنوان
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $addressData = [
                            'الدولة' => $organization->country,
                            'المحافظة / المنطقة' => $organization->state,
                            'المدينة' => $organization->city,
                            'الحي' => $organization->district,
                            'الشارع' => $organization->street,
                            'الرمز البريدي' => $organization->postal_code,
                            'صندوق البريد' => $organization->po_box,
                            'العنوان التفصيلي' => $organization->address,
                        ];
                    @endphp
                    @foreach ($addressData as $label => $value)
                        <div class="col-md-6 mb-3">
                            <div class="data-item">
                                <div class="small text-muted mb-1">
                                    {{ $label }}
                                </div>
                                <div class="font-weight-bold" style="color: #0B132B;">
                                    {{ $value ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
   
    {{-- الخريطة --}}
    <div class="col-lg-7 mb-4">
        <div class="card custom-card h-100">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-map text-danger ml-2 fa-lg"></i>
                <span class="font-weight-bold" style="color: #0B132B;">
                    موقع المؤسسة على الخريطة
                </span>
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
                @if($organization->latitude !== null && $organization->longitude !== null)
                    <div id="organization-info-map" style="height: 300px; width: 100%; border-radius: 8px; border: 1px solid #e2e8f0;"></div>
                    <div class="row mt-3 text-center">
                        <div class="col-md-4 mb-2">
                            <div class="small text-muted">خط العرض</div>
                            <div class="font-weight-bold" style="color: #0B132B;">{{ $organization->latitude }}</div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="small text-muted">خط الطول</div>
                            <div class="font-weight-bold" style="color: #0B132B;">{{ $organization->longitude }}</div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="small text-muted">رابط الخريطة</div>
                            @if($organization->map_url)
                                <a href="{{ $organization->map_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mt-1 px-3">
                                    <i class="fas fa-external-link-alt ml-1"></i> فتح الموقع
                                </a>
                            @else
                                <div class="font-weight-bold">-</div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-5 my-auto">
                        <i class="fas fa-map-marker-alt fa-3x mb-3 text-secondary"></i>
                        <div class="font-weight-bold">لم يتم تحديد موقع المؤسسة بعد.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ===================================================================== --}}
{{-- البيانات القانونية --}}
{{-- ===================================================================== --}}
<div class="row">
    <div class="col-12 mb-4">
        <div class="card custom-card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-balance-scale text-secondary ml-2 fa-lg"></i>
                <span class="font-weight-bold" style="color: #0B132B;">
                    البيانات القانونية
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $legalData = [
                            'اسم الممثل القانوني' => $organization->legal_representative_name,
                            'رقم هوية الممثل القانوني' => $organization->legal_representative_id,
                            'جوال الممثل القانوني' => $organization->legal_representative_phone,
                        ];
                    @endphp
                    @foreach ($legalData as $label => $value)
                        <div class="col-md-4 mb-3">
                            <div class="data-item">
                                <div class="small text-muted mb-1">
                                    {{ $label }}
                                </div>
                                <div class="font-weight-bold" style="color: #0B132B;">
                                    {{ $value ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================================================================== --}}
{{-- البيانات البنكية --}}
{{-- ===================================================================== --}}
<div class="row">
    <div class="col-12 mb-4">
        <div class="card custom-card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-university text-primary ml-2 fa-lg"></i>
                <span class="font-weight-bold" style="color: #0B132B;">
                    البيانات البنكية
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $bankData = [
                            'اسم البنك' => $organization->bank_name,
                            'اسم صاحب الحساب' => $organization->bank_account_name,
                            'رقم الحساب البنكي' => $organization->bank_account_number,
                            'الآيبان' => $organization->bank_iban,
                        ];
                    @endphp
                    @foreach ($bankData as $label => $value)
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="data-item">
                                <div class="small text-muted mb-1">
                                    {{ $label }}
                                </div>
                                <div class="font-weight-bold text-truncate" style="color: #0B132B;" title="{{ $value ?? '-' }}">
                                    {{ $value ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================================================================== --}}
{{-- بيانات النظام --}}
{{-- ===================================================================== --}}
<div class="row">
    <div class="col-12 mb-4">
        <div class="card custom-card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-cogs text-dark ml-2 fa-lg"></i>
                <span class="font-weight-bold" style="color: #0B132B;">
                    بيانات النظام
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="data-item">
                            <div class="small text-muted mb-1">واجهة API</div>
                            <div class="font-weight-bold">
                                @if($organization->api_enabled)
                                    <span class="badge badge-success px-2 py-1">مفعلة</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1">غير مفعلة</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="data-item">
                            <div class="small text-muted mb-1">تاريخ الإضافة</div>
                            <div class="font-weight-bold" style="color: #0B132B;">
                                {{ $organization->created_at?->format('Y-m-d H:i') ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="data-item">
                            <div class="small text-muted mb-1">آخر تحديث</div>
                            <div class="font-weight-bold" style="color: #0B132B;">
                                {{ $organization->updated_at?->format('Y-m-d H:i') ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
                @if($organization->notes)
                    <div class="mt-2">
                        <div class="data-item">
                            <div class="small text-muted mb-1">ملاحظات</div>
                            <div class="font-weight-bold" style="color: #0B132B;">
                                {{ $organization->notes }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ===================================================================== --}}
{{-- Leaflet Script & Styles --}}
{{-- ===================================================================== --}}
@if($organization->latitude !== null && $organization->longitude !== null)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const latitude = {{ $organization->latitude }};
            const longitude = {{ $organization->longitude }};
            const map = L.map("organization-info-map").setView([latitude, longitude], 15);
            
            L.tileLayer(
                "https://api.maptiler.com/maps/streets-v4/{z}/{x}/{y}.png?key={{ config('services.maptiler.api_key') }}",
                {
                    tileSize: 512,
                    zoomOffset: -1,
                    minZoom: 1,
                    attribution: '&copy; <a href="https://www.maptiler.com/copyright/" target="_blank">MapTiler</a> &copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap contributors</a>',
                    crossOrigin: true
                }
            ).addTo(map);

            L.marker([latitude, longitude])
                .addTo(map)
                .bindPopup(@json($organization->name));

            setTimeout(function () {
                map.invalidateSize();
            }, 200);
        });
    </script>
@endif