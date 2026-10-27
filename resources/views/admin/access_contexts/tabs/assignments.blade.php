
<div class="row">
    <div class="col-12">

        <x-info-card
            title="التعيينات والمستخدمون"
            icon="fas fa-users"
        >
            @if ($context->assignments->isEmpty())

                <div class="text-center text-muted py-4">
                    <i class="fas fa-users fa-2x mb-3"></i>
                    <p class="mb-0">
                        لا توجد تعيينات مرتبطة بسياق الوصول هذا.
                    </p>
                </div>

            @else

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>المستخدم</th>
                                <th>البريد الإلكتروني</th>
                                <th>حالة التعيين</th>
                                <th>بداية التعيين</th>
                                <th>نهاية التعيين</th>
                                <th>الأدوار المسندة</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($context->assignments as $assignment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $assignment->user?->name ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $assignment->user?->email ?? '—' }}
                                    </td>

                                    <td>
                                        @switch($assignment->status)
                                            @case('ACTIVE')
                                                <span class="badge badge-success">
                                                    نشط
                                                </span>
                                                @break

                                            @case('INACTIVE')
                                                <span class="badge badge-secondary">
                                                    غير نشط
                                                </span>
                                                @break

                                            @case('EXPIRED')
                                                <span class="badge badge-warning">
                                                    منتهي
                                                </span>
                                                @break

                                            @default
                                                <span class="badge badge-light">
                                                    {{ $assignment->status ?? '—' }}
                                                </span>
                                        @endswitch
                                    </td>

                                    <td>
                                        {{ $assignment->start_date ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $assignment->end_date ?? '—' }}
                                    </td>

                                    <td>
                                        @php
                                            $userRoles = $assignment->userRoles
                                                ->filter(fn ($userRole) =>
                                                    $userRole->role !== null
                                                );
                                        @endphp

                                        @forelse ($userRoles as $userRole)
                                            <div class="mb-1">
                                                <span class="badge badge-info">
                                                    {{ $userRole->role->display_name
                                                        ?? $userRole->role->name }}
                                                </span>

                                                @switch($userRole->status)
                                                    @case('ACTIVE')
                                                        <span class="badge badge-success">
                                                            نشط
                                                        </span>
                                                        @break

                                                    @case('INACTIVE')
                                                        <span class="badge badge-secondary">
                                                            غير نشط
                                                        </span>
                                                        @break

                                                    @case('SUSPENDED')
                                                        <span class="badge badge-warning">
                                                            موقوف
                                                        </span>
                                                        @break
                                                @endswitch
                                            </div>
                                        @empty
                                            <span class="text-muted">لا توجد أدوار</span>
                                        @endforelse
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif

        </x-info-card>

    </div>
</div>

