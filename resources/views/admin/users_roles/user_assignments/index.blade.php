
@extends('admin.layout.master')

@section('title')
    تعيينات المستخدمين
@endsection

@section('contect')

<x-page-header
        title="إدارة تعيينات المستخدمين"
        icon="fas fa-users"
       
    />
    
<div class="row mt-3"
id="assignmentStats"
data-stats-url="{{ route('admin.user_assignments.stats') }}">

    <x-stat-card
    title="إجمالي التعيينات"
    :value="$totalAssignments"
    icon="fas fa-layer-group"
    color="info"
    stat-key="total"
/>

<x-stat-card
    title="التعيينات النشطة"
    :value="$activeAssignments"
    icon="fas fa-check-circle"
    color="success"
    stat-key="active"
/>

<x-stat-card
    title="التعيينات غير النشطة"
    :value="$inactiveAssignments"
    icon="fas fa-pause-circle"
    color="secondary"
    stat-key="inactive"
/>

<x-stat-card
    title="التعيينات المنتهية"
    :value="$expiredAssignments"
    icon="fas fa-clock"
    color="danger"
    stat-key="expired"
/>

</div>

<x-navbar
:links="[
    [
        'title' => 'المستخدمون',
        'url' => route('admin.users.index'),
        'active' => request()->routeIs('admin.users.*'),
    ],
    [
        'title' => 'سياقات الوصول',
        'url' => route('admin.access_contexts.index'),
        'active' => request()->routeIs('admin.access_contexts.*'),
    ],
    [
        'title' => 'تعيينات المستخدمين',
        'url' => route('admin.user_assignments.index'),
        'active' => request()->routeIs('admin.user_assignments.*'),
    ],
]"
/>
    <x-constructor-table
        title="تعيينات المستخدمين"
        icon="fas fa-layer-group"
        tableid="ajax_responce"

        :filters="[
            'searchName' => 'بحث باسم المستخدم أو البريد الإلكتروني...',

            'statusFilter' => [
                'all' => 'كل حالات التعيين',
                'ACTIVE' => 'نشط',
                'INACTIVE' => 'غير نشط',
                'EXPIRED' => 'منتهي',
            ],

            'contextFilter' => $accessContexts->pluck('name', 'id')
                ->prepend('كل سياقات الوصول', 'all')
                ->toArray(),
        ]"

        :sorts="[
            'sortBy' => [
                'id' => 'المعرف',
                'status' => 'حالة التعيين',
                'start_date' => 'بداية التعيين',
                'end_date' => 'نهاية التعيين',
                'created_at' => 'تاريخ الإنشاء',
            ],

            'sort' => [
                'asc' => 'تصاعدي',
                'desc' => 'تنازلي',
            ],
        ]"

        :columns="[
            'المعرف',
            'اسم المستخدم',
            'البريد الإلكتروني',
            'سياق الوصول',
            'الأدوار',
            'بداية التعيين',
            'نهاية التعيين',
            'الحالة',
        ]"

        :fields="[
            'id',
            'user_name',
            'user_email',
            'context_name',
            'roles_summary',
            'start_date_display',
            'end_date_display',
            'status',
        ]"

        :rows="$data"
        :actions="[        
            'edit' => fn($row) => route('admin.user_assignments.edit', $row->id),
        
            'check' => fn($row) => [
                'id' => $row->id,
                'status' => $row->status === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE',
                'title' => $row->status === 'ACTIVE' ? 'تعطيل التعيين' : 'تفعيل التعيين',
                'class' => $row->status === 'ACTIVE' ? 'suspend' : 'approve',
            ],
        ]"
        :primary-key="'id'"
        :first-item="$data->firstItem()"
        :last-item="$data->lastItem()"
        :total="$data->total()"
        :pagination="$data->links()"
    />

@endsection

@section('script')
    <script>
        $(document).ready(function () {

            const ajaxUrl = @json(route('admin.user_assignments.ajax_assignments'));
            const csrfToken = @json(csrf_token());

            $('#ajax_responce').empty();

            loadData(ajaxUrl);

            function loadData(url) {

                let filters = {};

                $('.filter-input').each(function () {
                    const name = $(this).attr('name');

                    if (name) {
                        filters[name] = $(this).val();
                    }
                });

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'html',
                    cache: false,

                    data: {
                        _token: csrfToken,
                        ...filters
                    },

                    success: function (data) {
                        $('#ajax_responce').html(data);
                    },

                    error: function (xhr) {
                        console.error(xhr.responseText);
                        alert('حدث خطأ أثناء جلب تعيينات المستخدمين.');
                    }
                });
            }

            $(document).on(
                'input change',
                '.filter-input',
                function () {
                    loadData(ajaxUrl);
                }
            );

            $(document).on(
                'click',
                '#ajax_responce .pagination a',
                function (e) {
                    e.preventDefault();

                    const url = $(this).attr('href');

                    if (url) {
                        loadData(url);
                    }
                }
            );

            $(document).on('click', '#refreshBtn', function () {
                const icon = $(this).find('i');

                icon.addClass('fa-spin');

                loadData(ajaxUrl);

                $(document).one('ajaxStop.assignmentsRefresh', function () {
                    icon.removeClass('fa-spin');
                });
            });

            
const statusUrlTemplate = @json(
    route('admin.user_assignments.updateStatus', ['id' => '__ID__'])
);

$(document).on(
    'click',
    '#ajax_responce .change-status',
    function (e) {
        e.preventDefault();

        const button = $(this);
        const id = button.data('id');
        const targetStatus = button.attr('data-status');

        if (!id || !['ACTIVE', 'INACTIVE'].includes(targetStatus)) {
            Swal.fire('تنبيه', 'بيانات تغيير الحالة غير صحيحة.', 'warning');
            return;
        }

        const activate = targetStatus === 'ACTIVE';

        Swal.fire({
            title: activate ? 'تنشيط التعيين؟' : 'إلغاء تنشيط التعيين؟',
            text: activate
                ? 'هل تريد تنشيط هذا التعيين؟'
                : 'هل تريد إلغاء تنشيط هذا التعيين؟',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'نعم، متابعة',
            cancelButtonText: 'إلغاء',
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }

            button.prop('disabled', true);

            $.ajax({
                url: statusUrlTemplate.replace('__ID__', id),
                type: 'PATCH',
                dataType: 'json',
                data: {
                    _token: csrfToken,
                    status: targetStatus,
                },
                success: function (response) {
                    Swal.fire(
                        'تم',
                        response.message,
                        'success'
                    );

                    loadData(ajaxUrl);
                    StatsComponent.refresh('#assignmentStats');
                },
                error: function (xhr) {
                    const message =
                        xhr.responseJSON?.message
                        || 'تعذر تغيير حالة التعيين.';

                    Swal.fire('تعذر التنفيذ', message, 'error');
                },
                complete: function () {
                    button.prop('disabled', false);
                },
            });
        });
    }
);


        });
    </script>
@endsection
