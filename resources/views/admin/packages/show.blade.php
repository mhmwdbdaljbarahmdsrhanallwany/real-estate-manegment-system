@extends('admin.layout.master')

@section('title', 'لوحة تحكم المؤسسة')

@section('contect')
@include('admin.incloude.agencynavepare')
 


    @include('admin.packages.tabs.header')
  {{-- النافبار الخاص بالمؤسسة --}}
  <x-navbar :links="$links" />


  <div class="mt-4">


      @switch($tab)


          @case('info')

              @include(
                  'admin.packages.tabs.info'
              )

          @break



          @case('organizations')

              @include(
                  'admin.packages.tabs.organizations'
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
