
<x-constructor-table
    :title="$tab === 'deleted' ? 'الأدوار المحذوفة' : 'الأدوار الحالية'"
    icon="fas fa-user-shield"
    tableid="rolesTable"
      :entity="'الدور'"
:entity-plural="'الأدوار'"
    :columns="[
        'المعرف',
        'اسم الدور',
        'الاسم البرمجي',
        'الحارس',
        'عدد إسنادات الأدوار',
        'الحالة',
        'تاريخ الإنشاء',
    ]"

    :fields="[
        'id',
        'display_name',
        'name',
        'guard_name',
        'user_roles_count',
        'status',
        'created_at',
    ]"

    :rows="$roles"
  
    :actions="[
    'show' => fn($row) => route('admin.roles.show', $row->id),

    'edit' => fn($row) => $tab === 'current'
        ? route('admin.roles.edit', $row->id)
        : null,
        'check' => fn($row) => $tab === 'current'
    ? [
        'id' => $row->id,
        'status' => $row->status === 'ACTIVE'
            ? 'INACTIVE'
            : 'ACTIVE',
        'title' => $row->status === 'ACTIVE'
            ? 'تعطيل الدور'
            : 'تفعيل الدور',
        'class' => $row->status === 'ACTIVE'
            ? 'suspend'
            : 'approve',
    ]
    : null,

    'restore' => fn($row) => $tab === 'deleted'
        ? route('admin.roles.restore', $row->id)
        : null,

    'delete' => fn($row) => $tab === 'current'
        ? route('admin.roles.destroy', $row->id)
        : null,
        'force_delete' => fn($row) => $tab === 'deleted'
    ? route('admin.roles.forceDestroy', $row->id)
    : null,
]"


    :primary-key="'id'"
    :first-item="$roles->firstItem()"
    :last-item="$roles->lastItem()"
    :total="$roles->total()"
    :pagination="$roles->links()"
/>
