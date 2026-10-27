
@extends('admin.layout.master')

@section('title')
    إدارة المستخدمين
@endsection

@section('contect')


@section('contect')
<x-page-header
        title="إدارة المستخدمين"
        icon="fas fa-users"
       
    />
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
        title="إدارة المستخدمين"
        icon="fas fa-users"
        tableid="ajax_responce"

        :filters="[
            'searchName' => 'بحث باسم المستخدم أو البريد الإلكتروني...',

    
            'userType' => [
    'active' => 'المستخدمون الحاليون',
    'deleted' => 'المستخدمون المحذوفون',
],

            'contextFilter' => $accessContexts->pluck('name', 'id')
                ->prepend('كل سياقات الوصول', 'all')
                ->toArray(),
        ]"

        :sorts="[
            'sortBy' => [
                'id' => 'المعرف',
                'name' => 'اسم المستخدم',
                'email' => 'البريد الإلكتروني',
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
            'سياقات الوصول',
            'الأدوار',
            'تاريخ الإنشاء',
        ]"

        :fields="[
            'id',
            'name',
            'email',
            'assignments_summary',
            'roles_summary',
            'created_at',
        ]"

        :rows="$data"

        :actions="[
            'show' => fn($row) => route('admin.users.show', $row->id),
            'edit' => fn($row) => route('admin.users.edit', $row->id),
            'delete' => fn($row) => route('admin.users.delete', $row->id),
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

            const ajaxUrl = @json(route('admin.users.ajax_users'));
            const csrfToken = @json(csrf_token());

            // تحميل النتائج الأولية عبر AJAX
            $('#ajax_responce').empty();

            loadData(ajaxUrl);

            function loadData(url) {

                let filters = {};

                // جمع قيم البحث والفلاتر والترتيب
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

                        alert('حدث خطأ أثناء جلب بيانات المستخدمين.');
                    }
                });
            }

            // البحث والفلترة والترتيب
            $(document).on(
                'input change',
                '.filter-input',
                function () {
                    loadData(ajaxUrl);
                }
            );

            // التنقل بين صفحات النتائج
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

            // تحديث النتائج
            $(document).on('click', '#refreshBtn', function () {

                const icon = $(this).find('i');

                icon.addClass('fa-spin');

                loadData(ajaxUrl);

                // إزالة الحركة بعد انتهاء طلب AJAX
                $(document).one('ajaxStop.usersRefresh', function () {
                    icon.removeClass('fa-spin');
                });
            });

        });
    </script>
@endsection

