@extends('admin.layout.master')

@section('title')
    إدارة الأدوار
@endsection

@section('contect')


<x-page-header
    title="إدارة الادوار"
    icon="fas fa-key"
/>
<x-navbar
    :links="[
        [
            'title' => 'جميع الأدوار',
            'url' => route('admin.roles.index'),
            'active' => request()->routeIs('admin.roles.*'),
        ],
        [
            'title' => 'جميع الصلاحيات',
            'url' => route('admin.permissions.index'),
            'active' => request()->routeIs('admin.permissions.*'),
        ],
        // [
        //     'title' => 'استثناءات صلاحيات المستخدمين',
        //     'url' => route('admin.user_permission_overrides.index'),
        //     'active' => request()->routeIs('admin.user_permission_overrides.*'),
        // ],
    ]"
/>
    <div class="card" style="margin-top: 10px">
        <div class="card-body pb-2">
            <div class="btn-group mb-3" role="group" aria-label="تبويبات الأدوار">
                <button type="button" class="btn btn-primary role-tab active" data-tab="current" style="margin-left: 5px">
                    <i class="fas fa-user-shield ml-1"></i>
                    الأدوار الحالية
                </button>
                <button type="button" class="btn btn-outline-secondary role-tab" data-tab="deleted">
                    <i class="fas fa-trash-alt ml-1"></i>
                    الأدوار المحذوفة
                </button>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3" style="direction: rtl">
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus ml-1"></i>
            إنشاء دور
        </a>
    </div>
    
    <x-constructor-table title="إدارة الأدوار" icon="fas fa-user-shield" tableid="ajax_responce" :filters="[
        'searchName' => 'بحث باسم الدور أو الاسم البرمجي...',
        'statusFilter' => [
            'all' => 'كل الحالات',
            'ACTIVE' => 'نشط',
            'INACTIVE' => 'غير نشط',
        ],
    ]"
        :sorts="[
            'sortBy' => [
                'id' => 'المعرف',
                'display_name' => 'اسم الدور',
                'name' => 'الاسم البرمجي',
                'created_at' => 'تاريخ الإنشاء',
            ],
            'sort' => [
                'asc' => 'تصاعدي',
                'desc' => 'تنازلي',
            ],
        ]" :columns="['المعرف', 'اسم الدور', 'الاسم البرمجي', 'الحارس', 'عدد إسنادات الأدوار', 'الحالة', 'تاريخ الإنشاء']"
         :fields="['id', 'display_name', 'name', 'guard_name', 'user_roles_count', 'status', 'created_at']" 
         :rows="$roles" 
         :actions="false" :primary-key="'id'"
        :first-item="$roles->firstItem()" 
        :last-item="$roles->lastItem()" :total="$roles->total()" :pagination="$roles->links()" />
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            const ajaxUrl = @json(route('admin.roles.ajax_roles'));
            const csrfToken = @json(csrf_token());

            let currentTab = 'current';

            $('#ajax_responce').empty();

            function loadData(url = ajaxUrl) {

                const filters = {};

                $('.filter-input').each(function() {
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
                        ...filters,
                        tab: currentTab
                    },
                    success: function(data) {
                        $('#ajax_responce').html(data);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('حدث خطأ أثناء جلب بيانات الأدوار.');
                    }
                });
            }

            $(document).on('click', '.role-tab', function() {

                currentTab = $(this).data('tab');

                $('.role-tab')
                    .removeClass('btn-primary active')
                    .addClass('btn-outline-secondary');

                $(this)
                    .removeClass('btn-outline-secondary')
                    .addClass('btn-primary active');

                loadData();
            });

            $(document).on(
                'input change',
                '.filter-input',
                function() {
                    loadData();
                }
            );

            $(document).on(
                'click',
                '#ajax_responce .pagination a',
                function(e) {
                    e.preventDefault();

                    const url = $(this).attr('href');

                    if (url) {
                        loadData(url);
                    }
                }
            );

            $(document).on('click', '#refreshBtn', function() {
                const icon = $(this).find('i');

                icon.addClass('fa-spin');

                loadData();

                $(document).one('ajaxStop.rolesRefresh', function() {
                    icon.removeClass('fa-spin');
                });
            });

            $(document).on('click', '.change-status', function(e) {
                e.preventDefault();

                const button = $(this);
                const id = button.data('id');
                const status = button.data('status');

                const actionText = status === 'ACTIVE' ? 'تفعيل' : 'تعطيل';

                Swal.fire({
                    title: `هل تريد ${actionText} هذا الدور؟`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، متابعة',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        url: `/roles/${id}/change-status`,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: status
                        },
                        beforeSend: function() {
                            button.prop('disabled', true);
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم بنجاح',
                                text: response.message,
                                confirmButtonText: 'حسنًا'
                            });

                            loadData();
                        },
                        error: function(xhr) {
                            const response = xhr.responseJSON || {};

                            Swal.fire({
                                icon: 'error',
                                title: 'تعذر تغيير الحالة',
                                text: response.message ||
                                    'حدث خطأ أثناء تحديث حالة الدور.',
                                confirmButtonText: 'حسنًا'
                            });
                        },
                        complete: function() {
                            button.prop('disabled', false);
                        }
                    });
                });
            });
            loadData();
        });
    </script>
@endsection
