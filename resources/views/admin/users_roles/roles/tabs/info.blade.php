
<div class="row">
    <div class="col-12">

        <x-info-card
            title="معلومات الدور"
            icon="fas fa-user-shield"
        >
            <div class="row">

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">
                        الاسم الظاهر
                    </small>

                    <strong>
                        {{ $role->display_name }}
                    </strong>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">
                        الاسم البرمجي
                    </small>

                    <code>{{ $role->name }}</code>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">
                        الحارس (Guard)
                    </small>

                    <code>{{ $role->guard_name }}</code>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">
                        الحالة
                    </small>

                    @if ($role->status === 'ACTIVE')
                        <span class="badge badge-success">
                            نشط
                        </span>
                    @elseif ($role->status === 'INACTIVE')
                        <span class="badge badge-secondary">
                            غير نشط
                        </span>
                    @else
                        <span class="badge badge-light">
                            {{ $role->status ?? '—' }}
                        </span>
                    @endif
                </div>

                <div class="col-lg-8 col-md-12 mb-4">
                    <small class="text-muted d-block mb-1">
                        الوصف
                    </small>

                    <div>
                        {{ $role->description ?: 'لا يوجد وصف' }}
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">
                        تاريخ الإنشاء
                    </small>

                    <strong>
                        {{ $role->created_at?->format('Y-m-d H:i') ?? '—' }}
                    </strong>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <small class="text-muted d-block mb-1">
                        آخر تحديث
                    </small>

                    <strong>
                        {{ $role->updated_at?->format('Y-m-d H:i') ?? '—' }}
                    </strong>
                </div>

            </div>
        </x-info-card>

    </div>
</div>
