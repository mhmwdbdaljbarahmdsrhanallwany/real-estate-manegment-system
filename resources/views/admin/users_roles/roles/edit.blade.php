@extends('admin.layout.master')

@section('title')
    تعديل بيانات الدور
@endsection

@section('contect')
<div class="card shadow-sm border-0">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-shield ml-1"></i>
            تعديل بيانات الدور
        </h3>
    </div>

    <form method="POST"
          action="{{ route('admin.roles.update', $role->id) }}">
        @csrf
        @method('PUT')

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

            <div class="form-group">
                <label>الاسم البرمجي</label>
                <input type="text"
                       class="form-control"
                       value="{{ $role->name }}"
                       readonly>
                <small class="form-text text-muted">
                    لا يمكن تغيير الاسم البرمجي بعد إنشاء الدور.
                </small>
            </div>

            <div class="form-group">
                <label>الحارس</label>
                <input type="text"
                       class="form-control"
                       value="{{ $role->guard_name }}"
                       readonly>
                <small class="form-text text-muted">
                    يحدد النظام الحارس ولا يمكن تغييره من هذه الصفحة.
                </small>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('admin.roles.show', [
                'id' => $role->id,
                'tab' => 'info',
            ]) }}"
               class="btn btn-secondary">
                <i class="fas fa-arrow-right ml-1"></i>
                إلغاء
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save ml-1"></i>
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>
@endsection