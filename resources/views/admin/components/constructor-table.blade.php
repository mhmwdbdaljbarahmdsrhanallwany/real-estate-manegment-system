

<div  class="sTable-card" style="margin-top: 10px">

    <!-- الهيدر -->
    <div class="sTable-header">
        <h5 class="sTable-title">
            <i class="{{ $icon ?? 'fas fa-building' }}"></i>
            {{ $title }}
        </h5>
        <button id="refreshBtn" type="button" class="sTable-refresh" data-toggle="tooltip" data-placement="top" title="تحديث بيانات الجدول">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>

    <!-- الجسم -->
    <div class="sTable-body">

        <!-- الفلاتر -->
        @if ((is_array($filters) && count($filters)) || (is_array($sorts) && count($sorts)))
        <div class="sTable-filters">
            @if (is_array($filters))
                @foreach ($filters as $key => $value)
                    @if (!is_array($value))
                        <div class="sTable-filter-group">
                            <div class="sTable-filter-wrap">
                                <i class="fas fa-search sTable-filter-icon"></i>
                                <input type="text" id="{{ $key }}" name="{{ $key }}"
                                class="sTable-filter filter-input" placeholder="{{ $value }}">
                            </div>
                        </div>
                    @else
                        <div class="sTable-filter-group" style="min-width:140px;">
                            <select id="{{ $key }}" class="sTable-filter filter-input"
                                name="{{ $key }}">
                                @foreach ($value as $optionKey => $optionLabel)
                                    <option value="{{ $optionKey }}">{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endforeach
            @endif

            @if (is_array($sorts))
                <div class="sTable-filter-group" style="min-width:auto; flex:0;">
                    <div class="sTable-sort-group">
                        <span class="sTable-sort-label">ترتيب حسب:</span>
                        <select id="sortBy" class="sTable-sort-select filter-input" name="sortBy">
                            @foreach ($sorts['sortBy'] as $optionKey => $optionLabel)
                                <option value="{{ $optionKey }}">{{ $optionLabel }}</option>
                            @endforeach
                        </select>
                        <select id="sort" class="sTable-sort-select filter-input" name="sort">
                            @foreach ($sorts['sort'] as $optionKey => $optionLabel)
                                <option value="{{ $optionKey }}">{{ $optionLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        </div>
        @endif

        <!-- الجدول -->
        <div id="{{ $tableid }}">
            @if (@empty($files))

                @if (isset($rows) && count($rows) > 0)
                    <div class="sTable-wrap">
                        <table class="sTable">
                            <thead>
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
                                                <td><span class="sTable-badge sTable-badge-success">{{ data_get($row, $field) }}</span></td>
                                            @elseif($field == 'subscription_status')
                                                <td><span class="sTable-badge sTable-badge-primary">{{ data_get($row, $field) }}</span></td>
                                            @elseif($field == 'storage_space')
                                                <td>{{ data_get($row, $field) }} GB</td>
                                            @elseif($field == 'plan_price' || $field == 'currency_label')
                                                <td>{{ data_get($row, $field) }} {{ data_get($row, 'currency_label') }}</td>
                                            @else
                                            <td>
                                                @if ($field === 'deleted_at_formatted')
                                                <span dir="ltr" style="direction: ltr; unicode-bidi: isolate; display: inline-block;">
                                                    &#8206;{{ data_get($row, $field, '') }}&#8206;
                                                </span>
                                            @else
                                                {{ data_get($row, $field, '') }}
                                            @endif
                                            </td>
                                            @endif
                                            @if ($field == 'is_active')
                                                <td>
                                                    <span class="sTable-badge {{ data_get($row, $field) ? 'sTable-badge-success' : 'sTable-badge-danger' }}">
                                                        {{ data_get($row, $field) ? 'نشطة' : 'غير نشطة' }}
                                                    </span>
                                                </td>
                                            @elseif($field == 'is_featured')
                                                <td>
                                                    <span class="sTable-badge sTable-badge-warning">
                                                        {{ data_get($row, $field) ? 'نعم' : 'لا' }}
                                                    </span>
                                                </td>
                                            @elseif($field == 'base_price')
                                                <td>{{ number_format(data_get($row, $field), 2) }} $</td>
                                            @elseif($field == 'billing_cycle')
                                                <td>
                                                    @switch(data_get($row, $field))
                                                        @case('monthly') شهري @break
                                                        @case('yearly') سنوي @break
                                                        @case('weekly') أسبوعي @break
                                                    @endswitch
                                                </td>
                                            @endif
                                        @endforeach
                                        @if ($actions ?? false)
                                        <td>
                                            <div class="sTable-actions">
                                    
                                                

                                                <x-button
                                                 :entity="$entity"
                                                :entity-plural="$entityPlural"
                                                :show-route="isset($actions['show']) ? $actions['show']($row) : null"
                                                :edit-route="isset($actions['edit']) ? $actions['edit']($row) : null"
                                                :delete-route="isset($actions['delete']) ? $actions['delete']($row) : null"
                                                :restore-route="isset($actions['restore']) ? $actions['restore']($row) : null"
                                                :force-delete-route="isset($actions['force_delete']) ? $actions['force_delete']($row) : null"
                                                :check="isset($actions['check']) ? $actions['check']($row) : null"
                                                :deleted-id="$row[$primaryKey]"
                                               
                                            />
                                            
                                    
                                            </div>
                                        </td>
                                    @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="sTable-footer">
                        <div class="sTable-info">
                            إظهار <strong>{{ $firstItem }}</strong> إلى <strong>{{ $lastItem }}</strong> من أصل <strong>{{ $total }}</strong> 
                        </div>
                        <div class="sTable-pagination" id="ajax_serch">
                            {!! $pagination !!}
                        </div>
                    </div>
                @else
                    <div class="sTable-empty">
                        <i class="fas fa-inbox"></i>
                        لا توجد بيانات لعرضها
                    </div>
                @endif

            @else

                @if (isset($rows) && count($rows) > 0)
                    <div class="sTable-wrap">
                        <table class="sTable">
                            <thead>
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
                                                <td><span class="sTable-badge sTable-badge-primary">{{ data_get($row, $value['name']) ?? '' }}</span></td>
                                            @elseif(!empty($value['type']) && $value['type'] == 'text')
                                                <td>{{ data_get($row, $value['name']) ?? '' }}</td>
                                            @else
                                                <td>
                                                    @php
                                                        $file = data_get($row, $value['name']);
                                                    @endphp
                                                    @if(!empty($file))
                                                        <a href="{{ asset('storage/'.$file) }}" target="_blank" class="sTable-file-link">
                                                            <i class="fas fa-file-pdf"></i>
                                                            {{ basename($file) }}
                                                        </a>
                                                    @else
                                                        <span style="color:#94a3b8;font-size:.78rem;">غير موجود</span>
                                                    @endif
                                                </td>
                                            @endif
                                        @endforeach
                                        @if ($actions ?? false)
                                            <td>
                                                <div class="sTable-actions">
                                                    @if ($actions['type'] == 'admin')
                                                        @if ($row->status == 'pending_approval')
                                                            <x-button :check="['id' => $row->organization_id, 'status' => 'active', 'title' => 'اعتماد', 'class' => 'approve']" />
                                                            <x-button :check="['id' => $row->organization_id, 'status' => 'suspended', 'title' => 'تعليق', 'class' => 'suspend']" />
                                                        @elseif ($row->status == 'active')
                                                            <x-button :check="['id' => $row->organization_id, 'status' => 'pending_approval', 'title' => 'إعادة للمراجعة', 'class' => 'review']" />
                                                            <x-button :check="['id' => $row->organization_id, 'status' => 'suspended', 'title' => 'تعليق', 'class' => 'suspend']" />
                                                        @else
                                                            <x-button :check="['id' => $row->organization_id, 'status' => 'active', 'title' => 'اعتماد', 'class' => 'approve']" />
                                                            <x-button :check="['id' => $row->organization_id, 'status' => 'pending_approval', 'title' => 'إعادة للمراجعة', 'class' => 'review']" />
                                                        @endif
                                                    @else
                                                        <x-button :show-route="$actions['show']($row)" :edit-route="$actions['edit']($row)" :delete-route="$actions['delete']($row)"
                                                            :deleted-id="$row[$primaryKey]"
                                                             />
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="sTable-footer">
                        <div class="sTable-info">
                            إظهار <strong>{{ $firstItem }}</strong> إلى <strong>{{ $lastItem }}</strong> من أصل <strong>{{ $total }}</strong>
                        </div>
                        <div class="sTable-pagination" id="ajax_serch">
                            {!! $pagination !!}
                        </div>
                    </div>
                @else
                    <div class="sTable-empty">
                        <i class="fas fa-inbox"></i>
                        لا توجد بيانات لعرضها
                    </div>
                @endif

            @endif
        </div>

    </div>
</div>
