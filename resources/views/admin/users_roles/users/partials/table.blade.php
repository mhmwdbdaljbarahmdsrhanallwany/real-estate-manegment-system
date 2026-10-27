
<div class="sTable-wrap">
    <table class="sTable">
        <thead>
            <tr>
                <th>المعرف</th>
                <th>اسم المستخدم</th>
                <th>البريد الإلكتروني</th>
                <th>سياقات الوصول</th>
                <th>الأدوار</th>
                <th>تاريخ الإنشاء</th>
                <th>الإجراءات</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($data as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->assignments_summary ?? '—' }}</td>
                    <td>{{ $row->roles_summary ?? '—' }}</td>
                    <td>{{ $row->created_at }}</td>

                    <td>
                        <div class="sTable-actions">
                       

                            @if ($row->trashed())
                            <a href="javascript:void(0);"
                               class="btn btn-sm btn-success restoreBtn"
                               data-id="{{ $row->id }}"
                               data-url="{{ route('admin.users.restore', ['id' => $row->id]) }}"
                               data-entity="المستخدم"
                               data-entity-plural="المستخدمين"
                               title="استعادة المستخدم">
                                <i class="fas fa-trash-restore"></i>
                            </a>
                        @else
                            <x-button
                                :show-route="route('admin.users.show', $row->id)"
                                :edit-route="route('admin.users.edit', $row->id)"
                                :delete-route="route('admin.users.delete', ['id' => $row->id])"
                                :deleted-id="$row->id"
                                :entity="'المستخدم'"
                                :entity-plural="'المستخدمين'"
                            />
                        @endif
                        

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        لا توجد بيانات لعرضها
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="sTable-footer">
    <div class="sTable-info">
        إظهار
        <strong>{{ $data->firstItem() ?? 0 }}</strong>
        إلى
        <strong>{{ $data->lastItem() ?? 0 }}</strong>
        من أصل
        <strong>{{ $data->total() }}</strong>
    </div>

    <div class="sTable-pagination">
        {{ $data->links() }}
    </div>
</div>
