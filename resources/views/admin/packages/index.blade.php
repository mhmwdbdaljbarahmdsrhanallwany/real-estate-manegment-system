@extends('admin.layout.master')

@section('title')
    باقات الاشتراك
@endsection

@section('contect')

@include('admin.incloude.agencynavepare')


{{-- 
<x-navbar :links="[
    [
        'title'=>'الرئيسية',
        'url'=>route('admin.packages.index')
],
[
        'title'=>'اضافة',
        'url'=>route('admin.packages.create')
],
    [
        'title' => 'الخدمات',
        'url' => '/services'
    ],
    [
        'title' => 'تواصل معنا',
        'url' => '/contact'
    ],
]" /> --}}







<x-constructor-table
    title="قائمة الباقات"
    icon="fas fa-box me-2"

    :filters="[
        'search_name' => 'بحث باسم الباقة .....',

        'is_active' => [
            'all' => 'كل الحالات',
            1 => 'نشطة',
            0 => 'غير نشطة'
        ],

        'billing_cycle' => [
            'all' => 'كل الدورات',
            'monthly' => 'شهري',
            'yearly' => 'سنوي',
            'weekly' => 'أسبوعي',
        ]
    ]"

    :sorts="[
        'sortBy' => [
            'package_id' => 'المعرف',
            'name' => 'اسم الباقة',
            'base_price' => 'السعر',
            'level' => 'المستوى'
        ],
        'sort' => [
            'asc' => 'تصاعدي',
            'desc' => 'تنازلي'
        ]
    ]"

    tableid="ajax_responce"

    :columns="[
        'المعرف',
        'اسم الباقة',
        'الرمز',
        'المستوى',
        'السعر الأساسي',
        'دورة الفوترة',
        'مدة الاشتراك',
        'الخصم',
        'الضريبة',
        'المستخدمين',
        'الفروع',
        'العقارات',
        'الوحدات',
        'التخزين',
        'طلبات API',
        'الحالة',
        'مميز'
    ]"

    :fields="[
        'package_id',
        'name',
        'code',
        'level',
        'base_price',
        'billing_cycle',
        'duration_days',
        'discount_value',
        'tax_value',
        'max_users',
        'max_branches',
        'max_properties',
        'max_units',
        'storage_limit_mb',
        'max_api_requests',
        'is_active',
        'is_featured'
    ]"

    :rows="$data"

    :actions="[
        'show' => fn($row) => route('admin.packages.show', $row->package_id),
        'edit' => fn($row) => route('admin.packages.edit', $row->package_id),
        'delete' => fn($row) => route('admin.packages.delete', $row->package_id),
    ]"

    :primary-key="'package_id'"

    :first-item="$data->firstItem()"
    :last-item="$data->lastItem()"
    :total="$data->total()"
    :pagination="$data->links()"
/>



@section('script')

<script >

$(document).ready( function () {
    $("#ajax_responce").empty();

loadData('{{ route("admin.packages.ajax_packages") }}');

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

    loadData('{{ route("admin.packages.ajax_packages") }}');

});


$(document).on('click', '.pagination a', function(e) {

    e.preventDefault();

    let url = $(this).attr('href');

    loadData(url);

});


$(document).on('click', '#refreshBtn', function(){

    let icon = $(this).find('i');

    icon.addClass('fa-spin');

    loadData('{{ route("admin.packages.ajax_packages") }}');

    setTimeout(() => {
        icon.removeClass('fa-spin');
    }, 600);

});


});
  </script>
 


@endsection
@endsection
