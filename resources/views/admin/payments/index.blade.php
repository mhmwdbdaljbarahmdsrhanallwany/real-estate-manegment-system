
@extends('admin.layout.master')

@section('title')
    إدارة الدفعات
@endsection

@section('contect')


<x-constructor-table
    title="قائمة الدفعات"
    icon="fas fa-money-bill-wave me-2"

    :filters="[
        'search' => 'بحث برقم الدفعة أو المرجع...',

        'status' => [
            'all' => 'كل الحالات',
            'success' => 'ناجحة',
            'pending' => 'معلقة',
            'failed' => 'فاشلة',
        ],

        'payment_method' => [
            'all' => 'كل طرق الدفع',
            'cash' => 'نقدي',
            'bank' => 'تحويل بنكي',
            'card' => 'بطاقة',
            'online' => 'إلكتروني',
        ],
    ]"

    :sorts="[
        'sortBy' => [
            'payment_id' => 'رقم الدفعة',
            'amount' => 'المبلغ',
            'paid_at' => 'تاريخ الدفع',
        ],

        'sort' => [
            'asc' => 'تصاعدي',
            'desc' => 'تنازلي',
        ],
    ]"

    tableid="ajax_responce"

    :columns="[
        'رقم الدفعة',
        'رقم الفاتورة',
        'رقم الاشتراك',
        'المبلغ',
        'طريقة الدفع',
        'تاريخ الدفع',
        'الحالة',
    ]"

    :fields="[
        'payment_id',
        'invoice_id',
        'subscription_id',
        'amount',
        'payment_method',
        'paid_at',
        'status',
    ]"

    :rows="$payments"
    :primary-key="'payment_id'"

    :first-item="$payments->firstItem()"
    :last-item="$payments->lastItem()"
    :total="$payments->total()"
    :pagination="$payments->links()"
/>

@endsection

@section('script')
<script>
$(document).ready(function () {

    const searchUrl = @json(route('admin.payments.ajax_search'));

    $("#ajax_responce").empty();

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
                $("#ajax_responce").html(html);
            },

            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء جلب بيانات الدفعات.',
                });
            }
        });
    }

    // تحميل الدفعات عند فتح الصفحة.
    loadData(searchUrl);

    // البحث والتصفية.
    $(document).on('input change', '.filter-input', function () {
        loadData(searchUrl);
    });

    // التنقل بين صفحات النتائج.
    $(document).on(
        'click',
        '#ajax_responce .pagination a',
        function (e) {
            e.preventDefault();
            loadData($(this).attr('href'));
        }
    );

    // زر التحديث الذي يوفره المكوّن.
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

});
</script>
@endsection
