
@extends('admin.layout.master')

@section('title', 'عرض المستخدم')

@section('contect')

<div class="container-fluid">

   
    {{-- ترويسة المستخدم ومعلومات الحساب --}}
    <x-page-header
        :title="$user->name"
        icon="fas fa-user-circle"
        :code="'USER-' . $user->id"
    >
    
        <div class="d-flex align-items-center flex-wrap">
    
            {{-- البريد الإلكتروني --}}
            <div class="border rounded px-2 py-1 mx-1 mb-1">
                <small class="text-muted d-block">
                    <i class="fas fa-envelope ml-1"></i>
                    البريد الإلكتروني
                </small>
                <span class="small font-weight-bold">
                    {{ $user->email }}
                </span>
            </div>
    
            {{-- سياقات الوصول --}}
            <div class="border rounded px-2 py-1 mx-1 mb-1">
                <small class="text-muted d-block">
                    <i class="fas fa-layer-group ml-1 text-info"></i>
                    سياقات الوصول
                </small>
                <strong>{{ $contextsCount }}</strong>
            </div>
    
            {{-- الأدوار النشطة --}}
            <div class="border rounded px-2 py-1 mx-1 mb-1">
                <small class="text-muted d-block">
                    <i class="fas fa-user-shield ml-1 text-success"></i>
                    الأدوار النشطة
                </small>
                <strong>{{ $rolesCount }}</strong>
            </div>
    
            {{-- تاريخ إنشاء الحساب --}}
            <div class="border rounded px-2 py-1 mx-1 mb-1">
                <small class="text-muted d-block">
                    <i class="fas fa-calendar-alt ml-1 text-primary"></i>
                    تاريخ الإنشاء
                </small>
                <strong class="small">
                    {{ $user->created_at
                        ? $user->created_at->format('Y-m-d')
                        : '—' }}
                </strong>
            </div>
    
            {{-- أزرار الإجراءات --}}
            <div class="d-flex align-items-center flex-wrap mx-1 mb-1">
    
                <a href="{{ route('admin.users.index') }}"
                   class="btn btn-outline-secondary btn-sm ml-1 mb-1">
                    <i class="fas fa-arrow-right ml-1"></i>
                    العودة
                </a>
    
                <a href="{{ route('admin.users.edit', $user->id) }}"
                   class="btn btn-primary btn-sm mb-1">
                    <i class="fas fa-edit ml-1"></i>
                    تعديل المستخدم
                </a>
    
            </div>
    
        </div>
    
    </x-page-header>
  
    

    {{-- النافبار الخاص بالمستخدم --}}
    <x-navbar :links="$links" />

    {{-- محتوى التبويب المحدد --}}
    <div class="mt-4">

        @switch($tab)

            @case('info')
                @include('admin.users_roles.users.tabs.info')
                @break

            @case('contexts')
                @include('admin.users_roles.users.tabs.contexts')
                @break

            @case('roles')
                @include('admin.users_roles.users.tabs.roles')
                @break

            @default
                @include('admin.users_roles.users.tabs.info')

        @endswitch

    </div>

</div>

@endsection

