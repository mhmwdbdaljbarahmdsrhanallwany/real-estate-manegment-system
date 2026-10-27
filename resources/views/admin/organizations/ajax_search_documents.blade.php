@if (isset($organizationDocuments) && $organizationDocuments->count() > 0)

    <div class="sTable-wrap">
        <table class="sTable">

            <thead>
                <tr>
                    <th>المعرف</th>
                    <th>اسم المؤسسة</th>
                    <th>السجل التجاري</th>
                    <th>الرقم الضريبي</th>
                    <th>رخصة البلدية</th>
                    <th>رخصة الوساطة العقارية</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($organizationDocuments as $info)

                    <tr>

                        <td>{{ $info->organization_id }}</td>

                        <td>{{ $info->name }}</td>

                        <td>{{ $info->commercial_registration ?? '-' }}</td>

                        <td>{{ $info->tax_number ?? '-' }}</td>

                        {{-- رخصة البلدية --}}
                        <td>

                            @if ($info->municipality_license)

                                <a href="{{ asset('storage/' . $info->municipality_license) }}"
                                   target="_blank"
                                   class="text-primary">

                                    <i class="fas fa-file-pdf text-danger"></i>
                                    عرض الملف

                                </a>

                            @else

                                <span class="text-muted">
                                    لا يوجد ملف
                                </span>

                            @endif

                        </td>

                        {{-- رخصة الوساطة العقارية --}}
                        <td>

                            @if ($info->real_estate_broker_license)

                                <a href="{{ asset('storage/' . $info->real_estate_broker_license) }}"
                                   target="_blank"
                                   class="text-primary">

                                    <i class="fas fa-file-pdf text-danger"></i>
                                    عرض الملف

                                </a>

                            @else

                                <span class="text-muted">
                                    لا يوجد ملف
                                </span>

                            @endif

                        </td>

                        {{-- الحالة --}}
                        <td>
                            <span class="sTable-badge sTable-badge-success">
                                {{ $info->status_label }}
                            </span>
                        </td>

                        {{-- الإجراءات --}}
                        <td>

                            @if ($info->status == 'pending_approval')

                                <x-button :check="[
                                    'id' => $info->organization_id,
                                    'status' => 'active',
                                    'title' => 'اعتماد',
                                    'class' => 'approve'
                                ]"/>

                                <x-button :check="[
                                    'id' => $info->organization_id,
                                    'status' => 'suspended',
                                    'title' => 'تعليق',
                                    'class' => 'suspend'
                                ]"/>

                            @elseif ($info->status == 'active')

                                <x-button :check="[
                                    'id' => $info->organization_id,
                                    'status' => 'pending_approval',
                                    'title' => 'إعادة للمراجعة',
                                    'class' => 'review'
                                ]"/>

                                <x-button :check="[
                                    'id' => $info->organization_id,
                                    'status' => 'suspended',
                                    'title' => 'تعليق',
                                    'class' => 'suspend'
                                ]"/>

                            @elseif ($info->status == 'suspended')

                                <x-button :check="[
                                    'id' => $info->organization_id,
                                    'status' => 'active',
                                    'title' => 'اعتماد',
                                    'class' => 'approve'
                                ]"/>

                                <x-button :check="[
                                    'id' => $info->organization_id,
                                    'status' => 'pending_approval',
                                    'title' => 'إعادة للمراجعة',
                                    'class' => 'review'
                                ]"/>

                            @endif

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>
    </div>

    <div class="sTable-footer">

        <div class="sTable-info">
            إظهار
            <strong>{{ $organizationDocuments->firstItem() }}</strong>
            إلى
            <strong>{{ $organizationDocuments->lastItem() }}</strong>
            من أصل
            <strong>{{ $organizationDocuments->total() }}</strong>
            مؤسسة
        </div>

        <div class="sTable-pagination">
            {!! $organizationDocuments->links() !!}
        </div>

    </div>

@else

    <div class="sTable-empty">
        <i class="fas fa-inbox"></i>
        لا توجد مستندات لعرضها
    </div>

@endif