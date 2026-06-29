@extends('admin.layout.master')
@section('title')
    اضافة بيانات مؤسسة
@endsection

@section('contect')
    @include('admin.incloude.agencynavepare')

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        @if ($errors->any())
            @section('script')
                <script>
                    toastr.error("{{ $errors->first() }}");
                </script>
            @endsection
        @endif



        <!-- الهيدر -->
        <div class="card-header bg-light">
            <h4 class="mb-0 fw-bold text-primary">
                <i class="fas fa-building me-2"></i> إضافة مؤسسة جديدة
            </h4>
        </div>
        <form id="agencyForm" enctype="multipart/form-data" method="POST" action="{{ route('admin.agencies.store') }}">
            @csrf

            <!-- ================== القسم 1 ================== -->
            <div class="form-section mb-4" style="margin-right: 20px;margin-top: 20px;margin-left: 20px;">
                <h5 class="mb-3 text-primary">بيانات المؤسسة</h5>
                <div class="row g-3">
                    <x-constructor-form :types="[
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
                        'municipality_license' => [
                            'label' => ' رخصة البلدية ',
                            'type' => 'file',
                            'name' => 'municipality_license[]',
                            'multiple' => 'multiple',
                        ],
                        'real_estate_broker_license' => [
                            'label' => ' رخصة الوساطة العقارية ',
                            'type' => 'file',
                            'name' => 'real_estate_broker_license[]',
                            'multiple' => 'multiple',
                        ],
                        'documents_path' => [
                            'label' => 'مستندات المؤسسة ',
                            'type' => 'file',
                            'name' => 'documents_path[]',
                            'multiple' => 'multiple',
                        ],
                    ]" />





                </div>
            </div>

            <!-- ================== القسم 2 ================== -->
            <div class="form-section d-none mb-4" style="margin-right: 20px;margin-left: 20px;">
                <h5 class="mb-3 text-success">بيانات التواصل</h5>
                <div class="row g-3">
                    <x-constructor-form :types="[
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
            </div>

            <!-- ================== القسم 3 ================== -->
            <div class="form-section d-none mb-4" style="margin-right: 20px;margin-left: 20px;">
                <h5 class="mb-3 text-warning">البيانات البنكية</h5>
                <div class="row g-3">


                    <x-constructor-form :types="[
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

                    <!-- ================== رفع المستندات ================== -->
                </div>
            </div>


            <!-- زر الحفظ -->
            <div class="text-end mt-4" style="margin-right: 20px;margin-bottom: 10px;">
                <button type="submit" class="btn btn-primary px-3">اضافة مؤسسة</button>
            </div>
        </form>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const sections = document.querySelectorAll(".form-section");

                document.addEventListener("input", function() {
                    sections.forEach((section, index) => {
                        // إذا كان القسم ظاهر
                        if (!section.classList.contains("d-none")) {
                            // اجمع كل الحقول داخل القسم
                            let inputs = section.querySelectorAll("input, select");
                            // تحقق أن كل الحقول ممتلئة
                            let filled = Array.from(inputs).every(el => el.value.trim() !== "");

                            // إذا امتلأت الحقول، أظهر القسم التالي
                            if (filled && sections[index + 1]) {
                                sections[index + 1].classList.remove("d-none");
                            }
                        }
                    });
                });
            });
        </script>


    @endsection
