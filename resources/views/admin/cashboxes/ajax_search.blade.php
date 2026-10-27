<x-constructor-table
    :entity="'الصندوق'"
    :entity-plural="'الصناديق'"
    title="قائمة الصناديق المالية"

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