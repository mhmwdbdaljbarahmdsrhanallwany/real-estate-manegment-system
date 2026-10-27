
@if (isset($data) && $data->count() > 0)

    <div class="sTable-wrap">
        <table class="sTable">
            <thead>
                <tr>
                    <th>المعرف</th>
                    <th>اسم المؤسسة</th>
                    <th>السجل التجاري</th>
                    <th>الرقم الضريبي</th>
                    <th>الجوال</th>
                    <th>البريد الإلكتروني</th>
                    <th>المدينة</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $info)
                    <tr>

                        <td>{{ $info->organization_id }}</td>

                        <td>{{ $info->name }}</td>

                        <td>{{ $info->commercial_registration ?? '-' }}</td>

                        <td>{{ $info->tax_number ?? '-' }}</td>

                        <td>{{ $info->mobile ?? '-' }}</td>

                        <td>{{ $info->email ?? '-' }}</td>

                        <td>{{ $info->city ?? '-' }}</td>

                        <td>
                            <span class="sTable-badge sTable-badge-success">
                                {{ $info->status_label }}
                            </span>
                        </td>

                        <td>
                            <div class="sTable-actions">

                                <x-button
                                :entity="'المؤسسة'"
:entity-plural="'المؤسسات'"
                                    :show-route="route(
                                        'admin.organizations.show',
                                        $info->organization_id
                                    )"

                                    :edit-route="route(
                                        'admin.organizations.edit',
                                        $info->organization_id
                                    )"

                                    :delete-route="route(
                                        'admin.organizations.destroy',
                                        $info->organization_id
                                    )"

                                    :deleted-id="$info->organization_id"
                                />

                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <div class="sTable-footer">

        <div class="sTable-info">
            إظهار
            <strong>{{ $data->firstItem() }}</strong>
            إلى
            <strong>{{ $data->lastItem() }}</strong>
            من أصل
            <strong>{{ $data->total() }}</strong>
            مؤسسة
        </div>

        <div class="sTable-pagination">
            {!! $data->links() !!}
        </div>

    </div>

@else

    <div class="sTable-empty">
        <i class="fas fa-inbox"></i>
        لا توجد مؤسسات لعرضها
    </div>

@endif