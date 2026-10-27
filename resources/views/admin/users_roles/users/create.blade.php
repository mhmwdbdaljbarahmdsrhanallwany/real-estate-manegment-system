
@extends('admin.layout.master')

@section('title')
    إضافة مستخدم جديد
@endsection

@section('contect')

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-plus ml-1"></i>
            إضافة مستخدم جديد
        </h3>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="card-body">

            {{-- رسائل التحقق --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- بيانات الحساب --}}
            <h6 class="text-primary font-weight-bold mb-3">
                <i class="fas fa-user ml-1"></i>
                بيانات الحساب
            </h6>

            <div class="row">
                <x-constructor-form
                    :rows="null"
                    :types="[
                        'name' => [
                            'label' => 'اسم المستخدم المعروض',
                            'name' => 'name',
                            'icon' => 'fas fa-user',
                            'required' => true,
                        ],
                        'email' => [
                            'label' => 'البريد الإلكتروني',
                            'name' => 'email',
                            'type' => 'email',
                            'icon' => 'fas fa-envelope',
                            'required' => true,
                        ],
                        'password' => [
                            'label' => 'كلمة المرور',
                            'name' => 'password',
                            'type' => 'password',
                            'icon' => 'fas fa-lock',
                            'required' => true,
                        ],
                        'password_confirmation' => [
                            'label' => 'تأكيد كلمة المرور',
                            'name' => 'password_confirmation',
                            'type' => 'password',
                            'icon' => 'fas fa-lock',
                            'required' => true,
                        ],
                    ]"
                />
            </div>

            <hr>

            {{-- سياق الوصول --}}
            <h6 class="text-primary font-weight-bold mb-3">
                <i class="fas fa-sitemap ml-1"></i>
                سياق الوصول
            </h6>

            <div class="form-group">
                <label for="access_context_id">سياق الوصول</label>

                <select
                    name="access_context_id"
                    id="access_context_id"
                    class="form-control @error('access_context_id') is-invalid @enderror"
                    required
                >
                    <option value="">اختر سياق الوصول</option>

                    @foreach ($accessContexts as $context)
                        <option
                            value="{{ $context->id }}"
                            {{ (string) old('access_context_id') === (string) $context->id ? 'selected' : '' }}
                        >
                            {{ $context->name }}
                            ({{ $context->context_type }})
                        </option>
                    @endforeach
                </select>

                @error('access_context_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <small class="form-text text-muted">
                    يحدد السياق الذي سيُنشأ للمستخدم ضمنه التعيين.
                </small>
            </div>

            <hr>

            {{-- الدور --}}
            <h6 class="text-primary font-weight-bold mb-3">
                <i class="fas fa-user-shield ml-1"></i>
                الدور والصلاحيات (اختياري)
            </h6>

            <div class="form-group">
                <label for="role_id">الدور</label>

                <select
                    name="role_id"
                    id="role_id"
                    class="form-control @error('role_id') is-invalid @enderror"
                >
                    <option value="">إنشاء المستخدم دون دور</option>

                    @foreach ($roles as $role)
                        <option
                            value="{{ $role->id }}"
                            {{ (string) old('role_id') === (string) $role->id ? 'selected' : '' }}
                        >
                            {{ $role->display_name ?? $role->name }}
                        </option>
                    @endforeach
                </select>

                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <small class="form-text text-muted">
                    يمكنك إنشاء المستخدم دون دور وإسناده لاحقًا.
                </small>
            </div>

            <hr>

            {{-- تاريخ نهاية التعيين --}}
            <h6 class="text-primary font-weight-bold mb-3">
                <i class="fas fa-calendar-alt ml-1"></i>
                مدة التعيين
            </h6>

            <div class="row">
                <x-constructor-form
                    :rows="null"
                    :types="[
                        'end_date' => [
                            'label' => 'تاريخ نهاية التعيين (اختياري)',
                            'name' => 'end_date',
                            'type' => 'date',
                            'icon' => 'fas fa-calendar-alt',
                            'min' => now()->addDay()->toDateString(),
                        ],
                    ]"
                />
            </div>

            <div class="text-muted small mb-3">
                اترك التاريخ فارغًا إذا كان التعيين مفتوح المدة.
            </div>

            {{-- الصلاحيات المباشرة --}}
            <div class="alert alert-light border mb-0">
                <i class="fas fa-info-circle text-info ml-1"></i>
                الصلاحيات الممنوحة عبر الأدوار ستُدار من شاشة الأدوار.
                لم تتم إضافة صلاحيات مباشرة للمستخدم لأن آلية تخزينها
                والتحقق منها تحتاج إلى توافق مع نظام الصلاحيات الحالي.
            </div>

        </div>

        <div class="card-footer d-flex justify-content-between">
            <a
                href="{{ route('admin.users.index') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-right ml-1"></i>
                إلغاء
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save ml-1"></i>
                حفظ المستخدم
            </button>
        </div>
    </form>
</div>

@endsection
