@extends('admin.layout.master')

@section('title')
    إدارة الصلاحيات
@endsection

@section('contect')
<div class="container-fluid" >

    <div class="card">
        <div class="card-header bg-light">
            <h4 class="mb-0 fw-bold text-primary">
                <i class="fas fa-folder-plus me-2"></i>
                إضافة وحدة صلاحيات جديدة
            </h4>
        </div>

        <div class="card-body">

            @php
                $data = [
                    'name' => old('name'),
                    'display_name' => old('display_name'),
                    'description' => old('description'),
                    'sort_order' => old('sort_order'),
                ];

                $types = [
                    'name' => [
                        'label' => 'الاسم البرمجي',
                        'name' => 'name',
                        'icon' => 'code',
                        'type' => 'text',
                        'placeholder' => 'مثال: invoices',
                        'maxlength' => 100,
                        'required' => true,
                    ],

                    'display_name' => [
                        'label' => 'اسم الوحدة المعروض',
                        'name' => 'display_name',
                        'icon' => 'folder',
                        'type' => 'text',
                        'placeholder' => 'مثال: الفواتير',
                        'maxlength' => 255,
                        'required' => true,
                    ],

                    'description' => [
                        'label' => 'وصف الوحدة',
                        'name' => 'description',
                        'icon' => 'align-left',
                        'type' => 'textarea',
                        'placeholder' => 'اكتب وصفًا مختصرًا للوحدة (اختياري)',
                        'rows' => 3,
                        'maxlength' => 1000,
                    ],

                    'sort_order' => [
                        'label' => 'ترتيب العرض',
                        'name' => 'sort_order',
                        'icon' => 'sort-numeric-down',
                        'type' => 'number',
                        'placeholder' => 'يُحدد تلقائيًا إذا تُرك فارغًا',
                        'min' => 1,
                        'step' => 1,
                    ],
                ];

                $selectes = [];
            @endphp

            <form
                method="POST"
                action="{{ route('admin.permission-modules.store') }}"
                class="row g-4"
            >
                @csrf

                <div class="col-12">
                    <x-constructor-form
                        :rows="$data"
                        :types="$types"
                        :selectes="$selectes"
                    />
                </div>

                <div class="col-12 d-flex gap-2">
                    <x-button :save="true" text="حفظ الوحدة" />

                    <a
                        href="{{ route('admin.permissions.index') }}"
                        class="btn btn-secondary"
                    >
                        إلغاء
                    </a>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
