
@extends('admin.layout.master')

@section('title', 'تفاصيل الدور')

@section('contect')
    <div class="container-fluid">

        <x-page-header
            :title="$role->display_name"
            icon="fas fa-user-shield"
            :code="'ROLE-' . $role->id"
        />

        <x-navbar :links="$links" />

        <div class="mt-4">
            @switch($tab)

                @case('info')
                    @include('admin.users_roles.roles.tabs.info')
                    @break

                @case('permissions')
                    @include('admin.users_roles.roles.tabs.permissions')
                    @break

                @case('users')
                    @include('admin.users_roles.roles.tabs.users')
                    @break

                @default
                    @include('admin.users_roles.roles.tabs.info')

            @endswitch
        </div>

    </div>
@endsection
