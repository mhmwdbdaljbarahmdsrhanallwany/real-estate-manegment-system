<div class="row">


    {{-- ===========================
        معلومات الباقة
    =========================== --}}

    <div class="col-lg-7 mb-3">


        <div class="card shadow-sm border-0">


            <div class="card-header bg-white">

                <i class="fas fa-box text-primary"></i>

                <span class="font-weight-bold">
                    معلومات الباقة
                </span>

            </div>



            <div class="card-body">


                <div class="row">


                    @php

                    $data = [

                        'اسم الباقة' => $package->name,

                        'كود الباقة' => $package->code,

                        'المستوى' => $package->level,

                        'السعر الأساسي' => number_format($package->base_price,2),

                        'دورة الفوترة' => $package->billing_cycle_label,

                        'مدة الباقة' => $package->duration_days.' يوم',

                        'حالة الباقة' => $package->status_label,

                        'نوع الباقة' => $package->featured_label,

                    ];

                    @endphp



                    @foreach($data as $label=>$value)

                    <div class="col-md-6 mb-4">


                        <div class="small text-muted">

                            {{ $label }}

                        </div>


                        <div class="font-weight-bold">

                            {{ $value ?? '-' }}

                        </div>


                    </div>


                    @endforeach


                </div>



                <hr>



                <div>

                    <div class="small text-muted mb-2">

                        وصف الباقة

                    </div>


                    <p class="mb-0">

                        {{ $package->description ?? 'لا يوجد وصف' }}

                    </p>


                </div>


            </div>


        </div>


    </div>





    {{-- ===========================
        حدود الباقة
    =========================== --}}


    <div class="col-lg-5 mb-3">


        <div class="card shadow-sm border-0">


            <div class="card-header bg-white">

                <i class="fas fa-sliders-h text-success"></i>

                <span class="font-weight-bold">

                    حدود الاستخدام

                </span>

            </div>



            <div class="card-body">


                @php

                $limits=[

                    'المستخدمون'=>[
                        'icon'=>'fa-users',
                        'value'=>$package->max_users
                    ],

                    'الفروع'=>[
                        'icon'=>'fa-code-branch',
                        'value'=>$package->max_branches
                    ],

                    'العقارات'=>[
                        'icon'=>'fa-home',
                        'value'=>$package->max_properties
                    ],

                    'الوحدات'=>[
                        'icon'=>'fa-building',
                        'value'=>$package->max_units
                    ],

                    'التخزين'=>[
                        'icon'=>'fa-database',
                        'value'=>$package->storage_limit_mb.' MB'
                    ],

                ];

                @endphp




                @foreach($limits as $label=>$item)


                <div class="d-flex align-items-center mb-4">


                    <div class="mr-3 text-primary">

                        <i class="fas {{ $item['icon'] }} fa-lg"></i>

                    </div>


                    <div>


                        <div class="small text-muted">

                            {{ $label }}

                        </div>


                        <strong>

                            {{ $item['value'] ?? 0 }}

                        </strong>


                    </div>


                </div>


                @endforeach


            </div>


        </div>


    </div>



</div>