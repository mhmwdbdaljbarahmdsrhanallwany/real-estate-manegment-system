
@extends('admin.layout.master')

@section('title')
اضافة بيانات مؤسسة
@endsection

@section('contect')
    <div class="container-fluid">
         {{-- عنوان الصفحة --}}
         <x-page-header
         title="سياقات الوصول"
         icon="fas fa-layer-group"
     />

     <div class="row mt-3">

        <x-stat-card
            title="إجمالي سياقات الوصول"
            :value="$totalContexts"
            icon="fas fa-layer-group"
            color="info"
        />
    
        <x-stat-card
            title="السياقات النشطة"
            :value="$activeContexts"
            icon="fas fa-check-circle"
            color="success"
        />
    
        <x-stat-card
            title="السياقات غير النشطة"
            :value="$inactiveContexts"
            icon="fas fa-pause-circle"
            color="secondary"
        />
    
    </div>
    
        </div>
        <x-navbar
        :links="[
            [
                'title' => 'المستخدمون',
                'url' => route('admin.users.index'),
                'active' => request()->routeIs('admin.users.*'),
            ],
            [
                'title' => 'سياقات الوصول',
                'url' => route('admin.access_contexts.index'),
                'active' => request()->routeIs('admin.access_contexts.*'),
            ],
            [
                'title' => 'تعيينات المستخدمين',
                'url' => route('admin.user_assignments.index'),
                'active' => request()->routeIs('admin.user_assignments.*'),
            ],
        ]"
    />
       


        {{-- إعدادات جدول سياقات الوصول --}}
        @php
            $columns = [
                'اسم السياق',
                'نوع السياق',
                'النطاق',
                'النطاق الفرعي',
                'المؤسسة',
                'عدد التعيينات',
                'الحالة',
            ];

            $fields = [
                'name',
                'context_type',
                'domain',
                'subdomain',
                'organization.name',
                'assignments_count',
                'status',
            ];

            $actions = [
                'show' => fn ($context) =>
                    route('admin.access_contexts.show', $context->id),
            ];
        @endphp

        {{-- جدول سياقات الوصول --}}
        <div class="mt-3">
            <x-constructor-table
                title="جميع سياقات الوصول"
                icon="fas fa-table"
                id="access-contexts"
                tableid="access-contexts-table"
                :columns="$columns"
                :fields="$fields"
                :rows="$contexts"
                :actions="$actions"
                primary-key="id"
                :first-item="$contexts->firstItem()"
                :last-item="$contexts->lastItem()"
                :total="$contexts->total()"
                :pagination="$contexts"
            />
        </div>
        

    </div>
@endsection

