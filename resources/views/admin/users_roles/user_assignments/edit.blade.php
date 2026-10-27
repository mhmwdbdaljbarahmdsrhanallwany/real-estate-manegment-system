
@extends('admin.layout.master')

@section('title')
    تعديل التعيين
@endsection

@section('contect')
    {{-- @include('admin.incloude.agencynavepare') --}}

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-light">
            <h4 class="text-primary mb-0">
                <i class="fas fa-user-edit mr-2"></i>
                تعديل التعيين
            </h4>
        </div>

        <div class="card-body">

            {{-- معلومات التعيين للقراءة فقط --}}
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="sForm-label">المستخدم</label>
                    <input
                        type="text"
                        class="sForm-control"
                        value="{{ $data->user?->name ?? 'مستخدم غير متاح' }}"
                        readonly
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="sForm-label">سياق الوصول</label>
                    <input
                        type="text"
                        class="sForm-control"
                        value="{{ $data->accessContext?->name ?? 'سياق غير متاح' }}"
                        readonly
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="sForm-label">تاريخ بداية التعيين</label>
                    <input
                        type="text"
                        class="sForm-control"
                        value="{{ $data->start_date
                        ? \Carbon\Carbon::parse($data->start_date)->format('Y-m-d')
                        : '—' }}"
                        readonly
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="sForm-label">حالة التعيين</label>
                    <input
                        type="text"
                        class="sForm-control"
                        value="{{ [
                            'ACTIVE' => 'نشط',
                            'INACTIVE' => 'غير نشط',
                            'EXPIRED' => 'منتهي',
                        ][$data->status] ?? $data->status }}"
                        readonly
                    >
                </div>
            </div>

            <hr>

            {{-- تعديل تاريخ النهاية فقط --}}
            <form
                method="POST"
                action="{{ route('admin.user_assignments.update', $data->id) }}"
                class="row"
            >
                @csrf
                @method('PUT')

                <x-constructor-form
                    :rows="$data"
                    :types="[
                        'end_date' => [
    'label' => 'تاريخ نهاية التعيين',
    'name' => 'end_date',
    'type' => 'date',
    'icon' => 'fas fa-calendar-alt',
    'min' => now()->addDay()->toDateString(),

],
                    ]"
                />

                <div class="col-12 text-muted small mb-3">
                    اترك التاريخ فارغًا إذا كان التعيين مفتوح المدة.
                </div>

                <div class="col-12 text-right">
                    <a
                        href="{{ route('admin.user_assignments.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="fas fa-arrow-right mr-1"></i>
                        رجوع
                    </a>

                    <x-button :save="true" text="حفظ التعديلات" />
                </div>
            </form>

        </div>
    </div>
@endsection
