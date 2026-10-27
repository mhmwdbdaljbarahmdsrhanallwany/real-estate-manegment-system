@php
    $subscription = $organization->subscriptions->last();
    $package = $subscription?->package;

    // القيم الحالية (تستبدل لاحقاً بالـ relations الحقيقية)
    $currentUsers = $organization->users_count ?? 0;
    $currentBranches = $organization->branches_count ?? 0;
    $currentProperties = $organization->properties_count ?? 0;

@endphp


@if($package)

<div class="card shadow border-0 h-100">


    {{-- Header --}}
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

        <div>
            <i class="fas fa-crown text-warning"></i>
            الباقة الحالية
        </div>


        <span class="badge bg-success">
            {{ $subscription->status_label }}
        </span>


    </div>




    <div class="card-body">


        {{-- اسم الباقة --}}

        <div class="text-center mb-4">

            <h3 class="fw-bold mb-1">
                {{ $package->name }}
            </h3>


            <p class="text-muted">
                {{ $package->description }}
            </p>


        </div>





        {{-- التواريخ --}}

        <div class="row text-center border-bottom pb-3 mb-4">


            <div class="col-6">

                <small class="text-muted">
                    بداية الاشتراك
                </small>

                <h6>
                    {{ $subscription->start_date?->format('Y-m-d') }}
                </h6>

            </div>



            <div class="col-6">

                <small class="text-muted">
                    نهاية الاشتراك
                </small>

                <h6>
                    {{ $subscription->end_date?->format('Y-m-d') }}
                </h6>

            </div>


        </div>





        {{-- حدود الباقة --}}

        <h6 class="mb-3">
            استخدام الموارد
        </h6>



        {{-- المستخدمين --}}

        <div class="mb-3">

            <div class="d-flex justify-content-between">

                <span>
                    <i class="fas fa-users"></i>
                    المستخدمون
                </span>


                <span>
                    {{ $currentUsers }}
                    /
                    {{ $package->max_users }}
                </span>

            </div>


            <div class="progress mt-2">

                <div class="progress-bar"
                     style="
                     width:
                     {{ $package->max_users > 0 
                     ? ($currentUsers / $package->max_users) * 100 
                     : 0 }}%
                     ">

                </div>

            </div>

        </div>





        {{-- الفروع --}}

        <div class="mb-3">

            <div class="d-flex justify-content-between">

                <span>
                    <i class="fas fa-building"></i>
                    الفروع
                </span>


                <span>
                    {{ $currentBranches }}
                    /
                    {{ $package->max_branches }}
                </span>


            </div>


            <div class="progress mt-2">


                <div class="progress-bar"
                style="
                width:
                {{ $package->max_branches > 0
                ? ($currentBranches / $package->max_branches) * 100
                : 0 }}%
                ">


                </div>


            </div>


        </div>





        {{-- العقارات --}}

        <div class="mb-4">


            <div class="d-flex justify-content-between">

                <span>
                    <i class="fas fa-home"></i>
                    العقارات
                </span>


                <span>
                    {{ $currentProperties }}
                    /
                    {{ $package->max_properties }}
                </span>


            </div>



            <div class="progress mt-2">


                <div class="progress-bar"
                style="
                width:
                {{ $package->max_properties > 0
                ? ($currentProperties / $package->max_properties) * 100
                : 0 }}%
                ">


                </div>


            </div>


        </div>





        {{-- زر الإدارة --}}

        <a href="#"
           class="btn btn-primary w-100">

            <i class="fas fa-cog"></i>
            إدارة الاشتراك

        </a>


    </div>


</div>


@endif