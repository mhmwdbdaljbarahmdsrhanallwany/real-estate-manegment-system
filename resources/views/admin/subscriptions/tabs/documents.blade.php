<div class="card shadow-sm border-0">


    <div class="card-header bg-white">

        <i class="fas fa-folder-open text-primary"></i>

        <span class="font-weight-bold">
            مستندات المؤسسة
        </span>

    </div>



    <div class="card-body">


        <div class="row">



            {{-- الترخيص البلدي --}}
            <div class="col-md-4 mb-3">


                <div class="document-card border rounded p-3 text-center h-100">


                    <div class="mb-2">

                        <i class="fas fa-file-alt fa-2x text-primary"></i>

                    </div>



                    <h6 class="font-weight-bold">

                        الترخيص البلدي

                    </h6>



                    @if($organization->municipality_license)


                        <span class="badge badge-success">

                            <i class="fas fa-check"></i>
                            مرفوع

                        </span>


                        <div class="mt-3">


                            <a href="{{ asset('storage/'.$organization->municipality_license) }}"
                               target="_blank"
                               class="btn btn-outline-primary btn-sm">


                                <i class="fas fa-eye"></i>

                                عرض


                            </a>


                        </div>



                    @else


                        <span class="badge badge-secondary">

                            غير موجود

                        </span>



                    @endif



                </div>


            </div>






            {{-- ترخيص الوساطة --}}
            <div class="col-md-4 mb-3">


                <div class="document-card border rounded p-3 text-center h-100">


                    <div class="mb-2">

                        <i class="fas fa-building fa-2x text-warning"></i>

                    </div>



                    <h6 class="font-weight-bold">

                        ترخيص الوساطة العقارية

                    </h6>



                    @if($organization->real_estate_broker_license)


                        <span class="badge badge-success">

                            <i class="fas fa-check"></i>

                            مرفوع

                        </span>



                        <div class="mt-3">


                            <a href="{{ asset('storage/'.$organization->real_estate_broker_license) }}"
                               target="_blank"
                               class="btn btn-outline-primary btn-sm">


                                <i class="fas fa-eye"></i>

                                عرض


                            </a>


                        </div>



                    @else


                        <span class="badge badge-secondary">

                            غير موجود

                        </span>


                    @endif



                </div>


            </div>








           




        </div>


    </div>


</div>