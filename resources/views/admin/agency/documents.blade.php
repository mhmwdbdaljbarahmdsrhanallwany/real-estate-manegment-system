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






    <x-constructor-table title="  مستندات المؤسسات" icon="fas fa-file-alt" :filters="[
        'searchagency_name' => 'بحث باسم المؤسسة.....',
        'statusFilter' => [
            'all' => 'كل الحالات',
            'pending_approval' => 'قيدالموافقة',
            'active' => 'نشط',
            'suspended' => 'موقف',
        ],
    ]" :sorts="[
        'sortBy' => ['agency_id' => 'المعرف', 'agency_name' => 'اسم المؤسسة'],
        'sort' => ['asc' => 'تصاعدي', 'desc' => 'تنازلي'],
    ]"
        tableid="ajax_responce" :columns="[
            'المعرف',
            'اسم المؤسسة',
            'السجل التجاري',
            'الرقم الضريبي',
            'رخصة البلدية ',
            'رخصة الوساطة العقارية',
            'الحالة',
        ]" :files="[
            'agency_id' => [
                'type' => 'text',
                'name' => 'agency_id',
            ],
            'agency_name' => [
                'type' => 'text',
                'name' => 'agency_name',
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
        ]" :rows="$agencyDocument" :actions="[
            'type' => 'admin',
            'active' => [
                'status' => 'active',
                'title' => 'تفعيل',
                'class' => 'approve',
            ],
            'pending_approval' => [
                'status' => 'pending_approval',
                'title' => 'اعادة المراجة',
                'class' => 'review',
            ],
            'suspended' => [
                'status' => 'suspended',
                'title' => 'تعليق',
                'class' => 'suspend',
            ],
        ]" :first-item="$agencyDocument->firstItem()"
        :last-item="$agencyDocument->lastItem()" :total="$agencyDocument->total()" :pagination="$agencyDocument->links()" />

@section('script')
    <script>
        $(document).ready(function() {
            loadData('{{ route('admin.agencies.ajax_search_document') }}');

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
                    success: function(agencyDocument) {
                        $("#ajax_responce").html(agencyDocument);
                    },
                    error: function() {
                        alert('حدث خطأ أثناء جلب البيانات');
                    }
                });

            }
            $(document).on('input change', '.filter-input', function() {

                loadData('{{ route('admin.agencies.ajax_search_document') }}');

            });
            $(document).on('click', '.pagination a', function(e) {

                e.preventDefault();

                let url = $(this).attr('href');

                loadData(url);

            });
            $(document).on('click', '.change-status', function() {

                let button = $(this);

                let agency_id = button.data('id');

                let status = button.data('status');

                $.ajax({

                    url: '{{ route('admin.agencies.change_status') }}',

                    type: 'POST',

                    data: {

                        _token: '{{ csrf_token() }}',

                        agency_id: agency_id,

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
                        loadData('{{ route('admin.agencies.ajax_search_document') }}');

                    },
                    error: function() {

                        alert('حدث خطأ أثناء تحديث الحالة');
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

                loadData('{{ route('admin.agencies.ajax_search_document') }}');

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
