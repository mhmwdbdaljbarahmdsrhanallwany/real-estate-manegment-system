@extends('admin.layout.master')

@section('title', 'لوحة تحكم المؤسسة')

@section('contect')
@include('admin.incloude.agencynavepare')
 
    @include('admin.organizations.tabs.header')
  {{-- النافبار الخاص بالمؤسسة --}}
  <x-navbar :links="$links" />
  <div class="mt-4">
      @switch($tab)
          @case('info')
              @include( 'admin.organizations.tabs.info')
          @break
          @case('subscriptions')
              @include('admin.organizations.tabs.subscriptions')
          @break
          @case('packages')
              @include('admin.organizations.tabs.packages')
          @break
          @case('documents')
              @include('admin.organizations.tabs.documents')
          @break
      @endswitch
  </div>
@endsection
