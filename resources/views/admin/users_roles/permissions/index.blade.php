
@extends('admin.layout.master')

@section('title')
    إدارة الصلاحيات
@endsection

@section('contect')

<x-page-header
    title="إدارة الصلاحيات"
    icon="fas fa-key"
/>

<div class="row mt-3">
    <x-stat-card
        title="إجمالي الصلاحيات"
        :value="$totalPermissions"
        icon="fas fa-key"
        color="info"
    />

    <x-stat-card
        title="الصلاحيات النشطة"
        :value="$activePermissions"
        icon="fas fa-check-circle"
        color="success"
    />

    <x-stat-card
        title="الصلاحيات غير النشطة"
        :value="$inactivePermissions"
        icon="fas fa-ban"
        color="secondary"
    />
</div>

<x-navbar
    :links="[
        [
            'title' => 'جميع الأدوار',
            'url' => route('admin.roles.index'),
            'active' => request()->routeIs('admin.roles.*'),
        ],
        [
            'title' => 'جميع الصلاحيات',
            'url' => route('admin.permissions.index'),
            'active' => request()->routeIs('admin.permissions.*'),
        ],
        // [
        //     'title' => 'استثناءات صلاحيات المستخدمين',
        //     'url' => route('admin.user_permission_overrides.index'),
        //     'active' => request()->routeIs('admin.user_permission_overrides.*'),
        // ],
    ]"
/>
{{-- فلتر الوحدة وأزرار الإضافة --}}
<form method="GET"
      action="{{ route('admin.permissions.index') }}"
      class="card mb-3">

    <div class="card-body">
        <div class="row align-items-end">

            {{-- فلتر الوحدة --}}
            <div class="col-md-5 mb-2">
                <label for="moduleFilter">تصفية حسب الوحدة</label>

                <select
                    name="moduleFilter"
                    id="moduleFilter"
                    class="form-control"
                    onchange="this.form.submit()"
                >
                    @foreach ($modules as $value => $label)
                        <option
                            value="{{ $value }}"
                            @selected($selectedModule === $value)
                        >
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- زر إضافة صلاحية --}}
            <div class="col-md-auto mb-2">
                <a href="{{ route('admin.permissions.create') }}"
                   class="btn btn-success">
                    <i class="fas fa-plus-circle"></i>
                    إضافة صلاحية
                </a>
            </div>

            {{-- زر إضافة وحدة --}}
            <div class="col-md-auto mb-2">
                <a href="{{ route('admin.permission-modules.create') }}"
   class="btn btn-primary">
    <i class="fas fa-folder-plus"></i>
    إضافة وحدة
</a>
            </div>

        </div>
    </div>
</form>


{{-- الصلاحيات مقسمة حسب الوحدة --}}
<div id="permissionsModules">

    @forelse ($groupedPermissions as $moduleKey => $modulePermissions)

        @php
            $firstPermission = $modulePermissions->first();

            $moduleTitle = $moduleKey === '__unassigned'
                ? 'غير محددة الوحدة'
                : $firstPermission->module_display;

            $collapseId = 'permission-module-' . $loop->index;
        @endphp

        <div class="card mb-3">

            {{-- عنوان الوحدة --}}
            <div class="card-header">
                <button
                    type="button"
                    class="btn btn-link text-decoration-none p-0 d-flex
                           align-items-center justify-content-between w-100"
                    data-toggle="collapse"
                    data-target="#{{ $collapseId }}"
                    aria-expanded="false"
                    aria-controls="{{ $collapseId }}"
                >
                    <span>
                        <i class="fas fa-folder-open mr-2"></i>
                        <strong>{{ $moduleTitle }}</strong>

                        <span class="badge badge-info ml-2">
                            {{ $modulePermissions->count() }}
                        </span>
                    </span>

                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>

            {{-- محتوى الوحدة --}}
            <div id="{{ $collapseId }}" class="collapse">
                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">

                            <thead>
                                <tr>
                                    <th>المعرف</th>
                                    <th>اسم الصلاحية</th>
                                    <th>الاسم البرمجي</th>
                                    <th>الوصف</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($modulePermissions as $permission)
                                    <tr>
                                        <td>{{ $permission->id }}</td>

                                        <td>
                                            {{ $permission->display_name }}
                                        </td>

                                        <td>
                                            <code>{{ $permission->name }}</code>
                                        </td>

                                        <td>
                                            {{ $permission->description ?: '—' }}
                                        </td>

                                        <td>
                                            @if ($permission->status === 'ACTIVE')
                                                <span class="badge badge-success">
                                                    نشطة
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    غير نشطة
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

        </div>

    @empty
        <div class="alert alert-info">
            لا توجد صلاحيات ضمن الوحدة المحددة.
        </div>
    @endforelse

</div>

{{-- معلومات الترقيم --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mt-3">

    <div class="text-muted mb-2">
        @if ($permissions->total() > 0)
            عرض {{ $permissions->firstItem() }}
            إلى {{ $permissions->lastItem() }}
            من أصل {{ $permissions->total() }} صلاحية
        @else
            لا توجد صلاحيات للعرض.
        @endif
    </div>

    <div>
        {{ $permissions->links() }}
    </div>

</div>

@endsection
