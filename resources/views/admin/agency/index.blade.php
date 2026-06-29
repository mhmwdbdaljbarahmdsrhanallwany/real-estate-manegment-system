@extends('admin.layout.master')

@section('title')
    جميع الموسسات
@endsection

@section('contect')
@include('admin.incloude.agencynavepare')


<!-- /.row -->


<x-constructor-table
title="قائمة المؤسسات العقارية"
:filters="[
  'searchagency_name' => 'بحث باسم المؤسسة.....',
  'statusFilter' => ['all'=> 'كل الحالات','pending_approval'=>'قيدالموافقة','active'=> 'نشط','suspended'=>'موقف',
   'blocked'=> 'محظور',
   'expired'=> 'منتهي' ],
   'subscriptionFilter' => ['all'=>'كل الاشتراكات','trial'=>'تجريبي','active'=>'فعال','expired'=> 'منتهي',
   'grace_period'=> 'فترة سماح' ],
]"
:sorts="[
  'sortBy' => ['agency_id'=> 'المعرف','agency_name'=> 'اسم المؤسسة' ],
   'sort' => ['asc'=> 'تصاعدي','desc'=> 'تنازلي'],
]" 
tableid="ajax_responce"
:columns="['المعرف','اسم المؤسسة','السجل التجاري','الرقم الضريبي','البريد الالكتروني','الهاتف','الحالة','الاشتراك']"
:fields="['agency_id','agency_name','commercial_registration','tax_number','email','phone','status','subscription_status']"
:rows="$data"

:actions="[
  'show' => fn($row) => route('admin.agencies.detaile', $row['agency_id']),
  'edit' => fn($row) => route('admin.agencies.edit', $row['agency_id']),
  'delete' => fn($row) => route('admin.agencies.delete', $row['agency_id']),
]"
:primary-key="'agency_id'"
:first-item="$data->firstItem()"
:last-item="$data->lastItem()"
:total="$data->total()"
:pagination="$data->links()"


/>




<!-- روابط الصفحات -->

@section('script')


<script >

  $(document).ready(function(){
    loadData('{{ route("admin.agencies.ajax_search") }}');
   
    function loadData( url) {

    let filters = {};

    $('.filter-input').each(function () {

        let name = $(this).attr('name');
        let value = $(this).val();

        filters[name] = value;

    });

    $.ajax({
        url: url ,
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

loadData('{{ route("admin.agencies.ajax_search") }}');

});
$(document).on('click', '.pagination a', function(e) {

e.preventDefault();

let url = $(this).attr('href');

loadData(url);

});
   //  زر التحديث
    $(document).on('click', '#refreshBtn', function(){
      let icon = $(this).find('i');
  
      icon.addClass('fa-spin');
  
      loadData('{{ route("admin.agencies.ajax_search") }}');
  
      setTimeout(() => {
        icon.removeClass('fa-spin');
      }, 600);
    });

    $(function () {
    $('[data-toggle="tooltip"]').tooltip();
      });
  
  });
  </script>
@endsection

@endsection
