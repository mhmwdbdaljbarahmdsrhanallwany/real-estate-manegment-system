<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <i class="fas fa-building text-primary"></i>

        <span class="font-weight-bold">
            المؤسسات المشتركة بهذه الباقة
        </span>
        <span class="badge badge-primary">
            {{ $package->organizations->count() }}
            مؤسسة
        </span>
    </div>
    <div class="card-body">
        @if ($package->subscriptions->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="thead-light">
                        <tr>
                            <th> المؤسسة</th>
                            <th>   نوع الاشتراك  </th>
                            <th>    الحالة</th>
                             <th>البداية</th>
                             <th>النهاية</th>
                              <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                         @foreach ($package->subscriptions as $subscription)
                            @php
                                $organization = $subscription->organization;
                            @endphp
                            <tr>
                                {{-- المؤسسة --}}
                                <td>
                                     <div class="media align-items-center">
                                        @if ($organization->logo)
                                            <img src="{{ asset('storage/' . $organization->logo) }}" width="45"
                                                height="45" class="rounded-circle mr-2">
                                        @else
                                            <div class="rounded-circle bg-light mr-2"
                                                style="width:45px;height:45px;line-height:45px">
                                                <i class="fas fa-building text-secondary"></i>
                                            </div>
                                        @endif
                                        <div class="text-left">
                                            <div class="font-weight-bold">
                                                {{ $organization->name }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $organization->commercial_name }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                {{-- النوع --}}
                                <td>
                                    <span class="badge badge-info">
                                        {{ $subscription->subscription_type_label }}
                                    </span>
                                </td>
                                {{-- الحالة --}}
                                <td>
                                    @switch($subscription->status)
                                        @case('active')
                                            <span class="badge badge-success">
                                                نشط
                                            </span>
                                        @break
                                        @case('expired')
                                            <span class="badge badge-danger">
                                                منتهي
                                            </span>
                                        @break
                                        @case('cancelled')
                                            <span class="badge badge-secondary">
                                                ملغي
                                            </span>
                                        @break
                                        @default
                                            <span class="badge badge-warning">
                                                انتظار
                                            </span>
                                    @endswitch
                                </td>
                                {{-- البداية --}}
                                <td>
                                    {{ optional($subscription->start_date)->format('Y-m-d') ?? '-' }}
                                </td>
                                {{-- النهاية --}}
                                <td>
                                    {{ optional($subscription->end_date)->format('Y-m-d') ?? '-' }}
                                </td>
                                {{-- الإجراء --}}
                                <td>
                                    <a href="{{ route('admin.organizations.show', $organization->organization_id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> عرض
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-building fa-3x mb-3"></i>
                <h6>
                    لا توجد مؤسسات مشتركة بهذه الباقة
                </h6>
            </div>
        @endif
    </div>
</div>
