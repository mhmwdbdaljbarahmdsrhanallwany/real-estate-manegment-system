@extends('admin.layout.master')

@section('title')
    جميع الموسسات
@endsection

@section('contect')
    @include('admin.incloude.agencynavepare')


    <!-- /.row -->



    

    <div class="card-tools" style="margin-top: 7px">

        <a href="{{ route('admin.organizations.registration_form') }}"
           target="_blank"
           class="btn btn-secondary btn-sm">
            <i class="fas fa-print mr-1"></i>
            طباعة استمارة التسجيل
        </a>
    
    </div>
    <x-constructor-table
    :entity="'المؤسسة'"
:entity-plural="'المؤسسات'"
     title="قائمة المؤسسات" :filters="[
        'searchName' => 'بحث باسم المؤسسة...',
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
            'commercial_registration' => 'السجل التجاري',
            'created_at' => 'تاريخ الإنشاء',
        ],
        'sort' => [
            'asc' => 'تصاعدي',
            'desc' => 'تنازلي',
        ],
    ]" tableid="ajax_responce"
     :columns="[
        'المعرف',
        'اسم المؤسسة',
        'السجل التجاري',
        'الرقم الضريبي',
        'الجوال',
        'البريد الإلكتروني',
        'المدينة',
        'الحالة',
    ]"
        :fields="[
            'organization_id',
            'name',
            'commercial_registration',
            'tax_number',
            'mobile',
            'email',
            'city',
            'status',
        ]" :rows="$data" 
        :actions="[
            'show' => fn($row) => route('admin.organizations.show', $row['organization_id']),
            'edit' => fn($row) => route('admin.organizations.edit', $row['organization_id']),
            'delete' => fn($row) => route('admin.organizations.destroy', $row['organization_id']),
        ]" :primary-key="'organization_id'" 
        :first-item="$data->firstItem()" 
        :last-item="$data->lastItem()"
        :total="$data->total()" :pagination="$data->links()" />




    <!-- روابط الصفحات -->
    @section('script')
    <script>


$(document).ready(function () {


loadData('{{ route("admin.organizations.ajax_organizations") }}');

function loadData(url) {

    let filters = {};

    $('.filter-input').each(function () {

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
        success: function(data){
            $("#ajax_responce").html(data);
        },
        error: function(){
            alert('حدث خطأ أثناء جلب البيانات');
        }
    });

}


$(document).on('input change', '.filter-input', function () {

    loadData('{{ route("admin.organizations.ajax_organizations") }}');

});


$(document).on('click', '.pagination a', function(e) {

    e.preventDefault();

    let url = $(this).attr('href');

    loadData(url);

});


$(document).on('click', '#refreshBtn', function(){

    let icon = $(this).find('i');

    icon.addClass('fa-spin');

    loadData('{{ route("admin.organizations.ajax_organizations") }}');

    setTimeout(() => {
        icon.removeClass('fa-spin');
    }, 600);

});


});


    </script>
    @endsection

@endsection
