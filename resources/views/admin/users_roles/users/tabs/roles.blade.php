
{{-- الأدوار المسندة وصلاحياتها --}}
<div class="row mt-4">

    <div class="col-12">

        <x-info-card
            title="الأدوار المسندة وصلاحياتها"
            icon="fas fa-user-shield"
        >

            @php
                $hasRoles = false;
            @endphp

            @foreach($assignments as $assignment)

                @php
                    $activeUserRoles = $assignment->userRoles
                        ->filter(fn ($userRole) =>
                            $userRole->status === 'ACTIVE'
                            && $userRole->role
                            && $userRole->role->status === 'ACTIVE'
                        );
                @endphp

                @foreach($activeUserRoles as $userRole)

                    @php
                        $hasRoles = true;
                        $role = $userRole->role;

                        $permissions = $role->permissions
                            ->where('guard_name', 'web')
                            ->where('status', 'ACTIVE');
                    @endphp

                    <div class="border rounded p-3 mb-4">

                        {{-- بيانات الدور --}}
                        <div class="d-flex justify-content-between
                                    align-items-start flex-wrap">

                            <div>
                                <h5 class="font-weight-bold mb-1">
                                    <i class="fas fa-user-shield text-info ml-1"></i>
                                    {{ $role->display_name ?: $role->name }}
                                </h5>

                                <small class="text-muted">
                                    الاسم البرمجي:
                                    <code>{{ $role->name }}</code>
                                </small>
                            </div>

                            <span class="badge badge-success">
                                دور نشط
                            </span>

                        </div>

                        @if($role->description)
                            <p class="text-muted mt-2 mb-3">
                                {{ $role->description }}
                            </p>
                        @endif

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                سياق الإسناد
                            </small>

                            <strong>
                                {{ $assignment->accessContext?->name
                                    ?? 'سياق غير محدد' }}
                            </strong>
                        </div>

                        <hr>

                        {{-- صلاحيات الدور --}}
                        <h6 class="font-weight-bold mb-3">
                            <i class="fas fa-key text-warning ml-1"></i>
                            الصلاحيات المتاحة للدور
                            <span class="badge badge-secondary">
                                {{ $permissions->count() }}
                            </span>
                        </h6>

                        @if($permissions->isNotEmpty())

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered
                                              table-hover mb-0">

                                    <thead class="thead-light">
                                        <tr>
                                            <th>الصلاحية</th>
                                            <th>الاسم البرمجي</th>
                                            <th>الوصف</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($permissions as $permission)

                                            <tr>
                                                <td>
                                                    {{ $permission->display_name
                                                        ?: $permission->name }}
                                                </td>

                                                <td>
                                                    <code>
                                                        {{ $permission->name }}
                                                    </code>
                                                </td>

                                                <td>
                                                    {{ $permission->description
                                                        ?: '—' }}
                                                </td>
                                            </tr>

                                        @endforeach
                                    </tbody>

                                </table>
                            </div>

                        @else

                            <div class="text-muted small py-2">
                                لا توجد صلاحيات نشطة مرتبطة بهذا الدور.
                            </div>

                        @endif

                    </div>

                @endforeach

            @endforeach

            @unless($hasRoles)

                <div class="text-center text-muted py-4">
                    <i class="fas fa-user-shield fa-2x mb-3"></i>

                    <p class="mb-0">
                        لا توجد أدوار نشطة مسندة لهذا المستخدم.
                    </p>
                </div>

            @endunless

        </x-info-card>

    </div>

</div>

