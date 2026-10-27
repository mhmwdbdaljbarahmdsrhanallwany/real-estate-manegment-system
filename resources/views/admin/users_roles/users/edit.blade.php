@extends('admin.layout.master')

@section('title')
    تعديل المستخدم
@endsection

@section('contect')

<div class="card shadow-lg border-0 rounded-3">
    <div class="card-header bg-light">
        <h4 class="text-primary mb-0">
            <i class="fas fa-user-edit me-2"></i>
            تعديل بيانات المستخدم
        </h4>
    </div>

    <div class="card-body">
        <form
            method="POST"
            action="{{ route('admin.users.update', $user->id) }}"
            class="row"
        >
            @csrf
            @method('PUT')

            <x-constructor-form
                :rows="$user"
                :types="[
                    'name' => [
                        'label' => 'اسم المستخدم',
                        'name' => 'name',
                        'icon' => 'fas fa-user',
                        'required' => true,
                        'placeholder' => 'أدخل اسم المستخدم',
                    ],
                    'email' => [
                        'label' => 'البريد الإلكتروني',
                        'name' => 'email',
                        'type' => 'email',
                        'icon' => 'fas fa-envelope',
                        'required' => true,
                        'placeholder' => 'أدخل البريد الإلكتروني',
                    ],
                    'password' => [
                        'label' => 'كلمة المرور الجديدة',
                        'name' => 'password',
                        'type' => 'password',
                        'icon' => 'fas fa-lock',
                        'placeholder' => 'اتركه فارغًا دون تغيير كلمة المرور',
                    ],
                    'password_confirmation' => [
                        'label' => 'تأكيد كلمة المرور',
                        'name' => 'password_confirmation',
                        'type' => 'password',
                        'icon' => 'fas fa-lock',
                        'placeholder' => 'أعد إدخال كلمة المرور الجديدة',
                    ],
                ]"
            />

            <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                <x-button :save="true" text="حفظ التغييرات" />

                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i>
                    العودة
                </a>
            </div>
        </form>
    </div>
</div>

@endsection