@extends('admin.layout.master')

@section('title', 'لوحة تحكم المؤسسة')

@section('contect')
@include('admin.incloude.agencynavepare')
 


    @include('admin.subscriptions.tabs.header')
  {{-- النافبار الخاص بالمؤسسة --}}
  <x-navbar :links="$links" />


  <div class="mt-4">


      @switch($tab)


          @case('info')

              @include(
                  'admin.subscriptions.tabs.info'
              )

          @break



          @case('action')

              @include(
                  'admin.subscriptions.tabs.action'
              )

          @break



          {{-- @case('packages')

              @include(
                  'admin.organizations.tabs.packages'
              )

          @break



          @case('documents')

              @include(
                  'admin.organizations.tabs.documents'
              )

          @break --}}



      @endswitch


  </div>



    
@endsection
