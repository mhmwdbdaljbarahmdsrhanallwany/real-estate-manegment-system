@extends('admin.layout.master')

@section('title')
    المؤسسات والباقات قيد الاشتراك
@endsection



@section('contect')
@include('admin.incloude.agencynavepare')

    <x-constructor-table title="قائمة  المؤسسات قيد الاشتراك" icon="fas fa-calendar-alt me-2" :filters="[
        'searchSubscription' => 'بحث باسم المؤسسة .....',
        'planStatusFilter' => [
            'all' => 'كل الحالات',
            'trial' => 'تجريبي',
            'active' => 'نشط',
            'expired' => 'منتهي',
            'grace_period' => 'فترة سماح',
        ],
        'subscriptionTypeFilter' => [
            'all' => 'كل الانواع',
            'شهرية' => 'شهرية',
            'سنوية' => 'سنوية',
            'أكثر من سنة' => 'أكثر من سنة',
        ],
    ]"
        :sorts="[
            'sortBy' => ['AgencySubscriptionID' => 'المعرف', 'agency_name' => 'اسم المؤسسة'],
            'sort' => ['asc' => 'تصاعدي', 'desc' => 'تنازلي'],
        ]" tableid="ajax_responce1" :columns="[
            'معرف الاشتراك',
            ' معرف المؤسسة',
            '  المؤسسة',
            ' نوع الاشتراك',
            'تاريخ البداية ',
            ' تاريخ الانتهاء',
            'حالة الاشتراك ',
            'السعر',
            ' المدفوع',
            ' المتبقي',
        ]" :fields="[
            'AgencySubscriptionID',
            'agency_id',
            'agency_name',
            'SubscriptionType',
            'StartDate',
            'EndDate',
            'PlanStatus',
            'PlanPrice',
            'AmountPaid',
            'AmountRemaining',
        ]" :rows="$data" :actions="[
            'show' => fn($row) => route('admin.agencysubscription.detaile', $row['AgencySubscriptionID']),
            'edit' => fn($row) => route('admin.agencysubscription.edit', $row['AgencySubscriptionID']),
            'delete' => fn($row) => route('admin.agencysubscription.delete', $row['AgencySubscriptionID']),
        ]"
        :primary-key="'AgencySubscriptionID'" :first-item="$data->firstItem()" :last-item="$data->lastItem()" :total="$data->total()" :pagination="$data->links()" />




    <x-constructor-table id="table2" title="قائمة  الباقات قيد الاشتراك" icon="fas fa-calendar-alt me-2" :filters="[
        'searchsup_name' => 'بحث بالاسم .....',
      
        'planStatusFilter' => [
            'all' => 'كل الحالات',
            'trial' => 'تجريبي',
            'active' => 'نشط',
            'expired' => 'منتهي',
            'grace_period' => 'فترة سماح',
        ],
        'subscriptionTypeFilter' => [
            'all' => 'كل الانواع',
            'شهرية' => 'شهرية',
            'سنوية' => 'سنوية',
            'أكثر من سنة' => 'أكثر من سنة',
        ],
    ]"
        :sorts="[
            'sortBy' => [
                'AgencySubscriptionID' => 'معرف الاشتراك',
                'plan_name' => 'اسم الباقة',
            ],
            'sort' => ['asc' => 'تصاعدي', 'desc' => 'تنازلي'],
        ]" tableid="ajax_responce2" :columns="[
            'معرف الاشتراك',
            ' معرف الباقة',
            '  الباقة',
            ' نوع الاشتراك',
            'تاريخ البداية ',
            ' تاريخ الانتهاء',
            'حالة الاشتراك ',
            'السعر',
            ' المدفوع',
            ' المتبقي',
        ]" :fields="[
            'AgencySubscriptionID',
            'plan_id',
            'plan_name',
            'SubscriptionType',
            'StartDate',
            'EndDate',
            'PlanStatus',
            'PlanPrice',
            'AmountPaid',
            'AmountRemaining',
        ]" :rows="$data"
        :actions="[
            'show' => fn($row) => route('admin.agencysubscription.detaile', $row['AgencySubscriptionID']),
            'edit' => fn($row) => route('admin.agencysubscription.edit', $row['AgencySubscriptionID']),
            'delete' => fn($row) => route('admin.agencysubscription.delete', $row['AgencySubscriptionID']),
        ]" :primary-key="'AgencySubscriptionID'" :first-item="$data->firstItem()" :last-item="$data->lastItem()" :total="$data->total()" :pagination="$data->links()" />
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            loadData('{{ route('admin.agencysubscription.serch2') }}');


            function loadData(url) {

                let filters = {};

                $(' .filter-input').each(function() {

                    let name = $(this).attr('name');
                    let value = $(this).val();
                   

                    filters[name] = value;

                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'html',
                    cache: false,
                    data: {
                       
                        ...filters
                    },
                    success: function(data) {
                        $("#ajax_responce1").html(data);
                    },
                    error: function() {
                        alert('حدث خطأ أثناء جلب البيانات');
                    }
                });

            }




            $(document).on('input change', '.filter-input', function() {

                loadData('{{ route('admin.agencysubscription.serch2') }}');


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
  
      loadData('{{ route("admin.agencysubscription.serch2") }}');
  
      setTimeout(() => {
        icon.removeClass('fa-spin');
      }, 600);
    });

    $(function () {
    $('[data-toggle="tooltip"]').tooltip();
      });
        });
    </script>
    <script>
        $(document).ready(function() {
            loadPlanData('{{ route('admin.agencysubscription.serch1') }}');



            function loadPlanData(url) {

                let filters = {};

                $(' .table2').each(function() {

                    let name = $(this).attr('name');
                    let value = $(this).val();
                    

                    filters[name] = value;

                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'html',
                    cache: false,
                    data: {
                      
                        ...filters
                    },
                    success: function(data) {
                        $("#ajax_responce2").html(data);
                    },
                    error: function() {
                        alert('حدث خطأ أثناء جلب البيانات');
                    }
                });

            }


            $(document).on('input change', '.table2', function() {

                loadPlanData('{{ route('admin.agencysubscription.serch1') }}');


            });
            $(document).on('click', '.pagination a', function(e) {

                e.preventDefault();

                let url = $(this).attr('href');


                loadPlanData(url);


            });
             //  زر التحديث
    $(document).on('click', '#refreshBtn', function(){
      let icon = $(this).find('i');
  
      icon.addClass('fa-spin');
  
      loadPlanData('{{ route("admin.agencysubscription.serch1") }}');
  
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