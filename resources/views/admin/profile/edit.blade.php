@extends('admin.layout.master')
@section('title')
    تعديل الملف الشخصي
@endsection
@section('contect')
    @include('admin.incloude.agencynavepare')
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-light">
            <h4 class="text-primary mb-0">
                <i class="fas fa-user-edit me-2"></i>
                تعديل الملف الشخصي
            </h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="row g-4">
                @csrf
                @method('PUT')
                <x-constructor-form :rows="$data" 
                :types="[
                    'first_name' => [
                        'label' => 'الاسم الأول',
                        'name' => 'first_name',
                        'icon' => 'fas fa-user',
                    ],
                    'last_name' => [
                        'label' => 'اسم العائلة',
                        'name' => 'last_name',
                        'icon' => 'fas fa-user-tag',
                    ],               
                    'phone' => [
                        'label' => 'رقم الهاتف',
                        'name' => 'phone',
                        'icon' => 'fas fa-phone',
                    ],
                    'alternate_phone' => [
                        'label' => 'رقم هاتف احتياطي',
                        'name' => 'alternate_phone',
                        'icon' => 'fas fa-mobile-alt',
                    ],
                    'avatar' => [
                        'label' => 'الصورة الشخصية',
                        'name' => 'avatar',
                        'type' => 'file',
                        'icon' => 'fas fa-image',
                    ],
                    'birth_date' => [
                        'label' => 'تاريخ الميلاد',
                        'name' => 'birth_date',
                        'type' => 'date',
                        'icon' => 'fas fa-calendar',
                    ],
                    'notes' => [
                        'label' => 'ملاحظات',
                        'name' => 'notes',
                        'icon' => 'fas fa-sticky-note',
                    ],
                ]" :selectes="[
                    'gender' => [
                        'label' => 'الجنس',
                        'name' => 'gender',
                        'icon' => 'fas fa-venus-mars',
                        'option' => [
                            'Male' => 'ذكر',
                            'Female' => 'أنثى',
                        ],
                    ],
                ]" />
                <div class="col-12 text-end">
                    <x-button :save="true" text="تحديث البيانات" />
                </div>
            </form>
        </div>
    </div>
@endsection
