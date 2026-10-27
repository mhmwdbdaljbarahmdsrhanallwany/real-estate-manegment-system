<div class="card border-0 shadow-sm mb-4">

    <div class="card-body py-3">

        <div class="row align-items-center">


            {{-- هوية الاشتراك --}}
            <div class="col-lg-5 col-md-12 mb-3 mb-lg-0">

                <div class="media align-items-center">


                    <div class="rounded-circle bg-primary text-white text-center mr-3"
                         style="width:65px;height:65px;line-height:65px;">

                        <i class="fas fa-id-card fa-2x"></i>

                    </div>



                    <div class="media-body">


                        <h5 class="font-weight-bold mb-1">

                            اشتراك {{ $subscription->organization->name ?? '-' }}

                        </h5>


                        <div class="text-muted small">

                            <i class="fas fa-crown text-warning"></i>

                            {{ $subscription->package->name ?? '-' }}

                        </div>


                    </div>


                </div>

            </div>





            {{-- حالة الاشتراك --}}
            <div class="col-lg-2 col-md-4 col-6 mb-2">


                <div class="text-center border rounded p-2">


                    <i class="fas fa-toggle-on text-success"></i>


                    <div class="small text-muted mt-1">
                        الحالة
                    </div>



                    @switch($subscription->status)

                        @case('active')

                            <span class="badge badge-success">
                                نشط
                            </span>

                        @break


                        @case('expired')

                            <span class="badge badge-danger">
                                منتهي
                            </span>

                        @break


                        @case('pending')

                            <span class="badge badge-warning">
                                انتظار
                            </span>

                        @break


                        @case('cancelled')

                            <span class="badge badge-secondary">
                                ملغي
                            </span>

                        @break


                    @endswitch


                </div>


            </div>






            {{-- نوع الاشتراك --}}
            <div class="col-lg-2 col-md-4 col-6 mb-2">


                <div class="text-center border rounded p-2">


                    <i class="fas fa-layer-group text-info"></i>


                    <div class="small text-muted mt-1">
                        النوع
                    </div>


                    <span class="badge badge-info">

                        {{ $subscription->subscription_type_label }}

                    </span>


                </div>


            </div>






            {{-- مدة الاشتراك --}}
            <div class="col-lg-3 col-md-4 col-12 mb-2">


                <div class="text-center border rounded p-2">


                    <i class="fas fa-calendar-alt text-primary"></i>


                    <div class="small text-muted mt-1">
                        الفترة
                    </div>



                    <div class="small font-weight-bold">


                        {{ $subscription->start_date?->format('Y-m-d') ?? '-' }}

                        <span class="text-muted">
                            إلى
                        </span>

                        {{ $subscription->end_date?->format('Y-m-d') ?? '-' }}


                    </div>


                </div>


            </div>



        </div>


    </div>


</div>