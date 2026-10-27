
<x-constructor-table
    :entity="'الدفعة'"
    :entity-plural="'الدفعات'"
   

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
