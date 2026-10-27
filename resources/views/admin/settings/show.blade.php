@extends('admin.layout.master')

@section('title', 'لوحة تحكم المؤسسة')

@section('contect')
{{-- @include('admin.incloude.agencynavepare') --}}
 


    @include('admin.settings.tabs.header') 
    
  {{-- النافبار الخاص بالمؤسسة--}}
  <x-navbar :links="$links" />

  


  <div class="mt-1">
  
  @switch($tab)
  

  @case('index')
  <div class="row">
    <div class="col-md-4">

        <div class="card text-center shadow-sm">

            <div class="card-body">

                <i class="fas fa-list fa-2x mb-3"></i>

                <h5>
                    الإجراءات
                </h5>


                <h3>
                    {{ $actionsCount ?? 0 }}
                </h3>


                <small>
                    أنواع الإجراءات المعرفة بالنظام
                </small>

            </div>

        </div>

    </div>



    <div class="col-md-4">

        <div class="card text-center shadow-sm">

            <div class="card-body">

                <i class="fas fa-bell fa-2x mb-3"></i>

                <h5>
                    الإشعارات
                </h5>


                <h3>
                    {{ $notificationsCount ?? 0 }}
                </h3>


                <small>
                    إعدادات التنبيهات
                </small>

            </div>

        </div>

    </div>



    <div class="col-md-4">

        <div class="card text-center shadow-sm">

            <div class="card-body">

                <i class="fas fa-calendar-check fa-2x mb-3"></i>

                <h5>
                    الاشتراكات
                </h5>


                <h3>
                    {{ $subscriptionsCount ?? 0 }}
                </h3>


                <small>
                    إعدادات دورة الاشتراك
                </small>

            </div>

        </div>

    </div>


</div>
  
  @break
  @case('actions_show')

@include('admin.settings.tabs.actions_show')

@break
  
  @case('general')
  
  @include('admin.settings.tabs.general')
  
  @break
  
  @case('organizations')
  
  @include('admin.settings.tabs.organizations')
  
  @break
  @case('subscriptions')
  
  @include('admin.settings.tabs.subscriptions')
  
  @break
  
  @case('actions')
  
  @include('admin.settings.tabs.actions')
  
  @break
  
  
  
  @case('notifications')
  
  @include('admin.settings.tabs.notifications')
  
  @break
  
  
  
  @endswitch
  
  </div>

    
@endsection
