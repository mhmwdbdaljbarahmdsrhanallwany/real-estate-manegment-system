<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-users ml-1"></i>
            المستخدمون المسند إليهم الدور
        </h3>

        <span class="badge badge-primary float-left">
            {{ $users->count() }} مستخدم
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>المستخدم</th>
                        <th>نطاق الوصول</th>
                        <th>نوع النطاق</th>
                        <th>حالة النطاق</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $userRole)
                        @php
                            $assignment = $userRole->userAssignment;
                            $user = $assignment->user;
                            $context = $assignment->accessContext;
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $user->name }}
                            </td>

                            <td>
                                {{ $context->name }}

                                @if ($context->subdomain)
                                    <small class="d-block text-muted">
                                        {{ $context->subdomain }}.{{ $context->domain }}
                                    </small>
                                @endif
                            </td>

                            <td>
                                {{ $context->context_type }}
                            </td>

                            <td>
                                <span class="badge badge-success">
                                    نشط
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted p-3">
                                لا يوجد مستخدمون نشطون مسند إليهم هذا الدور.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>