@if (@isset($agencyDocument) and !@empty($agencyDocument) and @count($agencyDocument) > 0)
    <table class="table table-hover table-bordered align-middle text-center">
        <thead class="table-primary">
            <tr>
                <th>المعرف</th>
                <th>اسم المؤسسة</th>
                <th> السجل التجاري</th>
                <th> الرقم الضريبي</th>
                <th> رخصة البلدية</th>
                <th> رخصة الوساطة</th>
                <th> الحالة</th>

                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agencyDocument as $info)
                <tr>
                    <td>{{ $info->agency_id }}</td>
                    <td> {{ $info->agency_name }}</td>
                    <td> {{ $info->commercial_registration }}</td>
                    <td> {{ $info->tax_number }}</td>

                    <td>
                        @if (!empty($info->municipality_license))
                            @foreach ($info->municipality_license as $index => $doc)
                                <div class="mb-1">

                                    <a class="text-primary" href="{{ asset('storage/' . $doc['path']) }}" target="_blank">
                                        عرض <i class="fas fa-file-pdf"></i> {{ $doc['original_name'] ?? '' }}
                                    </a>

                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">لا توجد رخصة بلدية مرفوعة</p>
                        @endif
                    </td>
                    <td>
                        @if (!empty($info->real_estate_broker_license))
                            @foreach ($info->real_estate_broker_license as $index => $doc)
                                <div class="mb-1">

                                    <a class="text-primary" href="{{ asset('storage/' . $doc['path']) }}"
                                        target="_blank">
                                        عرض <i class="fas fa-file-pdf"></i> {{ $doc['original_name'] ?? '' }}
                                    </a>

                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">لا توجد رخصة بلدية مرفوعة</p>
                        @endif
                    </td>
                    <td><span class="badge bg-primary"> {{ $info->status_label }}</span></td>

                    <td>
                        @if ($info->status == 'pending_approval')
                            <x-button :check="[
                                'id' => $info->agency_id,
                                'status' => 'active',
                                'title' => 'اعتماد',
                                'class' => 'approve',
                            ]" />
                            <x-button :check="[
                                'id' => $info->agency_id,
                                'status' => 'suspended',
                                'title' => 'تعليق',
                                'class' => 'suspend',
                            ]" />
                        @elseif ($info->status == 'active')
                            <x-button :check="[
                                'id' => $info->agency_id,
                                'status' => 'pending_approval',
                                'title' => 'إعادة للمراجعة',
                                'class' => 'review',
                            ]" />
                            <x-button :check="[
                                'id' => $info->agency_id,
                                'status' => 'suspended',
                                'title' => 'تعليق',
                                'class' => 'suspend',
                            ]" />
                        @else
                            <x-button :check="[
                                'id' => $info->agency_id,
                                'status' => 'active',
                                'title' => 'اعتماد',
                                'class' => 'approve',
                            ]" />
                            <x-button :check="[
                                'id' => $info->agency_id,
                                'status' => 'pending_approval',
                                'title' => 'إعادة للمراجعة',
                                'class' => 'review',
                            ]" />
                        @endif

                    </td>

                </tr>
            @endforeach
            <!-- باقي المستندات -->
        </tbody>
    </table>
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div>
            إظهار {{ $agencyDocument->firstItem() }} إلى {{ $agencyDocument->lastItem() }} من أصل
            {{ $agencyDocument->total() }} بيانات المستندات
        </div>
        <div id="ajax_serch">
            {{ $agencyDocument->links() }}
        </div>
    </div>
@else
    <p style="text-align: center;color: brown;margin-top: 20px">لا توجد بيانات لعرضها</p>
@endif