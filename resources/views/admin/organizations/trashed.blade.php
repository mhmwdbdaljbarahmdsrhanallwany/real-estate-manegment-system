@extends('admin.layout.master')

@section('title')
    المؤسسات المحذوفة
@endsection

@section('contect')

    @include('admin.incloude.agencynavepare')

    <x-constructor-table
    title="المؤسسات المحذوفة منطقيًا"
:entity="'المؤسسة'"
:entity-plural="'المؤسسات'"
    :filters="[
        'searchName' => 'بحث باسم المؤسسة...',
    ]"

    :sorts="[
        'sortBy' => [
            'organization_id' => 'المعرف',
            'name' => 'اسم المؤسسة',
            'deleted_at' => 'تاريخ الحذف',
        ],
        'sort' => [
            'asc' => 'تصاعدي',
            'desc' => 'تنازلي',
        ],
    ]"

    tableid="trashed_responce"

    :columns="[
        'المعرف',
        'اسم المؤسسة',
        'السجل التجاري',
        'الرقم الضريبي',
        'الجوال',
        'البريد الإلكتروني',
        'المدينة',
        'تاريخ الحذف',
    ]"

    :fields="[
        'organization_id',
        'name',
        'commercial_registration',
        'tax_number',
        'mobile',
        'email',
        'city',
        'deleted_at_formatted',
    ]"

    :rows="$data"

    :actions="[
        'restore' => fn($row) => route(
            'admin.organizations.restore',
            $row->organization_id
        ),
    
        'force_delete' => fn($row) => route(
            'admin.organizations.forceDestroy',
            $row->organization_id
        ),
    ]"

    :primary-key="'organization_id'"

    :first-item="$data->firstItem()"
    :last-item="$data->lastItem()"
    :total="$data->total()"

    :pagination="$data->links()"
/>
@section('script')
<script>

$(document).ready(function () {

    function loadTrashedData(url) {

        let filters = {};

        $('#trashed_responce')
            .closest('.sTable-card')
            .find('.filter-input')
            .each(function () {

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

            success: function (data) {
                $('#trashed_responce').html(data);
            },

            error: function () {
                alert('حدث خطأ أثناء جلب البيانات');
            }
        });
    }


    // تحميل البيانات عند فتح الصفحة
    loadTrashedData(
        '{{ route("admin.organizations.ajax_trashed") }}'
    );


    // البحث والفرز
    $(document).on(
    'input change',
    '.sTable-card .filter-input',
    function () {

        loadTrashedData(
            '{{ route("admin.organizations.ajax_trashed") }}'
        );
    }
);


    // Pagination
    $(document).on(
        'click',
        '#trashed_responce .pagination a',
        function (e) {

            e.preventDefault();

            let url = $(this).attr('href');

            loadTrashedData(url);
        }
    );


    // تحديث البيانات
    $(document).on(
    'click',
    '#refreshBtn',
    function () {

        let icon = $(this).find('i');

        icon.addClass('fa-spin');

        loadTrashedData(
            '{{ route("admin.organizations.ajax_trashed") }}'
        );

        setTimeout(function () {
            icon.removeClass('fa-spin');
        }, 600);
    }
);

       

});

</script>
@endsection
@endsection