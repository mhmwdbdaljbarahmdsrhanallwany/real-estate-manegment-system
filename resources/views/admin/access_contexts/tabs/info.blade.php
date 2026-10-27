
<div class="row">
    <div class="col-12">

        <x-info-card title="معلومات سياق الوصول" icon="fas fa-layer-group">

            <div class="row">

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">اسم السياق</small>
                    <strong>{{ $context->name }}</strong>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">نوع السياق</small>
                    <strong>{{ $context->context_type }}</strong>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">الحالة</small>

                    @if ($context->status === 'ACTIVE')
                        <span class="badge badge-success">نشط</span>
                    @elseif ($context->status === 'INACTIVE')
                        <span class="badge badge-secondary">غير نشط</span>
                    @else
                        <span class="badge badge-light">
                            {{ $context->status ?? '—' }}
                        </span>
                    @endif
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">النطاق</small>
                    <strong>{{ $context->domain ?: '—' }}</strong>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">النطاق الفرعي</small>
                    <strong>{{ $context->subdomain ?: '—' }}</strong>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">المؤسسة</small>
                    <strong>{{ $context->organization?->name ?? '—' }}</strong>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">عدد التعيينات</small>
                    <strong>{{ $context->assignments_count }}</strong>
                </div>

            </div>

            <hr>

            <h6 class="font-weight-bold mb-3">
                <i class="far fa-clock text-muted ml-1"></i>
                معلومات التسجيل والتحديث
            </h6>

            <div class="row">

                <div class="col-lg-4 col-md-6 mb-3">
                    <small class="text-muted d-block mb-1">أنشئ بواسطة</small>
                    <strong>{{ $context->createdBy?->name ?? '—' }}</strong>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <small class="text-muted d-block mb-1">تاريخ الإنشاء</small>
                    <strong>
                        {{ $context->created_at?->format('Y-m-d H:i') ?? '—' }}
                    </strong>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <small class="text-muted d-block mb-1">آخر تحديث بواسطة</small>
                    <strong>{{ $context->updatedBy?->name ?? '—' }}</strong>
                </div>

                <div class="col-lg-4 col-md-6 mb-3">
                    <small class="text-muted d-block mb-1">تاريخ آخر تحديث</small>
                    <strong>
                        {{ $context->updated_at?->format('Y-m-d H:i') ?? '—' }}
                    </strong>
                </div>

            </div>

        </x-info-card>

    </div>
</div>

