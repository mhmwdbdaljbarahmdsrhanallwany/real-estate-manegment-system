
@extends('admin.layout.master')

@section('title')
    إدارة الفواتير
@endsection

@section('contect')
    @include('admin.incloude.agencynavepare')

    <x-constructor-table
        title="قائمة الفواتير"
        icon="fas fa-file-invoice-dollar me-2"

        :filters="[
            'searchInvoice' => 'بحث برقم الفاتورة أو اسم المؤسسة...',

            'statusFilter' => [
                'all' => 'كل الحالات',
                'pending' => 'معلقة',
                'paid' => 'مدفوعة',
                'partial' => 'مدفوعة جزئيًا',
                'overdue' => 'متأخرة',
            ],
        ]"

        :sorts="[
            'sortBy' => [
                'invoice_id' => 'المعرف',
                'invoice_number' => 'رقم الفاتورة',
                'total_amount' => 'الإجمالي',
                'net_amount' => 'صافي المبلغ',
                'issue_date' => 'تاريخ الإصدار',
                'due_date' => 'تاريخ الاستحقاق',
                'status' => 'الحالة',
            ],

            'sort' => [
                'asc' => 'تصاعدي',
                'desc' => 'تنازلي',
            ],
        ]"

        tableid="ajax_responce"

        :columns="[
            'المعرف',
            'رقم الفاتورة',
            'المؤسسة',
            'الباقة',
            'الإجمالي',
            'الخصم',
            'الضريبة',
            'صافي المبلغ',
            'تاريخ الإصدار',
            'تاريخ الاستحقاق',
            'الحالة',
        ]"

        :fields="[
            'invoice_id',
            'invoice_number',
            'subscription.organization.name',
            'subscription.package.name',
            'total_amount',
            'discount_amount',
            'tax_amount',
            'net_amount',
            'issue_date',
            'due_date',
            'status',
        ]"

        :rows="$invoices"

        :actions="[
            'show' => fn($row) => route('admin.invoices.show', $row['invoice_id']),
        ]"

        :primary-key="'invoice_id'"

        :first-item="$invoices->firstItem()"
        :last-item="$invoices->lastItem()"
        :total="$invoices->total()"
        :pagination="$invoices->links()"
    />

@endsection

@section('script')
    <script>
        $(document).ready(function () {

            const searchUrl = @json(route('admin.invoices.ajax_search'));

            function loadData(url = searchUrl) {
                let filters = {};

                $('.filter-input').each(function () {
                    const name = $(this).attr('name');
                    const value = $(this).val();

                    if (name) {
                        filters[name] = value;
                    }
                });

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'html',
                    cache: false,

                    data: {
                        _token: @json(csrf_token()),
                        ...filters
                    },

                    success: function (invoices) {
                        $('#ajax_responce').html(invoices);
                    },

                    error: function (xhr) {
                        console.error(xhr.responseText);
                        alert('حدث خطأ أثناء جلب الفواتير.');
                    }
                });
            }

            // تحميل نتائج البحث عند فتح الصفحة
            loadData();

            // البحث والتصفية والترتيب
            $(document).on(
                'input change',
                '.filter-input',
                function () {
                    loadData();
                }
            );

            // التنقل بين صفحات النتائج
            $(document).on('click', '#ajax_responce .pagination a', function (e) {
                e.preventDefault();

                const url = $(this).attr('href');

                if (url) {
                    loadData(url);
                }
            });

            // زر تحديث النتائج، إذا كان المكون يوفره
            $(document).on('click', '#refreshBtn', function () {
                const icon = $(this).find('i');

                icon.addClass('fa-spin');

                loadData();

                setTimeout(function () {
                    icon.removeClass('fa-spin');
                }, 600);
            });

            // تفعيل التلميحات
            $('[data-toggle="tooltip"]').tooltip();

        });
    </script>
@endsection
