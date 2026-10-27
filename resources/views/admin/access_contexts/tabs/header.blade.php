@php
$subscription = $organization->subscriptions->last();

$documents = [
    $organization->municipality_license,
    $organization->real_estate_broker_license
];

$documentCount = collect($documents)->filter()->count();
@endphp

<div class="card shadow-sm border-0" style="margin-top: 7px; border-radius: 12px; background: #ffffff;">
    <div class="card-body py-3">
        <div class="row align-items-center">
            {{-- بيانات المؤسسة --}}
            <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">
                <div class="d-flex align-items-center">
                    @if($organization->logo)
                        <img src="{{ asset('storage/'.$organization->logo) }}" class="rounded-circle border ml-3" width="65" height="65" style="object-fit: cover; border-color: #d9e3ee !important;">
                    @else
                        <div class="rounded-circle p-3 ml-3 d-flex align-items-center justify-content-center" style="width: 65px; height: 65px; background: #f8fafc; border: 1px solid #d9e3ee;">
                            <i class="fas fa-building fa-lg" style="color: #64748b;"></i>
                        </div>
                    @endif
                    <div>
                        <h5 class="mb-1 font-weight-bold" style="color: #0B132B; font-size: 1rem;">
                            {{ $organization->name }}
                        </h5>
                        <div class="small" style="color: #64748b;">
                            {{ $organization->commercial_name }}
                        </div>
                        <div class="small mt-1 d-flex align-items-center gap-1" style="color: #64748b;">
                            <i class="fas fa-map-marker-alt" style="color: #00F5D4;"></i>
                            <span>{{ $organization->city ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- حالة المؤسسة --}}
            <div class="col-lg-2 col-md-4 col-6 mb-2">
                <div class="text-center p-2 rounded-lg" style="background: #f8fafc; border: 1px solid #d9e3ee;">
                    <i class="fas fa-building" style="color: #0B132B;"></i>
                    <div class="small mt-1" style="color: #64748b;">
                        المؤسسة
                    </div>
                    <div class="mt-1">
                        @switch($organization->status)
                            @case('active')
                                <span class="badge px-2 py-1" style="background: #e6fcf5; color: #0ca678; font-size: 0.75rem;">نشطة</span>
                                @break
                            @case('pending_approval')
                                <span class="badge px-2 py-1" style="background: #fff9db; color: #f59f00; font-size: 0.75rem;">انتظار</span>
                                @break
                            @case('suspended')
                                <span class="badge px-2 py-1" style="background: #fff5f5; color: #fa5252; font-size: 0.75rem;">موقوفة</span>
                                @break
                            @default
                                <span class="badge px-2 py-1" style="background: #f1f5f9; color: #64748b; font-size: 0.75rem;">غير محدد</span>
                        @endswitch
                    </div>
                </div>
            </div>

            {{-- الاشتراك --}}
            <div class="col-lg-3 col-md-4 col-6 mb-2">
                <div class="text-center p-2 rounded-lg" style="background: #f8fafc; border: 1px solid #d9e3ee;">
                    <i class="fas fa-crown" style="color: #f59e0b;"></i>
                    <div class="small mt-1" style="color: #64748b;">
                        الاشتراك
                    </div>
                    @if($subscription)
                        <div class="small font-weight-bold text-truncate" style="color: #0B132B; max-width: 150px; margin: 0 auto;">
                            {{ $subscription->package->name ?? '-' }}
                        </div>
                        <span class="badge px-2 py-1 mt-1" style="background: #e6fcf5; color: #0ca678; font-size: 0.75rem;">
                            {{ $subscription->status_label }}
                        </span>
                    @else
                        <div class="small font-weight-bold" style="color: #0B132B;">-</div>
                        <span class="badge px-2 py-1 mt-1" style="background: #f1f5f9; color: #64748b; font-size: 0.75rem;">
                            بدون اشتراك
                        </span>
                    @endif
                </div>
            </div>

            {{-- المستندات --}}
            <div class="col-lg-3 col-md-4 col-12 mb-2">
                <div class="text-center p-2 rounded-lg" style="background: #f8fafc; border: 1px solid #d9e3ee;">
                    <i class="fas fa-file-alt" style="color: #00F5D4;"></i>
                    <div class="small mt-1" style="color: #64748b;">
                        المستندات
                    </div>
                    <div class="mt-1">
                        @if($documentCount == count($documents))
                            <span class="badge px-2 py-1" style="background: #e6fcf5; color: #0ca678; font-size: 0.75rem;">مكتملة</span>
                        @elseif($documentCount > 0)
                            <span class="badge px-2 py-1" style="background: #fff9db; color: #f59f00; font-size: 0.75rem;">ناقصة</span>
                        @else
                            <span class="badge px-2 py-1" style="background: #fff5f5; color: #fa5252; font-size: 0.75rem;">غير موجودة</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>