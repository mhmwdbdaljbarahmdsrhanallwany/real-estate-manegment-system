<div class="container-fluid">
    {{-- رأس الصفحة --}}
    <x-page-header
        title="{{ $actionType->name }}"
        icon="fas fa-tasks"
        code="{{ $actionType->code }}"
    >
        @if($actionType->is_system)
            <span class="badge bg-primary px-3 py-2">
                <i class="fas fa-lock"></i>
                إجراء نظام
            </span>
        @else
            <span class="badge bg-secondary px-3 py-2">
                <i class="fas fa-user"></i>
                إجراء مخصص
            </span>
        @endif
    </x-page-header>
    {{-- إحصائيات الإجراء --}}
    <div class="row mb-4">
        <x-stat-card
            title="عدد مرات التنفيذ"
            :value="$actionType->actions_count"
            icon="fas fa-play-circle"
            color="primary"
        />
        <x-stat-card
            title="إرسال الإشعارات"
            :value="$actionType->send_notification ? 'مفعل' : 'غير مفعل'"
            icon="fas fa-bell"
            color="warning"
        />
        <x-stat-card
            title="سجل المراجعة"
            :value="$actionType->write_audit ? 'مفعل' : 'غير مفعل'"
            icon="fas fa-clipboard-check"
            color="success"
        />
        <x-stat-card
            title="حالة الإجراء"
            :value="$actionType->is_active ? 'فعال' : 'معطل'"
            icon="fas fa-toggle-on"
            color="info"
        />
    </div>
    {{-- معلومات الإجراء --}}
    <x-info-card
        title="معلومات الإجراء"
        icon="fas fa-info-circle"
    >
        <table class="table table-bordered align-middle">
            <tr>
                <th width="200">
                    اسم الإجراء
                </th>
                <td>
                    {{ $actionType->name }}
                </td>
            </tr>
            <tr>
                <th>
                    الكود
                </th>
                <td>
                    <code>
                        {{ $actionType->code }}
                    </code>
                </td>
            </tr>
            <tr>
                <th>
                    الوصف
                </th>
                <td>
                    {{ $actionType->description ?? 'لا يوجد وصف' }}
                </td>
            </tr>
            <tr>
                <th>
                    نوع الإجراء
                </th>
                <td>
                    @if($actionType->is_system)
                        <span class="badge bg-primary">
                            إجراء نظام
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            إجراء مخصص
                        </span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>
                    ترتيب العرض
                </th>
                <td>
                    {{ $actionType->sort_order }}
                </td>
            </tr>
            <tr>
                <th>تاريخ الإنشاء
                </th>
                <td>
                    {{ $actionType->created_at?->format('Y-m-d H:i') ?? 'غير محدد' }}
                </td>
            </tr>
        </table>
    </x-info-card>
    {{-- آخر التنفيذات --}}
    <x-info-card
        title="آخر تنفيذات الإجراء"
        icon="fas fa-history">
        @php
            $columns = ['المستخدم','المؤسسة','العنوان','التاريخ'];
            $fields = ['user.name','organization.organization_name','title','created_at'];
        @endphp
        <x-constructor-table
            title="آخر التنفيذات"
            icon="fas fa-history"
            :columns="$columns"
            :fields="$fields"
            :rows="$recentActions"
            :filters="[]"
            :actions="false"
            tableid="action-history"
        />
    </x-info-card>
</div>