@extends('admin.layout.master')

@section('title')
    جميع الاشتراكات
@endsection

@section('contect')
@include('admin.incloude.agencynavepare')




<x-constructor-table
title="قائمة  كل الاشتراكات"
icon="fas fa-calendar-alt me-2"
:filters="[
  'searchSubscription' => 'بحث باسم المؤسسة .....',
  'planStatusFilter' => ['all'=> 'كل الحالات','trial'=>'تجريبي','active'=> 'نشط','expired'=>'منتهي','grace_period'=> 'فترة سماح' ],
   'subscriptionTypeFilter' => ['all'=>'كل الانواع','شهرية'=>'شهرية','سنوية'=>'سنوية','أكثر من سنة'=> 'أكثر من سنة' ],
]"
:sorts="[
  'sortBy' => ['AgencySubscriptionID'=> 'المعرف','agency_name'=> 'اسم المؤسسة' ],
   'sort' => ['asc'=> 'تصاعدي','desc'=> 'تنازلي'],
]"
tableid="ajax_responce"
:columns="['المعرف','المؤسسة',' الباقة',' نوع الاشتراك','تاريخ البداية ',' تاريخ الانتهاء','الحالة ',
'السعر',' المدفوع',' المتبقي'
]"
:fields="['AgencySubscriptionID','agency.agency_name','plan.plan_name','SubscriptionType','StartDate','EndDate','PlanStatus',
'PlanPrice','AmountPaid','AmountRemaining'
]"
:rows="$data"

:actions="[
  'show' => fn($row) => route('admin.agencysubscription.detaile', $row['AgencySubscriptionID']),
  'edit' => fn($row) => route('admin.agencysubscription.edit', $row['AgencySubscriptionID']),
  'delete' => fn($row) => route('admin.agencysubscription.delete', $row['AgencySubscriptionID']),
]"
:primary-key="'AgencySubscriptionID'"
:first-item="$data->firstItem()"
:last-item="$data->lastItem()"
:total="$data->total()"
:pagination="$data->links()"


/>


@section('script')

<script >

  $(document).ready(function(){
    loadData('{{ route("admin.agencysubscription.ajax_search_ag_sup") }}');
   
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

loadData('{{ route("admin.agencysubscription.ajax_search_ag_sup") }}');

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
  
      loadData('{{ route("admin.agencysubscription.ajax_search_ag_sup") }}');
  
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
