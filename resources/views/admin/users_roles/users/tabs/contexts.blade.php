{{-- سياقات الوصول والأدوار --}}
<div class="row mt-4">

    <div class="col-12">

        <x-info-card title="سياقات الوصول" icon="fas fa-layer-group">

            @forelse($assignments as $assignment)

                <div class="border rounded p-3 mb-3">

                    {{-- بيانات التعيين وحالته --}}
                    <div
                        class="d-flex justify-content-between
                                align-items-start flex-wrap">

                        <div>
                            <h6 class="font-weight-bold mb-1">
                                {{ $assignment->accessContext?->name ?? 'سياق غير متاح' }}
                            </h6>

                            <small class="text-muted">
                                النوع:
                                {{ $assignment->accessContext?->context_type ?? '—' }}
                            </small>
                        </div>

                        @php
                            $assignmentStatus = [
                                'ACTIVE' => [
                                    'label' => 'نشط',
                                    'class' => 'success',
                                ],
                                'INACTIVE' => [
                                    'label' => 'غير نشط',
                                    'class' => 'secondary',
                                ],
                                'EXPIRED' => [
                                    'label' => 'منتهي',
                                    'class' => 'danger',
                                ],
                            ][$assignment->status] ?? [
                                'label' => $assignment->status,
                                'class' => 'secondary',
                            ];
                        @endphp

                        <span class="badge badge-{{ $assignmentStatus['class'] }}">
                            {{ $assignmentStatus['label'] }}
                        </span>

                    </div>

                    <hr>
                    
                    {{-- تواريخ التعيين --}}
                    <div class="row mb-3">

                        <div class="col-md-6 mb-2 mb-md-0">
                            <small class="text-muted d-block">
                                <i class="fas fa-calendar-check ml-1"></i>
                                بداية التعيين
                            </small>

                            <span class="font-weight-bold">
                                {{ $assignment->start_date
                                    ? \Carbon\Carbon::parse($assignment->start_date)->format('Y-m-d')
                                    : '—' }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">
                                <i class="fas fa-calendar-times ml-1"></i>
                                نهاية التعيين
                            </small>

                            <span class="font-weight-bold">
                                {{ $assignment->end_date
                                    ? \Carbon\Carbon::parse($assignment->end_date)->format('Y-m-d')
                                    : 'غير محددة' }}
                            </span>
                        </div>

                    </div>

                    <hr>


                    {{-- الأدوار المرتبطة بالتعيين --}}
                    <h6 class="font-weight-bold mb-3">
                        <i class="fas fa-user-shield ml-1"></i>
                        الأدوار المسندة في هذا السياق
                    </h6>

                    @php
                        $activeUserRoles = $assignment->userRoles->filter(
                            fn($userRole) => $assignment->status === 'ACTIVE' &&
                                $userRole->status === 'ACTIVE' &&
                                $userRole->role &&
                                $userRole->role->status === 'ACTIVE',
                        );
                    @endphp

                    @forelse($activeUserRoles as $userRole)
                        <span class="badge badge-info p-2 ml-1 mb-1">
                            {{ $userRole->role->display_name ?: $userRole->role->name }}
                        </span>

                    @empty

                        <p class="text-muted small mb-0">
                            لا توجد أدوار نشطة لهذا التعيين.
                        </p>
                    @endforelse

                </div>

            @empty

                <div class="text-center text-muted py-4">
                    <i class="fas fa-layer-group fa-2x mb-2"></i>

                    <p class="mb-0">
                        لا توجد تعيينات وصول لهذا المستخدم.
                    </p>
                </div>

            @endforelse

        </x-info-card>

    </div>

</div>
