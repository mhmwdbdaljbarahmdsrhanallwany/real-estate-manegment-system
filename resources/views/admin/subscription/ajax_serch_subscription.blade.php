
@if(isset($data) && count($data) > 0)
<table class="table table-hover table-bordered align-middle text-center">
  <thead class="table-primary">
    <tr>
      <th>المعرف</th>
      <th>اسم الباقة</th>
      <th>المستوى</th>
      <th>السعر</th>
      <th>المدة</th>
      <th>نوع الباقة</th>
      <th>الفترة التجريبية</th>
      <th>غرامة التأخير</th>
      <th>فترة السماح</th>
      <th>عدد المستخدمين</th>
      <th>عدد الفروع</th>
      <th>مساحة التخزين</th>
      <th>الحالة</th>
      <th>الميزات</th>
      <th>الإجراءات</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $plan)
    <tr>
      <td>{{ $plan->plan_id }}</td>
      <td>{{ $plan->plan_name }}</td>
      <td>{{ $plan->plan_tier_label }}</td>
      <td>{{ $plan->plan_price }} {{ $plan->currency_label }}</td>
      <td>{{ $plan->duration }} يوم</td>
      <td>{{ $plan->subscription_type_label }}</td>
      <td>
        {{ $plan->trial_period_value }}
        {{ $plan->trial_period_unit_label }}
      </td>
      <td>{{ $plan->late_fee }}</td>
      <td>{{ $plan->grace_period }} يوم</td>
      <td>{{ $plan->max_users }}</td>
      <td>{{ $plan->max_branches }}</td>
      <td>{{ $plan->storage_space }} GB</td>
      <td>
        @php
            $statusColors = [
                'pending_approval'=>'bg-secondary',
                'active' => 'bg-success',
                'suspended'=> 'bg-warning',
                'blocked' => 'bg-danger',
                'expired'=> 'bg-dark',
            ];
        @endphp
        <span class="badge {{ $statusColors[$plan->status] ?? 'bg-info' }}">
            {{ $plan->status_label }}
        </span>
      </td>
      <td>{{ $plan->features }}</td>
      <td>
          {{-- coponent-button --}}
          <x-button 
          :show-route="route('admin.subscription.detaile',$plan->plan_id)" 
          :edit-route=" route('admin.subscription.edit',$plan->plan_id)" 
          :delete-route="route('admin.subscription.delete',$plan->plan_id)" 
          :deleted-id="$plan->plan_id" />
      
      </td>
    </tr>
    @endforeach
  </tbody>
</table> 
<div class="d-flex justify-content-between align-items-center mt-3" id="ajax_serch">
  <div>
    إظهار {{ $data->firstItem() }} إلى {{ $data->lastItem() }} من أصل {{ $data->total() }} 
  </div>
  <div>
    {{ $data->links() }}
  </div>
</div>     
@else
  <p class="text-center text-danger mt-3">لا توجد باقات لعرضها</p>
@endif