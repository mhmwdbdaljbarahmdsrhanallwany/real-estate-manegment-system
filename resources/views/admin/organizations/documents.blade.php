@extends('admin.layout.master')
<link rel="stylesheet" href="{{ asset('assets/agency/notes/notes.css') }}">
@section('title')
    مستندات توثيق المؤسسة
@endsection

@section('homepageurl')
    المؤسسات العقارية
@endsection
@section('urlpagebranch')
    مستندات المؤسسة
@endsection

@section('contect')
    @include('admin.incloude.agencynavepare')

    <x-admin-notice title="تنبيه اداري">
        <ul>
            <li>
                تبقى المؤسسات قيد المراجعة حتى يتم التحقق من جميع الوثائق والمستندات الرسمية،
                والتأكد من امتثالها الكامل للأنظمة واللوائح القانونية المعتمدة.
            </li>

            <li>
                يتم تحديث حالة المؤسسة بشكل دوري وفق نتائج التدقيق والمراجعة الداخلية.
            </li>
            <li>
                لا يُسمح بإضافة أي اشتراك للمؤسسة وهي قيد المراجعة، حتى يتم التحقق من المستندات واعتمادها وتفعيل حالة
                المؤسسة بشكل رسمي.
            </li>
        </ul>
    </x-admin-notice>
    <x-constructor-table title="مستندات المؤسسات" icon="fas fa-file-alt" :filters="[
        'searchName' => 'بحث باسم المؤسسة.....',
        'statusFilter' => [
            'all' => 'كل الحالات',
            'pending_approval' => 'قيد الموافقة',
            'active' => 'نشطة',
            'suspended' => 'موقوفة',
            'blocked' => 'محظورة',
            'expired' => 'منتهية',
        ],
    ]" :sorts="[
        'sortBy' => [
            'organization_id' => 'المعرف',
            'name' => 'اسم المؤسسة',
        ],
        'sort' => [
            'asc' => 'تصاعدي',
            'desc' => 'تنازلي',
        ],
    ]"
        tableid="ajax_responce" :columns="[
            'المعرف',
            'اسم المؤسسة',
            'السجل التجاري',
            'الرقم الضريبي',
            'رخصة البلدية',
            'رخصة الوساطة العقارية',
            'الحالة',
        ]" :files="[
            'organization_id' => [
                'type' => 'text',
                'name' => 'organization_id',
            ],
        
            'name' => [
                'type' => 'text',
                'name' => 'name',
            ],
        
            'commercial_registration' => [
                'type' => 'text',
                'name' => 'commercial_registration',
            ],
        
            'tax_number' => [
                'type' => 'text',
                'name' => 'tax_number',
            ],
        
            'municipality_license' => [
                'type' => 'file',
                'name' => 'municipality_license',
            ],
        
            'real_estate_broker_license' => [
                'type' => 'file',
                'name' => 'real_estate_broker_license',
            ],
        
            'status' => [
                'type' => 'text',
                'name' => 'status_label',
            ],
        ]" :rows="$organizationDocuments" :actions="[
            'type' => 'admin',
        
            'active' => [
                'status' => 'active',
                'title' => 'تفعيل',
                'class' => 'approve',
            ],
        
            'pending_approval' => [
                'status' => 'pending_approval',
                'title' => 'إعادة المراجعة',
                'class' => 'review',
            ],
        
            'suspended' => [
                'status' => 'suspended',
                'title' => 'تعليق',
                'class' => 'suspend',
            ],
        
            'blocked' => [
                'status' => 'blocked',
                'title' => 'حظر',
                'class' => 'blocked',
            ],
        
            'expired' => [
                'status' => 'expired',
                'title' => 'انتهاء',
                'class' => 'expired',
            ],
        ]" :first-item="$organizationDocuments->firstItem()"
        :last-item="$organizationDocuments->lastItem()" :total="$organizationDocuments->total()" :pagination="$organizationDocuments->links()" />
@section('script')
    <script>
        $(document).ready(function() {
            loadData('{{ route('admin.organizations.ajax_search_documents') }}');

            function loadData(url) {

                let filters = {};

                $('.filter-input').each(function() {

                    let name = $(this).attr('name');
                    let value = $(this).val();

                    filters[name] = value;

                });

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        ...filters
                    },
                    success: function(organizationDocuments) {
                        $("#ajax_responce").html(organizationDocuments);
                    },
                    error: function() {
                        alert('حدث خطأ أثناء جلب البيانات');
                    }
                });

            }
            $(document).on('input change', '.filter-input', function() {

                loadData('{{ route('admin.organizations.ajax_search_documents') }}');

            });
            $(document).on('click', '.pagination a', function(e) {

                e.preventDefault();

                let url = $(this).attr('href');

                loadData(url);

            });
            $(document).on('click', '.change-status', function() {
                let button = $(this);
                let organization_id = button.data('id');
                let status = button.data('status');
                $.ajax({
                    url: '{{ route('admin.organizations.change_status') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        organization_id: organization_id,
                        status: status
                    },
                    beforeSend: function() {
                        button.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'نجاح',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                        loadData('{{ route('admin.organizations.ajax_search_documents') }}');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'المستندات غير مكتملة',
                                text: xhr.responseJSON.message,
                                confirmButtonText: 'حسنًا'
                            });
                            return;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: 'حدث خطأ أثناء تحديث حالة المؤسسة.',
                            confirmButtonText: 'حسنًا'
                        });
                    },
                    complete: function() {
                        button.prop('disabled', false);
                    }
                });
            });
            //  زر التحديث
            $(document).on('click', '#refreshBtn', function() {
                let icon = $(this).find('i');

                icon.addClass('fa-spin');

                loadData('{{ route('admin.organizations.ajax_search_documents') }}');

                setTimeout(() => {
                    icon.removeClass('fa-spin');
                }, 600);
            });

            $(function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

        });
    </script>
@endsection
@endsection
