<div class="card">

    <div class="card-header">
    اشتراكات المؤسسة
    </div>
    
    
    <div class="card-body">
    
    
    <table class="table table-bordered">
    
    
    <thead>
    
    <tr>
    
    <th>
    الباقة
    </th>
    
    <th>
    النوع
    </th>
    
    <th>
    الحالة
    </th>
    
    <th>
    البداية
    </th>
    
    <th>
    النهاية
    </th>
    
    </tr>
    
    </thead>
    
    
    <tbody>
    
    
    @foreach($organization->subscriptions as $subscription)
    
    
    <tr>
    
    <td>
    {{ $subscription->package->name }}
    </td>
    
    
    <td>
    {{ $subscription->subscription_type_label }}
    </td>
    
    
    <td>
    {{ $subscription->status_label }}
    </td>
    
    
    <td>
    {{ $subscription->start_date }}
    </td>
    
    
    <td>
    {{ $subscription->end_date }}
    </td>
    
    
    </tr>
    
    
    @endforeach
    
    
    </tbody>
    
    
    </table>
    
    
    </div>
    
    </div>