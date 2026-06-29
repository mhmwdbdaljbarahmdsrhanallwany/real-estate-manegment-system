@extends('admin.layout.master')
@section('title')
    اضافة بيانات مؤسسة
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
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-light">
            <h4 class="mb-0 fw-bold text-primary">
                <i class="fas fa-building me-2"></i> تعديل مؤسسة {{ $data['agency_name'] }}
            </h4>
        </div>




        <!-- شريط التبويبات -->
        <ul class="nav nav-tabs" id="formTabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#section1">بيانات المؤسسة</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#section2">بيانات التواصل</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#section3">البيانات البنكية</a></li>
        </ul>

        <form id="agencyForm" enctype="multipart/form-data" method="POST"
            action="{{ route('admin.agencies.update', $data['agency_id']) }}">
            @csrf
            @method('PUT')

            <div class="tab-content p-3">
                <!-- القسم 1: بيانات المؤسسة -->
                <div class="tab-pane fade show active" id="section1">
                    <h5 class="mb-3 text-primary">بيانات المؤسسة</h5>
                    <div class="row g-3">
                        <!-- الحقول الأساسية -->
                        <x-constructor-form :rows="$data" :types="[
                            'agency_name' => [
                                'label' => 'اسم المؤسسة',
                                'name' => 'agency_name',
                            ],
                            'commercial_registration' => [
                                'label' => 'السجل التجاري',
                                'type' => 'text',
                                'name' => 'commercial_registration',
                            ],
                            'tax_number' => [
                                'label' => ' الرقم الضريبي',
                                'type' => 'text',
                                'name' => 'tax_number',
                            ],
                        
                            'unified_national_number' => [
                                'label' => ' الرقم الموحد',
                                'type' => 'text',
                                'name' => 'unified_national_number',
                            ],
                            'phone' => [
                                'label' => ' الهاتف ',
                                'type' => 'text',
                                'name' => 'phone',
                            ],
                            'mobile' => [
                                'label' => ' الجوال ',
                                'type' => 'text',
                                'name' => 'mobile',
                            ],
                        ]" />


                        <!-- رخصة البلدية -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><i class="fas fa-city text-primary me-1"></i> رخصة
                                البلدية</label>
                            <input type="file" name="municipality_license[]" class="form-control" multiple>
                            @if (!empty($data->municipality_license))
                                <ul class="list-group mt-2">
                                    @foreach ($data->municipality_license as $index => $file)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $file['original_name'] }}
                                            <div>
                                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary me-1">عرض</a>
                                                <a href="{{ asset('storage/' . $file['path']) }}" download
                                                    class="btn btn-sm btn-outline-success me-1">تحميل</a>
                                                <!-- زر الحذف -->
                                                <button type="button" class="btn btn-sm btn-outline-danger deleteFileBtn"
                                                    data-url="{{ route('admin.agencies.deleteFile', ['id' => $data->agency_id, 'field' => 'municipality_license', 'index' => $index]) }}">
                                                    حذف
                                                </button>



                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mt-3">لا توجد رخصة بلدية مرفوعة</p>
                            @endif
                        </div>

                        <!-- رخصة الوساطة العقارية -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><i class="fas fa-user-tie text-success me-1"></i> رخصة
                                الوساطة العقارية</label>
                            <input type="file" name="real_estate_broker_license[]" class="form-control" multiple>
                            @if (!empty($data->real_estate_broker_license))
                                <ul class="list-group mt-2">
                                    @foreach ($data->real_estate_broker_license as $index => $file)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $file['original_name'] }}
                                            <div>
                                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary me-1">عرض</a>
                                                <a href="{{ asset('storage/' . $file['path']) }}" download
                                                    class="btn btn-sm btn-outline-success me-1">تحميل</a>
                                                <button type="button" class="btn btn-sm btn-outline-danger deleteFileBtn"
                                                    data-url="{{ route('admin.agencies.deleteFile', ['id' => $data->agency_id, 'field' => 'real_estate_broker_license', 'index' => $index]) }}">
                                                    حذف
                                                </button>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mt-3">لا توجد رخصة وساطة عقارية مرفوعة</p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <h5 class="mb-3 text-info">المستندات العامة</h5>
                            <div class="col-12 mb-2">
                                <input type="file" name="documents_path[]" class="form-control" multiple>
                                <small class="text-muted">يمكنك رفع أكثر من ملف (PDF, صور, Word)</small>
                            </div>
                            @if (!empty($data->documents_path))
                                <ul class="list-group mt-3">
                                    @foreach ($data->documents_path as $index => $doc)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $doc['original_name'] }}
                                            <div>
                                                <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary me-1">عرض</a>
                                                <a href="{{ asset('storage/' . $doc['path']) }}" download
                                                    class="btn btn-sm btn-outline-success me-1">تحميل</a>
                                                <button type="button" class="btn btn-sm btn-outline-danger deleteFileBtn"
                                                    data-url="{{ route('admin.agencies.deleteFile', ['id' => $data->agency_id, 'field' => 'documents_path', 'index' => $index]) }}">
                                                    حذف
                                                </button>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mt-3">لا توجد مستندات عامة</p>
                            @endif
                        </div>

                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-outline-primary next-btn" data-next="#section2">التالي <i
                                class="fas fa-arrow-left"></i></button>
                    </div>
                </div>

                <!-- القسم 2: بيانات التواصل -->
                <div class="tab-pane fade" id="section2">
                    <h5 class="mb-3 text-success">بيانات التواصل</h5>
                    <div class="row g-3">
                        <x-constructor-form :rows="$data" :types="[
                            'email' => [
                                'label' => ' البريد الالكتروني',
                                'type' => 'email',
                                'name' => 'email',
                            ],
                            'website' => [
                                'label' => ' الموقع الالكتروني',
                                'type' => 'url',
                                'name' => 'website',
                            ],
                            'address' => [
                                'label' => 'العنوان',
                                'name' => 'address',
                            ],
                            'city' => [
                                'label' => 'المدينة',
                                'name' => 'city',
                            ],
                            'district' => [
                                'label' => ' الحي ',
                                'name' => 'district',
                            ],
                            'postal_code' => [
                                'label' => ' الرمز البريدي ',
                                'type' => 'text',
                                'name' => 'postal_code',
                            ],
                            'po_box' => [
                                'label' => 'صندوق البريد ',
                                'type' => 'text',
                                'name' => 'po_box',
                            ],
                            'postal_code' => [
                                'label' => ' الرمز البريدي ',
                                'type' => 'text',
                                'name' => 'postal_code',
                            ],
                            'legal_representative_name' => [
                                'label' => ' اسم الممثل القانوني ',
                                'name' => 'legal_representative_name',
                            ],
                            'legal_representative_id' => [
                                'label' => ' رقم هوية الممثل القانوني ',
                                'type' => 'text',
                                'name' => 'legal_representative_id',
                            ],
                            'legal_representative_phone' => [
                                'label' => '  هاتف الممثل القانوني ',
                                'type' => 'text',
                                'name' => 'legal_representative_phone',
                            ],
                        ]" />
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-outline-secondary prev-btn" data-prev="#section1"><i
                                class="fas fa-arrow-right"></i> السابق</button>
                        <button type="button" class="btn btn-outline-primary next-btn" data-next="#section3">التالي <i
                                class="fas fa-arrow-left"></i></button>
                    </div>
                </div>

                <!-- القسم 3: البيانات البنكية -->
                <div class="tab-pane fade" id="section3">
                    <h5 class="mb-3 text-warning">البيانات البنكية</h5>
                    <div class="row g-3">


                        <x-constructor-form :rows="$data" :types="[
                            'bank_name' => [
                                'label' => 'اسم البنك',
                                'name' => 'bank_name',
                            ],
                            'bank_account_name' => [
                                'label' => ' اسم صاحب الحساب',
                                'name' => 'bank_account_name',
                            ],
                            'bank_account_number' => [
                                'label' => 'رقم الحساب البنكي',
                                'type' => 'text',
                                'name' => 'bank_account_number',
                            ],
                        ]" />
                    </div>
                    < <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary prev-btn" data-prev="#section2">
                            <i class="fas fa-arrow-right"></i> السابق
                        </button>
                        <x-button :save='true' text='تحديث المؤسة' />
                </div>
            </div>



    </div>
    </form>
    </div>

    <!-- سكربت التنقل بين التبويبات -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // دالة إظهار التبويب المطلوب
            function showTab(targetId) {
                let tabTrigger = document.querySelector(`a[href="${targetId}"]`);
                if (tabTrigger) {
                    let tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            }

            // أزرار التالي
            document.querySelectorAll(".next-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    let nextId = this.getAttribute("data-next");
                    showTab(nextId);
                });
            });

            // أزرار السابق
            document.querySelectorAll(".prev-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    let prevId = this.getAttribute("data-prev");
                    showTab(prevId);
                });
            });
        });
    </script>
@section('script')
    <script>
        $(document).on('click', '.deleteFileBtn', function(e) {
            e.preventDefault();

            let url = $(this).data('url'); // الرابط الجاهز من الزر

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "سيتم حذف الملف نهائيًا!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST', // الراوت الآن POST
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الحذف',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            // إزالة العنصر من الصفحة مباشرة
                            $(e.target).closest('li').remove();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: (xhr.responseJSON && xhr.responseJSON.message) ?
                                    xhr.responseJSON.message : 'تعذر حذف الملف'
                            });
                            // وللتتبع أثناء التطوير
                            console.error('❌ خطأ في الحذف:', xhr);
                        }
                    });
                }
            });
        });
    </script>
@endsection
@endsection
