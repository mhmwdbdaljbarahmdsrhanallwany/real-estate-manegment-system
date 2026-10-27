@extends('admin.layout.master')

@section('title')
    إدارة الصلاحيات
@endsection

@section('contect')
<div class="container-fluid">

    <div class="card shadow-sm">

        {{-- عنوان الصفحة --}}
        <div class="card-header bg-light">
            <h4 class="mb-0 fw-bold text-primary">
                <i class="fas fa-key me-2"></i>
                إضافة صلاحية جديدة
            </h4>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                @php
                    $data = (object) [
                        'module_id' => old('module_id'),
                        'name' => old('name'),
                        'display_name' => old('display_name'),
                        'description' => old('description'),
                    ];

                    $types = [
                        'name' => [
                            'label' => 'الاسم البرمجي',
                            'name' => 'name',
                            'type' => 'text',
                            'icon' => 'fas fa-code',
                            'placeholder' => 'مثال: users.create',
                            'maxlength' => 255,
                            'required' => true,
                        ],

                        'display_name' => [
                            'label' => 'اسم الصلاحية المعروض',
                            'name' => 'display_name',
                            'type' => 'text',
                            'icon' => 'fas fa-tag',
                            'placeholder' => 'مثال: إضافة مستخدم',
                            'maxlength' => 255,
                            'required' => true,
                        ],

                        'description' => [
                            'label' => 'وصف الصلاحية',
                            'name' => 'description',
                            'type' => 'textarea',
                            'icon' => 'fas fa-align-right',
                            'placeholder' => 'اكتب وصفًا مختصرًا للصلاحية',
                            'rows' => 3,
                        ],
                    ];

                    $selectes = [
                        'module' => [
                            'label' => 'وحدة الصلاحية',
                            'name' => 'module_id',
                            'type' => 'relation',
                            'id' => 'module_id',
                            'option' => $modules,
                            'value' => 'id',
                            'display' => 'display_name',
                        ],
                    ];
                @endphp

                <x-constructor-form
                    :rows="$data"
                    :types="$types"
                    :selectes="$selectes"
                />

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.permissions.index') }}"
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-1"></i>
                        رجوع
                    </a>

                    <button type="submit" class="btn btn-primary" >
                        <i class="fas fa-save me-1"></i>
                        حفظ الصلاحية
                    </button>
                </div>

               
            </form>

        </div>
    </div>
</div>
@endsection
