
    <!-- جدول المؤسسات -->
    
        @if (@isset($data)  and @count($data)>0 )
        <table class="table table-hover table-bordered align-middle text-center">
          <thead class="table-primary">
            <tr>
              <th>المعرف</th>
              <th>اسم المؤسسة</th>
              <th>السجل التجاري</th>
              <th>الرقم الضريبي</th>
              <th>البريد الإلكتروني</th>
              <th>الهاتف</th>
              <th>الحالة</th>
              <th>الاشتراك</th>
              <th>الإجراءات</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              @foreach ($data as $info)
                  
              
              <td>{{ $info->agency_id }}</td>
              <td>{{ $info->agency_name }}</td>
              <td>{{ $info->commercial_registration }}</td>
              <td>{{ $info->tax_number }}</td>
              <td>{{ $info->email }}</td>
              <td>{{ $info->phone }}</td>
              <td><span class="badge bg-success">{{ $info->status }}</span></td>
              <td><span class="badge bg-primary">{{ $info->subscription_status }}</span></td>
              <td>
              {{-- coponent-button --}}
              <x-button 
              :show-route="route('admin.agencies.detaile', $info->agency_id)" 
              :edit-route="route('admin.agencies.edit', $info->agency_id)" 
              :delete-route="route('admin.agencies.delete', $info->agency_id)" 
              :deleted-id="$info->agency_id" />
              </td>
            </tr>
            <!-- باقي المؤسسات -->
            @endforeach
          </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center mt-2" >
          <div>
            إظهار {{ $data->firstItem() }} إلى {{ $data->lastItem() }} من أصل {{ $data->total() }} مؤسسة
          </div>
          <div id="ajax_serch">
            {{ $data->links() }}
          </div>
        </div>
        @else
            <p style="text-align: center;color: brown;margin-top: 20px">لا توجد بيانات لعرضها</p>
            @endif
      