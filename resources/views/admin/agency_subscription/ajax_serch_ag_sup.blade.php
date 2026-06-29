@if (@isset($data) and !@empty($data) and @count($data)>0 )
      <table class="table table-hover table-bordered align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>المعرف</th>
            <th>المؤسسة</th>
            <th>الباقة</th>
            <th>نوع الاشتراك</th>
            <th>تاريخ البداية</th>
            <th>تاريخ الانتهاء</th>
            <th>الحالة</th>
            <th>السعر</th>
            <th>المدفوع</th>
            <th>المتبقي</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data as $info)
          <tr>
            <td>{{ $info->AgencySubscriptionID }}</td> 
            <td>{{ $info->agency->agency_name ?? '-' }}</td>
            <td>{{ $info->plan->plan_name ?? '-' }}</td>
            <td>{{ $info->SubscriptionType }}</td>
            <td>{{ $info->StartDate }}</td>
            <td>{{ $info->EndDate }}</td>
            <td><span class="badge bg-info">{{ $info->PlanStatus }}</span></td>
            <td>{{ $info->PlanPrice }}</td>
            <td>{{ $info->AmountPaid }}</td>
            <td>{{ $info->AmountRemaining }}</td>
            <td>
              <x-button 
              :show-route="route('admin.agencysubscription.detaile', $info->AgencySubscriptionID)" 
              :edit-route="route('admin.agencysubscription.edit', $info->AgencySubscriptionID)" 
              :delete-route="route('admin.agencysubscription.delete', $info->AgencySubscriptionID)" 
              :deleted-id="$info->agency_id" />
           
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
       <!-- روابط الصفحات -->
      <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
          إظهار {{ $data->firstItem() }} إلى {{ $data->lastItem() }} من أصل {{ $data->total() }} اشتراك
        </div>
        <div id="ajax_serch">
          {{ $data->links() }}
        </div>
      </div>
      @else
        <p class="text-center text-danger mt-3">لا توجد بيانات لعرضها</p>
      @endif