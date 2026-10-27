@extends('admin.layout.master')
@section('title')
    إنشاء دور جديد
@endsection
@section('contect')
<div class="card shadow-sm border-0">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-shield ml-1"></i>
            إنشاء دور جديد
        </h3>
    </div>
    <form method="POST" action="{{ route('admin.roles.store') }}">
        @csrf

        <div class="card-body">
            <div class="row">
                <x-constructor-form :types="[
                    'display_name' => [
                        'label' => 'اسم الدور',
                        'name' => 'display_name',
                        'type' => 'text',
                        'icon' => 'fas fa-id-badge',
                        'placeholder' => 'مثال: مدير العقارات',
                        'maxlength' => 255,
                        'required' => true,
                    ],
                    'name' => [
                        'label' => 'الاسم البرمجي',
                        'name' => 'name',
                        'type' => 'text',
                        'icon' => 'fas fa-code',
                        'placeholder' => 'مثال: property_manager',
                        'maxlength' => 255,
                        'required' => true,
                    ],
                    'description' => [
                        'label' => 'وصف الدور',
                        'name' => 'description',
                        'type' => 'textarea',
                        'icon' => 'fas fa-align-right',
                        'placeholder' => 'وصف مختصر لمسؤوليات الدور',
                        'maxlength' => 1000,
                        'rows' => 3,
                    ],
                ]" />
            </div>
            <div class="alert alert-light border mt-2 mb-0">
                <i class="fas fa-info-circle text-info ml-1"></i>
                الاسم البرمجي يجب أن يبدأ بحرف إنجليزي، ويحتوي على
                أحرف إنجليزية أو أرقام أو شرطة سفلية.
                يحدد النظام الحارس تلقائيًا، وتُدار صلاحيات الدور بعد إنشائه.
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('admin.roles.index') }}"
               class="btn btn-secondary">
                <i class="fas fa-arrow-right ml-1"></i>
                إلغاء
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save ml-1"></i>
                حفظ الدور
            </button>
        </div>
    </form>
</div>
@endsection
