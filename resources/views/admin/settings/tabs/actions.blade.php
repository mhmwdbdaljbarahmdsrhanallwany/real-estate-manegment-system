{{-- عنوان الصفحة --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="mb-0">
                    <i class="fas fa-tasks text-primary"></i>
                    إدارة الإجراءات
                </h4>
                <p class="text-muted mb-">
                    يمكنك من هنا إدارة أنواع الإجراءات التي ينفذها النظام، وتحديد طريقة تعامل النظام معها مثل تسجيلها في
                    سجل المراجعة أو إرسال الإشعارات.
                </p>
            </div>
        </div>
{{-- بطاقات الإحصائيات --}}
<div class="row ">
    <div class="col-lg-3 col-md-6 ">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="fas fa-list fa-2x text-primary mb-2"></i>
                <h3>{{ $actionTypes->count() }}</h3>
                <small class="text-muted">
                    إجمالي أنواع الإجراءات
                </small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 ">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="fas fa-lock fa-2x text-success mb-2"></i>
                <h3>{{ $actionTypes->where('is_system', 1)->count() }}</h3>
                <small class="text-muted">
                    إجراءات النظام
                </small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="fas fa-user-cog fa-2x text-warning mb-2"></i>
                <h3>{{ $actionTypes->where('is_system', 0)->count() }}</h3>
                <small class="text-muted">
                    إجراءات مخصصة
                </small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="fas fa-check-circle fa-2x text-info mb-2"></i>
                <h3>{{ $actionTypes->where('is_active', 1)->count() }}</h3>
                <small class="text-muted">
                    إجراءات مفعلة
                </small>
            </div>
        </div>
    </div>
</div>
{{-- جدول الإجراءات --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1">
                    <i class="fas fa-tasks text-primary"></i>
                    إدارة الإجراءات
                </h5>
                <small class="text-muted">
                    التحكم في سلوك إجراءات النظام.
                </small>
            </div>
            <a href="#" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                إضافة إجراء
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center mb-0">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th class="text-start">الإجراء</th>
                    <th>النظام</th>
                    <th>الحالة</th>
                    <th>إشعار</th>
                    <th>Audit</th>
                    <th>مرات التنفيذ</th>
                    <th>آخر تنفيذ</th>
                    <th width="140">الإجراءات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($actionTypes as $action)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td class="text-start">
                            <strong>
                                {{ $action->name }}
                            </strong>
                            <br>
                            <small class="text-muted">
                                {{ $action->code }}
                            </small>
                        </td>
                        <td>
                            @if($action->is_system)
                                <span class="badge bg-primary">
                                    <i class="fas fa-lock"></i>
                                    نظام
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-user"></i>
                                    مخصص
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($action->is_active)
                                <span class="badge bg-success">
                                    فعال
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    معطل
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($action->send_notification)
                                <span class="badge bg-success">
                                    <i class="fas fa-bell"></i>
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-bell-slash"></i>
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($action->write_audit)
                                <span class="badge bg-info">
                                    <i class="fas fa-clipboard-list"></i>
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-times"></i>
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">
                                {{ $action->actions_count ?? 0 }}
                            </span>
                        </td>
                        <td>
                            @if($action->last_action)
                                <small>
                                    {{ $action->last_action->created_at->diffForHumans() }}
                                </small>
                            @else
                                <span class="text-muted">
                                    —
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.settings.actions.show',$action->action_type_id) }}"
                                    class="btn btn-outline-primary btn-sm">
                                 
                                     <i class="fas fa-eye"></i>
                                 
                                 </a>
                                <a href="#"
                                   class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                    <td colspan="9">
                            <div class="py-5">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">
                                    لا توجد إجراءات مسجلة.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>