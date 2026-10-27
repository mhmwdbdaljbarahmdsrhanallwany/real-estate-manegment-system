@extends('admin.layout.master')

@section('title')
    جميع الاشتراكات
@endsection

@section('contect')
    @include('admin.incloude.agencynavepare')


    <x-constructor-table title="قائمة الاشتراكات" icon="fas fa-calendar-alt me-2" :filters="[
        'searchOrganization' => 'بحث باسم المؤسسة...',
    
        'statusFilter' => [
            'all' => 'كل الحالات',
    
            'pending' => 'قيد الانتظار',
    
            'active' => 'نشط',
    
            'expired' => 'منتهي الصلاحية',
    
            'cancelled' => 'ملغي',
        ],
    
        'subscriptionTypeFilter' => [
            'all' => 'كل الأنواع',
    
            'trial' => 'تجريبي',
    
            'paid' => 'مدفوع',
        ],
    ]" :sorts="[
        'sortBy' => [
            'subscription_id' => 'المعرف',
    
            'start_date' => 'تاريخ البداية',
    
            'end_date' => 'تاريخ النهاية',
    
            'status' => 'الحالة',
        ],
    
        'sort' => [
            'asc' => 'تصاعدي',
    
            'desc' => 'تنازلي',
        ],
    ]"
        tableid="ajax_responce" :columns="[
            'المعرف',
        
            'المؤسسة',
        
            'الباقة',
        
            'نوع الاشتراك',
        
            'بداية الاشتراك',
        
            'نهاية الاشتراك',
        
            'الفترة التجريبية',
        
            'انتهاء التجربة',
        
            'التجديد التلقائي',
        
            'الحالة',

        ]" :fields="[
            'subscription_id',
        
            'organization.name',
        
            'package.name',
        
            'subscription_type_label',
        
            'start_date_formatted',
        
            'end_date_formatted',
        
            'trial_period_label',
        
            'trial_end_date',
        
            'auto_renew_label',
        
            'status_label',

        ]" 
        :rows="$data" :actions="[
            'show' => fn($row) => route('admin.subscriptions.show', $row['subscription_id']),
        
            'edit' => fn($row) => route('admin.subscriptions.edit', $row['subscription_id']),
        
            'delete' => fn($row) => route('admin.subscriptions.delete', $row['subscription_id']),
        ]" :primary-key="'subscription_id'"
        :first-item="$data->firstItem()" :last-item="$data->lastItem()" :total="$data->total()" :pagination="$data->links()" />
@section('script')
    <script>
        $(document).ready( function() {
            loadData('{{ route('admin.subscriptions.ajax_search_subscription') }}');

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
                    success: function(data) {
                        $("#ajax_responce").html(data);
                    },
                    error: function() {
                        alert('حدث خطأ أثناء جلب البيانات');
                    }
                });

            }
            $(document).on('input change', '.filter-input', function() {

                loadData('{{ route('admin.subscriptions.ajax_search_subscription') }}');

            });
            $(document).on('click', '.pagination a', function(e) {

                e.preventDefault();

                let url = $(this).attr('href');

                loadData(url);

            });
            //  زر التحديث
            $(document).on('click', '#refreshBtn', function() {
                let icon = $(this).find('i');

                icon.addClass('fa-spin');

                loadData('{{ route('admin.subscriptions.ajax_search_subscription') }}');

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
