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
                    <th>تاريخ الحذف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $row)
                    <tr>

                        <td>{{ $row->organization_id }}</td>

                        <td>{{ $row->name }}</td>

                        <td>{{ $row->commercial_registration }}</td>

                        <td>{{ $row->tax_number }}</td>

                        <td>{{ $row->mobile }}</td>

                        <td>{{ $row->email }}</td>

                        <td>{{ $row->city }}</td>

                        <td>
                            <span class="deleted-date">
                                {{ $row->deleted_at_formatted }}
                            </span>
                        </td>

                        <td>
                            <div class="sTable-actions">

                                <x-button
                          :entity="'المؤسسة'"
:entity-plural="'المؤسسات'"
                                    :restore-route="route(
                                        'admin.organizations.restore',
                                        $row->organization_id
                                    )"

                                    :force-delete-route="route(
                                        'admin.organizations.forceDestroy',
                                        $row->organization_id
                                    )"

                                    :deleted-id="$row->organization_id"
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
        لا توجد بيانات لعرضها
    </div>

@endif