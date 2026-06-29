@extends('admin.layout.master')

@section('title')
    باقات الاشتراك
@endsection

@section('contect')

@include('admin.incloude.agencynavepare')







<x-constructor-table
title="قائمة باقات الاشتراك"
icon="fas fa-box me-2"
:filters="[
  'searchsup_name' => 'بحث بالاسم .....',
  'statusFilter' => ['all'=> 'كل الحالات','pending_approval'=>'قيدالموافقة','active'=> 'نشط','suspended'=>'موقوف',
   'blocked'=> 'محظور',
   'expired'=> 'منتهي' ],
   'plan_tierFilter' => ['all'=>'كل المستويات','Basic'=>'اساسية','Standard'=>'قياسية','Premium'=> 'متقدمة',
   'Enterprise'=> ' مؤسسات' ],
]"
:sorts="[
  'sortBy' => ['plan_id'=> 'المعرف','plan_name'=> 'اسم الباقة' ],
   'sort' => ['asc'=> 'تصاعدي','desc'=> 'تنازلي'],
]"
tableid="ajax_responce"
:columns="['المعرف','اسم الباقة',' المستوى',' السعر',' المدة/يوم','نوع الباقة',' الفترة التجريبية/ يوم','غرامة التأخير'
,'فترة السماح','عدد المستخدمين','عددالفروع','مساحة التخزين','الحالة','الميزات']"
:fields="['plan_id','plan_name','plan_tier_label','plan_price','duration','subscription_type_label','trial_period_value',
'late_fee','grace_period','max_users','max_branches','storage_space','status_label','features']"
:rows="$data"

:actions="[
  'show' => fn($row) => route('admin.subscription.detaile', $row['plan_id']),
  'edit' => fn($row) => route('admin.subscription.edit', $row['plan_id']),
  'delete' => fn($row) => route('admin.subscription.delete', $row['plan_id']),
]"
:primary-key="'plan_id'"
:first-item="$data->firstItem()"
:last-item="$data->lastItem()"
:total="$data->total()"
:pagination="$data->links()"


/>



@section('script')

<script >

  $(document).ready(function(){
    
    loadData('{{ route("admin.subscription.ajax_searchsup") }}');
   
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

loadData('{{ route("admin.subscription.ajax_searchsup") }}');

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
  
      loadData('{{ route("admin.subscription.ajax_searchsup") }}');
  
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
