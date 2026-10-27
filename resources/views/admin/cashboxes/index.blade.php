
@extends('admin.layout.master')

@section('title')
    إدارة الصناديق المالية
@endsection

@section('contect')

<div class="container-fluid" dir="rtl">

    {{-- عنوان الصفحة وأزرار الإجراءات --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>
                    <h3 class="mb-1">إدارة الصناديق المالية</h3>
                    <p class="text-muted mb-0">
                        عرض الصناديق المالية والبحث فيها وإدارة بياناتها.
                    </p>
                </div>

                <div class="mt-2">
                    <a href="{{ route('admin.cashboxes.create') }}"
                       class="btn btn-primary">
                        <i class="fas fa-plus ml-1"></i>
                        إضافة صندوق مالي
                    </a>

                   
                </div>

            </div>
        </div>
    </div>
   
{{-- منطقة التنبيهات الديناميكية --}}
<div id="cashbox-notices" class="row mb-3" dir="rtl">

    @include('admin.cashboxes.partials.notices', [
        'inactiveCashboxesCount' => $inactiveCashboxesCount,
        'activeMainCashboxesCount' => $activeMainCashboxesCount,
    ])

</div>



    {{-- جدول الصناديق --}}
    <x-constructor-table
        :entity="'الصندوق'"
        :entity-plural="'الصناديق'"
        title="قائمة الصناديق المالية"

        :filters="[
            'searchName' => 'بحث باسم الصندوق أو الكود...',
            'statusFilter' => [
                'all' => 'كل الحالات',
                'active' => 'نشط',
                'inactive' => 'غير نشط',
            ],
        ]"

        :sorts="[
            'sortBy' => [
                'cashboxe_id' => 'المعرف',
                'name' => 'اسم الصندوق',
                'code' => 'كود الصندوق',
                'current_balance' => 'الرصيد الحالي',
                'created_at' => 'تاريخ الإنشاء',
            ],
            'sort' => [
                'asc' => 'تصاعدي',
                'desc' => 'تنازلي',
            ],
        ]"

        tableid="ajax_responce"

        :columns="[
            'المعرف',
            'اسم الصندوق',
            'الكود',
            'العملة',
            'الرصيد الافتتاحي',
            'الرصيد الحالي',
            'الحالة',
        ]"

        :fields="[
            'cashboxe_id',
            'name',
            'code',
            'currency',
            'opening_balance',
            'current_balance',
            'status',
        ]"

        :actions="[
            'edit' => fn($row) => route(
                'admin.cashboxes.edit',
                $row['cashboxe_id']
            ),

            'check' => fn($row) => [
                'id' => $row['cashboxe_id'],

                'status' => $row['status'] === 'active'
                    ? 'inactive'
                    : 'active',

                'title' => $row['status'] === 'active'
                    ? 'تعطيل الصندوق'
                    : 'تفعيل الصندوق',

                'class' => $row['status'] === 'active'
                    ? 'suspend'
                    : 'approve',
            ],
        ]"

        :rows="$cashboxes"
        :primary-key="'cashboxe_id'"
        :first-item="$cashboxes->firstItem()"
        :last-item="$cashboxes->lastItem()"
        :total="$cashboxes->total()"
        :pagination="$cashboxes->links()"
    />

</div>

@endsection

@section('script')
<script>
$(document).ready(function () {

    const searchUrl = @json(route('admin.cashboxes.ajax_search'));
    const changeStatusUrl = @json(route('admin.cashboxes.change_status'));
    const csrfToken = @json(csrf_token());

    // تحميل بيانات الصناديق مع الاحتفاظ بالفلاتر الحالية.
    function loadData(url) {
        const filters = {};

        $('.filter-input').each(function () {
            const name = $(this).attr('name');

            if (name) {
                filters[name] = $(this).val();
            }
        });

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',
            cache: false,
            data: filters,

            success: function (html) {
                $('#ajax_responce').html(html);
            },

            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء جلب بيانات الصناديق.',
                });
            }
        });
    }

    // التحميل الأول للجدول.
    loadData(searchUrl);

    // البحث والتصفية والترتيب.
    $(document).on('input change', '.filter-input', function () {
        loadData(searchUrl);
    });

    // التنقل بين صفحات النتائج.
    $(document).on('click', '#refreshBtn', function (e) {
    e.preventDefault();

    const button = $(this);
    const icon = button.find('i');

    icon.addClass('fa-spin');

    loadData(searchUrl);

    setTimeout(function () {
        icon.removeClass('fa-spin');
    }, 600);
});

    // تحديث الجدول.
    $('#refreshCashboxesBtn').on('click', function () {
        const button = $(this);
        const icon = button.find('i');

        icon.addClass('fa-spin');
        button.prop('disabled', true);

        loadData(searchUrl);
    });

    // إيقاف حركة زر التحديث بعد انتهاء طلب تحميل الجدول.
    // معالج النقر يستخدم التفويض لأن الجدول يتغير عبر AJAX.
    $(document).ajaxComplete(function (event, xhr, settings) {
        if (
            settings.url &&
            settings.url.indexOf(searchUrl) !== -1
        ) {
            $('#refreshCashboxesBtn')
                .prop('disabled', false)
                .find('i')
                .removeClass('fa-spin');
        }
    });

    // تفعيل أو تعطيل الصندوق المالي.
    $(document).on('click', '.change-status', function () {

        const button = $(this);
        const cashboxe_id = button.data('id');
        const status = button.data('status');

        if (
            !cashboxe_id ||
            !['active', 'inactive'].includes(status)
        ) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'بيانات تغيير حالة الصندوق غير صحيحة.',
            });

            return;
        }

        const actionText = status === 'active'
            ? 'تفعيل'
            : 'تعطيل';

        Swal.fire({
            icon: 'question',
            title: `هل تريد ${actionText} الصندوق؟`,
            text: 'سيتم تغيير حالة الصندوق دون تعديل أرصدته.',
            showCancelButton: true,
            confirmButtonText: 'نعم، متابعة',
            cancelButtonText: 'إلغاء',
        }).then(function (result) {

            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: changeStatusUrl,
                type: 'POST',
                dataType: 'json',

                data: {
                    _token: csrfToken,
                    cashboxe_id: cashboxe_id,
                    status: status,
                },

                beforeSend: function () {
                    button.prop('disabled', true);
                },

                success: function (response) {

                    if (response.status) {

                        Swal.fire({
                            icon: 'success',
                            title: 'نجاح',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false,
                        });

                        // إعادة تحميل الجدول لإظهار الحالة الجديدة.
                        loadData(searchUrl);
                        // تحديث التنبيهات فورًا دون تحديث الصفحة.
        refreshCashboxNotices();

                    } else {

                        Swal.fire({
                            icon: 'warning',
                            title: 'تنبيه',
                            text: response.message ||
                                'لم يتم تغيير حالة الصندوق.',
                        });

                    }
                },

                error: function (xhr) {
                    const response = xhr.responseJSON;
                    let message = 'حدث خطأ أثناء تحديث حالة الصندوق.';

                    if (xhr.status === 422 && response) {
                        const errors = response.errors || {};

                        message = response.message ||
                            Object.values(errors)
                                .flat()
                                .join('\n') ||
                            'بيانات الطلب غير صحيحة.';

                    } else if (xhr.status === 404) {
                        message = 'الصندوق المطلوب غير موجود.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'تعذر تغيير الحالة',
                        text: message,
                        confirmButtonText: 'حسنًا',
                    });
                },

                complete: function () {
                    button.prop('disabled', false);
                },
            });
        });
    });
    const noticesUrl = @json(route('admin.cashboxes.notices'));

function refreshCashboxNotices() {
    $.ajax({
        url: noticesUrl,
        type: 'GET',
        dataType: 'json',
        cache: false,

        success: function (response) {
            $('#cashbox-notices').html(response.html);
        },

        error: function () {
            console.error('تعذر تحديث تنبيهات الصناديق.');
        }
    });
}

});
</script>
@endsection
