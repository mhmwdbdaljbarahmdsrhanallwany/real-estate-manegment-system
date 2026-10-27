@extends('admin.layout.master')

@section('title', '  سياق الوصول')

@section('contect')
    <div class="container-fluid">

        <x-page-header
            :title="$context->name"
            icon="fas fa-layer-group"
            :code="'CONTEXT-' . $context->id"
        />

        <x-navbar :links="$links" />

        <div class="mt-4">
            @switch($tab)

                @case('info')
                    @include('admin.access_contexts.tabs.info')
                    @break

                @case('assignments')
                    @include('admin.access_contexts.tabs.assignments')
                    @break

                @default
                    @include('admin.access_contexts.tabs.info')

            @endswitch
        </div>

    </div>
@endsection

