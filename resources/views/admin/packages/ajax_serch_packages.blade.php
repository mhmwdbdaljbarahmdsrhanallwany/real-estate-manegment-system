
@if(isset($data) && count($data) > 0)

<div class="sTable-wrap">
    <table class="sTable">
    <thead >
        <tr>
          
            <th>المعرف</th>
            <th>اسم الباقة</th>
            <th>الرمز</th>
            <th>المستوى</th>
            <th>السعر الأساسي</th>
            <th>دورة الفوترة</th>
            <th>مدة الاشتراك (يوم)</th>
            <th>قيمة الخصم</th>
            <th>قيمة الضريبة</th>
            <th>المستخدمين</th>
            <th>الفروع</th>
            <th>العقارات</th>
            <th>الوحدات</th>
            <th>التخزين (MB)</th>
            <th>طلبات API</th>
            <th>الحالة</th>
            <th>مميز</th>
            <th>الإجراءات</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $package)
        <tr>

            <td>{{ $package->package_id }}</td>

            <td>{{ $package->name }}</td>

            <td>
                <span >
                    {{ $package->code }}
                </span>
            </td>

            <td>
                <span >
                     {{ $package->level }}
                </span>
            </td>

            <td>
                {{ number_format($package->base_price, 2) }}
            </td>

            <td>
                @switch($package->billing_cycle)
                    @case('monthly') شهري @break
                    @case('yearly') سنوي @break
                    @case('weekly') أسبوعي @break
                @endswitch
            </td>

            <td>{{ $package->duration_days }} يوم</td>

            <td>{{ $package->discount_value }}</td>

            <td>{{ $package->tax_value }}</td>

            <td>{{ $package->max_users }}</td>

            <td>{{ $package->max_branches }}</td>

            <td>{{ $package->max_properties }}</td>

            <td>{{ $package->max_units }}</td>

            <td>{{ $package->storage_limit_mb }} MB</td>

            <td>{{ $package->max_api_requests }}</td>

            <td>
                <span class="badge bg-{{ $package->is_active ? 'success' : 'danger' }}">
                    {{ $package->is_active ? 'نشطة' : 'غير نشطة' }}
                </span>
            </td>

            <td>
                <span class="badge bg-warning text-dark">
                    {{ $package->is_featured ? 'نعم' : 'لا' }}
                </span>
            </td>

            <td>
                <x-button 
                    :show-route="route('admin.packages.show', $package->package_id)" 
                    :edit-route="route('admin.packages.edit', $package->package_id)" 
                    :delete-route="route('admin.packages.delete', $package->package_id)" 
                    :deleted-id="$package->package_id" 
                />
            </td>

        </tr>
        @endforeach
    </tbody>
</table>
</div>
{{-- Pagination --}}
<div class="d-flex justify-content-between align-items-center mt-3" id="ajax_serch">
    <div>
        إظهار {{ $data->firstItem() }} إلى {{ $data->lastItem() }} من أصل {{ $data->total() }}
    </div>

    <div id="ajax_serch">
        {{ $data->links() }}
    </div>
</div>

@else
    <p class="text-center text-danger mt-3">لا توجد باقات لعرضها</p>
@endif