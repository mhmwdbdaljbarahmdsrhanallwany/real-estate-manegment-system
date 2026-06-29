<div class="card shadow-lg border-0 rounded-3">
    <!-- الهيدر -->
    <div class="card-header bg-light">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
            <h4 class="mb-0 fw-semibold text-primary">
                <i class="{{ $icon ?? 'fas fa-building' }} me-2"></i> {{ $title }}
            </h4>
            <button id="refreshBtn" type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip"
                data-placement="top" title="تحديث بيانات الجدول">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <!-- الجسم -->
    <div class="card-body">
        <!-- الفلاتر -->
        <div class="row g-3 mb-3">
            @if (is_array($filters))
                @foreach ($filters as $key => $value)
                    @if (!is_array($value))
                        <div class="col-md-4">
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white border-0"><i class="fas fa-search"></i></span>
                                <input type="text" id="{{ $key }}" name="{{ $key }}"
                                    class="form-control {{ $id ?? 'filter-input'}} " placeholder="{{ $value }}">
                            </div>
                        </div>
                    @else
                        <div class="col-md-3">
                            <select id="{{ $key }}" class="form-select shadow-sm {{ $id ?? 'filter-input'}} "
                                name="{{ $key }}">
                                @foreach ($value as $optionKey => $optionLabel)
                                    <option value="{{ $optionKey }}">{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endforeach
            @endif

            {{-- الفلاتر الخاصة بالترتيب --}}
            @if (is_array($sorts))
                <div class="mb-0">
                    <label>ترتيب حسب:</label>
                    <select id="sortBy" class="form-select d-inline w-auto {{ $id ?? 'filter-input'}} " name="sortBy">
                        @foreach ($sorts['sortBy'] as $optionKey => $optionLabel)
                            <option value="{{ $optionKey }}">{{ $optionLabel }}</option>
                        @endforeach
                    </select>

                    <select id="sort" class="form-select d-inline w-auto {{ $id ?? 'filter-input'}} " name="sort">
                        @foreach ($sorts['sort'] as $optionKey => $optionLabel)
                            <option value="{{ $optionKey }}">{{ $optionLabel }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <!-- الجدول -->
        <div class="table-responsive" id="{{ $tableid }}">
            @if (@empty($files))


                @if (isset($rows) && count($rows) > 0)
                    <table class="table table-hover table-bordered align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                @foreach ($columns as $col)
                                    <th>{{ $col }}</th>
                                @endforeach
                                @if ($actions ?? false)
                                    <th>الإجراءات</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    @foreach ($fields as $field)
                                        @if ($field == 'status' || $field == 'status_label' || $field == 'PlanStatus')
                                            <td><span class="badge bg-success">{{ data_get($row, $field) }}</span></td>
                                        @elseif($field == 'subscription_status')
                                            <td><span class="badge bg-primary">{{ data_get($row, $field) }}</span></td>
                                        @elseif($field == 'storage_space')
                                            <td>{{ data_get($row, $field) }} GB</td>
                                        @elseif($field == 'plan_price' || $field == 'currency_label')
                                            <td>{{ data_get($row, $field) }} {{ data_get($row, 'currency_label') }}
                                            </td>
                                        @else
                                            <td>{{ data_get($row, $field, '') }}</td>
                                        @endif
                                    @endforeach
                                    @if ($actions ?? false)
                                        <td>
                                            <x-button :show-route="$actions['show']($row)" :edit-route="$actions['edit']($row)" :delete-route="$actions['delete']($row)"
                                                :deleted-id="$row[$primaryKey]" />
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div>
                            إظهار {{ $firstItem }} إلى {{ $lastItem }} من أصل {{ $total }} مؤسسة
                        </div>
                        <div id="ajax_serch">
                            {!! $pagination !!}
                        </div>

                    </div>
                @else
                    <p class="text-muted text-center mt-3">لا توجد بيانات لعرضها</p>
                @endif
            @else
                @if (isset($rows) && count($rows) > 0)
                    <table class="table table-hover table-bordered align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                @foreach ($columns as $col)
                                    <th>{{ $col }}</th>
                                @endforeach
                                @if ($actions ?? false)
                                    <th>الإجراءات</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    @foreach ($files as $key => $value)
                                        @if (!empty($value['type']) && $value['name'] == 'status_label')
                                            <td><span
                                                    class="badge bg-primary">{{ data_get($row, $value['name']) ?? '' }}</span>
                                            </td>
                                        @elseif(!empty($value['type']) && $value['type'] == 'text')
                                            <td>{{ data_get($row, $value['name']) ?? '' }}</td>
                                        @else
                                            <td>

                                                @php
                                                    $uploadedFiles = data_get($row, $value['name'], []);
                                                @endphp


                                                @if (is_array($uploadedFiles))
                                                    @foreach ($uploadedFiles as $index => $file)
                                                        <div class="mb-1">

                                                            <a class="text-primary"
                                                                href="{{ asset('storage/' . $file['path']) }}"
                                                                target="_blank">
                                                                عرض <i class="fas fa-file-pdf"></i>
                                                                {{ $file['original_name'] ?? '' }}
                                                            </a>

                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p class="text-muted">غير موجود </p>
                                                @endif

                                            </td>
                                        @endif
                                    @endforeach
                                    @if ($actions ?? false)
                                        @if ($actions['type'] == 'admin')
                                            <td>

                                                @if ($row->status == 'pending_approval')
                                                    <x-button :check="[
                                                        'id' => $row->agency_id,
                                                        'status' => 'active',
                                                        'title' => 'اعتماد',
                                                        'class' => 'approve',
                                                    ]" />
                                                    <x-button :check="[
                                                        'id' => $row->agency_id,
                                                        'status' => 'suspended',
                                                        'title' => 'تعليق',
                                                        'class' => 'suspend',
                                                    ]" />
                                                @elseif ($row->status == 'active')
                                                    <x-button :check="[
                                                        'id' => $row->agency_id,
                                                        'status' => 'pending_approval',
                                                        'title' => 'إعادة للمراجعة',
                                                        'class' => 'review',
                                                    ]" />
                                                    <x-button :check="[
                                                        'id' => $row->agency_id,
                                                        'status' => 'suspended',
                                                        'title' => 'تعليق',
                                                        'class' => 'suspend',
                                                    ]" />
                                                @else
                                                    <x-button :check="[
                                                        'id' => $row->agency_id,
                                                        'status' => 'active',
                                                        'title' => 'اعتماد',
                                                        'class' => 'approve',
                                                    ]" />
                                                    <x-button :check="[
                                                        'id' => $row->agency_id,
                                                        'status' => 'pending_approval',
                                                        'title' => 'إعادة للمراجعة',
                                                        'class' => 'review',
                                                    ]" />
                                                @endif




                                            </td>
                                        @else
                                            <td>
                                                <x-button :show-route="$actions['show']($row)" :edit-route="$actions['edit']($row)" :delete-route="$actions['delete']($row)"
                                                    :deleted-id="$row[$primaryKey]" />
                                            </td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div>
                            إظهار {{ $firstItem }} إلى {{ $lastItem }} من أصل {{ $total }} 
                        </div>
                        <div id="ajax_serch">
                            {!! $pagination !!}
                        </div>

                    </div>
                @else
                    <p class="text-muted text-center mt-3">لا توجد بيانات لعرضها</p>
                @endif
            @endif
        </div>
    </div>
</div>
