
<form
    action="{{ route('admin.roles.updatePermissions', $role->id) }}"
    method="POST"
>
    @csrf
    @method('PUT')

    {{-- بطاقة صلاحيات الدور --}}
    <div class="card card-outline card-primary">

        {{-- رأس البطاقة --}}
        <div class="card-header">
            <h3 class="card-title float-right">
                <i class="fas fa-key ml-1"></i>
                صلاحيات الدور
            </h3>

            <div class="card-tools">
                <span class="badge badge-primary">
                    {{ count($assignedPermissionIds) }} صلاحية مسندة
                </span>
            </div>
        </div>

        {{-- محتوى البطاقة --}}
        <div class="card-body">

            {{-- رسائل التحقق --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 pr-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- رسالة توضيحية --}}
            <div class="alert alert-light border">
                <i class="fas fa-info-circle text-primary ml-1"></i>
                حدد الصلاحيات التي تريد إسنادها إلى الدور، ثم اضغط
                <strong>حفظ الصلاحيات</strong>.
                تظهر هنا الصلاحيات النشطة وغير المحذوفة منطقيًا ضمن وحدات الدور.
            </div>

            @if ($modules->isEmpty())

                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle ml-1"></i>
                    لا توجد وحدات مرتبطة بهذا الدور؛ إذ لم يتم العثور على
                    صلاحيات مرتبطة به يمكن استنتاج وحداته منها.
                </div>

            @elseif ($permissions->isEmpty())

                <div class="alert alert-info mb-0">
                    لا توجد صلاحيات نشطة متاحة ضمن وحدات هذا الدور.
                </div>

            @else

                {{-- أدوات تحديد الصلاحيات --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

                    <div class="mb-2">
                        <span class="text-muted">
                            عدد الوحدات:
                        </span>
                        <strong>{{ $permissions->count() }}</strong>

                        <span class="text-muted mr-3">
                            إجمالي الصلاحيات المعروضة:
                        </span>
                        <strong>{{ $permissions->flatten(1)->count() }}</strong>
                    </div>

                    <div class="mb-2">
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary"
                            onclick="toggleRolePermissions(true)"
                        >
                            <i class="fas fa-check-square ml-1"></i>
                            تحديد الكل
                        </button>
                    
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-secondary"
                            onclick="toggleRolePermissions(false)"
                        >
                            <i class="far fa-square ml-1"></i>
                            إلغاء التحديد
                        </button>
                    </div>

                </div>

                {{-- الصلاحيات حسب الوحدة --}}
                @foreach ($permissions as $module => $modulePermissions)

                    <div class="card card-outline card-secondary mb-3">

                        {{-- رأس الوحدة --}}
                        <div class="card-header">

                            <h3 class="card-title">
                                <i class="fas fa-layer-group ml-1 text-muted"></i>
                                {{ $module }}
                            </h3>

                            <div class="card-tools">
                                <span class="badge badge-light">
                                    {{ $modulePermissions->count() }} صلاحية
                                </span>

                                <span class="badge badge-success">
                                    {{ $modulePermissions->filter(
                                        fn ($permission) => in_array(
                                            (int) $permission->id,
                                            $assignedPermissionIds,
                                            true
                                        )
                                    )->count() }} مسندة
                                </span>
                            </div>

                        </div>

                        {{-- جدول الصلاحيات --}}
                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-bordered table-hover mb-0">

                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">
                                                #
                                            </th>

                                            <th class="text-center" style="width: 70px;">
                                                <i class="fas fa-check"></i>
                                            </th>

                                            <th>
                                                اسم الصلاحية
                                            </th>

                                            <th>
                                                الاسم البرمجي
                                            </th>

                                            <th>
                                                الوصف
                                            </th>

                                            <th class="text-center" style="width: 130px;">
                                                الحالة
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($modulePermissions as $permission)

                                            @php
                                                $isAssigned = in_array(
                                                    (int) $permission->id,
                                                    $assignedPermissionIds,
                                                    true
                                                );
                                            @endphp

                                            <tr>

                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td class="text-center">
                                                    <input
                                                    type="checkbox"
                                                    class="permission-checkbox"
                                                    name="permission_ids[]"
                                                    value="{{ $permission->id }}"
                                                    id="permission_{{ $permission->id }}"
                                                    data-initially-assigned="{{ $isAssigned ? '1' : '0' }}"
                                                    {{ $isAssigned ? 'checked' : '' }}
                                                    aria-label="تحديد صلاحية {{ $permission->display_name }}"
                                                >
                                                </td>

                                                <td>
                                                    <label
                                                        for="permission_{{ $permission->id }}"
                                                        class="mb-0"
                                                        style="cursor: pointer;"
                                                    >
                                                        {{ $permission->display_name }}
                                                    </label>
                                                </td>

                                                <td>
                                                    <code>{{ $permission->name }}</code>
                                                </td>

                                                <td>
                                                    {{ $permission->description ?: '—' }}
                                                </td>

                                                <td class="text-center">
                                                    @if ($isAssigned)
                                                        <span class="badge badge-success">
                                                            مسندة للدور
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">
                                                            غير مسندة
                                                        </span>
                                                    @endif
                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                @endforeach

                {{-- أزرار الحفظ --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap border-top pt-3">

                    <small class="text-muted mb-2">
                        <i class="fas fa-info-circle ml-1"></i>
                        لن يؤثر الحفظ على ارتباطات الصلاحيات خارج الوحدات المعروضة.
                    </small>

                    <button
                        type="submit"
                        class="btn btn-primary mb-2"
                    >
                        <i class="fas fa-save ml-1"></i>
                        حفظ الصلاحيات
                    </button>

                </div>

            @endif

        </div>

    </div>

</form>

{{-- التحكم في تحديد الصلاحيات --}}
<script>
    window.toggleRolePermissions = function (selectAll) {
        const form = document.querySelector(
            'button[onclick="toggleRolePermissions(true)"]'
        )?.closest('form');

        if (!form) {
            console.error('لم يتم العثور على نموذج صلاحيات الدور.');
            return;
        }

        form.querySelectorAll('.permission-checkbox').forEach(function (checkbox) {
            if (selectAll) {
                // تحديد جميع الصلاحيات المعروضة
                checkbox.checked = true;
            } else {
                // إعادة كل صلاحية إلى حالتها الأصلية عند تحميل الصفحة
                checkbox.checked =
                    checkbox.dataset.initiallyAssigned === '1';
            }
        });
    };
</script>
