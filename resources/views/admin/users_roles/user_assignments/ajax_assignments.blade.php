
<x-constructor-table
    tableid="ajax_responce"

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
