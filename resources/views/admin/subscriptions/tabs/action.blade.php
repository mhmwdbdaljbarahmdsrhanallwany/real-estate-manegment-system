<div class="row">
    {{-- ======================================
        الجانب الأيمن : معلومات الاشتراك
    ======================================= --}}
    <div class="col-lg-8 mb-3 order-lg-1">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <i class="fas fa-chart-line text-primary"></i>
                <strong>
                    حالة ودورة الاشتراك
                </strong>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- الفترة التجريبية --}}
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-flask fa-lg text-info mr-3"></i>
                                <div>
                                    <small class="text-muted">
                                        الفترة التجريبية
                                    </small>
                                    <h6 class="mb-0 font-weight-bold">
                                        {{ $status['trialDays'] }}يوم </h6>
                                </div>
                            </div>
                            <hr>
                            <small class="text-muted"> تنتهي في:
                               {{ $subscription->trial_end_date?->format('Y-m-d') ?? '-' }}
                            </small>
                        </div>
                    </div>
                    {{-- الاشتراك الحالي --}}
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-check fa-lg text-success mr-3"></i>
                                <div>
                                    <small class="text-muted">
                                        الاشتراك الحالي
                                    </small>
                                    <h6 class="mb-0 font-weight-bold">
                                        {{ $status['remainingDays'] }}يوم </h6>
                                </div>
                            </div>
                            <hr>
                            <small class="text-muted">ينتهي في:
                              {{ $subscription->end_date?->format('Y-m-d') ?? '-' }}
                            </small>
                        </div>
                    </div>
                    {{-- الغرامة --}}
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-money-bill-wave fa-lg text-danger mr-3"></i>
                                <div>
                                    <small class="text-muted"> قبل الغرامة
                                    </small>
                                    <h6 class="mb-0 font-weight-bold">
                                        {{ $status['penaltyDays'] }}
                                        يوم
                                    </h6>
                                </div>
                            </div>
                            <hr>
                            <small class="text-muted">
                                تبدأ في:
                                {{ $status['penaltyDate']?->format('Y-m-d') ?? '-' }}
                            </small>
                        </div>
                    </div>
                    {{-- الإيقاف --}}
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-ban fa-lg text-dark mr-3"></i>
                                <div>
                                   <small class="text-muted">قبل الإيقاف
                                    </small>
                                    <h6 class="mb-0 font-weight-bold">
                                        {{ $status['suspendDays'] }}
                                        يوم
                                    </h6>
                                </div>
                            </div>
                            <hr>
                            <small class="text-muted">
                                الإيقاف في:
                                {{ $status['suspendDate']?->format('Y-m-d') ?? '-' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    {{-- ======================================
        الجانب الأيسر : الإجراءات
    ======================================= --}}
    <div class="col-lg-4 mb-3 order-lg-2">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <i class="fas fa-cogs text-primary"></i>
                <strong>إجراءات الاشتراك</strong>
            </div>

            <div class="card-body">

                {{-- تفعيل الاشتراك --}}
                <form method="POST"
                      action="{{ route('admin.subscriptions.activate', $subscription->subscription_id) }}">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-check-circle"></i>
                        تفعيل الاشتراك
                    </button>
                </form>

                {{-- تجديد الاشتراك --}}
                <form method="POST"
                      action="{{ route('admin.subscriptions.renew', $subscription->subscription_id) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-sync"></i>
                        تجديد الاشتراك
                    </button>
                </form>

                {{-- ترقية باقة الاشتراك --}}
                <form method="POST"
                      action="{{ route('admin.subscriptions.upgrade', $subscription->subscription_id) }}">
                    @csrf
                    <button type="submit" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-arrow-up"></i>
                        ترقية الباقة
                    </button>
                </form>

                {{-- تمديد فترة الاشتراك --}}
                <form method="POST"
                      action="{{ route('admin.subscriptions.extend', $subscription->subscription_id) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-block mb-2">
                        <i class="fas fa-calendar-plus"></i>
                        تمديد الفترة
                    </button>
                </form>

                {{-- تطبيق الغرامة المستحقة --}}
                <form method="POST"
                      action="{{ route('admin.subscriptions.apply-penalty', $subscription->subscription_id) }}">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-money-bill"></i>
                        تطبيق غرامة
                    </button>
                </form>

                {{-- إيقاف الاشتراك --}}
                <form method="POST"
                      action="{{ route('admin.subscriptions.stop', $subscription->subscription_id) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-block">
                        <i class="fas fa-ban"></i>
                        إيقاف الاشتراك
                    </button>
                </form>

            </div>
        </div>

        {{-- حالة الاشتراك الحالية --}}
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body text-center">
                <small class="text-muted">الحالة الحالية</small>
                <h5 class="mt-2">
                    <span class="badge badge-{{ $status['state']['color'] }}">
                        {{ $status['state']['label'] }}
                    </span>
                </h5>
            </div>
        </div>

    </div>

</div>