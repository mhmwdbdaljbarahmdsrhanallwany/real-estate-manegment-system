
{{-- معلومات الحساب --}}
<div class="row mt-4">

    <div class="col-12">

        <x-info-card
            title="معلومات الحساب"
            icon="fas fa-user-circle"
        >

            <div class="row">

                {{-- اسم المستخدم --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="d-flex align-items-center">

                        <div class="text-info ml-3">
                            <i class="fas fa-user fa-lg"></i>
                        </div>

                        <div>
                            <small class="text-muted d-block mb-1">
                                اسم المستخدم
                            </small>

                            <strong>{{ $user->name }}</strong>
                        </div>

                    </div>
                </div>

                {{-- البريد الإلكتروني --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="d-flex align-items-center">

                        <div class="text-info ml-3">
                            <i class="fas fa-envelope fa-lg"></i>
                        </div>

                        <div>
                            <small class="text-muted d-block mb-1">
                                البريد الإلكتروني
                            </small>

                            <strong>{{ $user->email }}</strong>
                        </div>

                    </div>
                </div>

            </div>

            <hr>

            {{-- معلومات التسجيل --}}
            <h6 class="font-weight-bold mb-4">
                <i class="far fa-clock text-muted ml-1"></i>
                معلومات التسجيل
            </h6>

            <div class="row">

                {{-- تاريخ إنشاء الحساب --}}
                <div class="col-lg-4 col-md-6 mb-3">
                    <small class="text-muted d-block mb-2">
                        تاريخ إنشاء الحساب
                    </small>

                    <strong>
                        {{ $user->created_at
                            ? $user->created_at->format('Y-m-d H:i')
                            : '—' }}
                    </strong>
                </div>

                {{-- آخر تحديث --}}
                <div class="col-lg-4 col-md-6 mb-3">
                    <small class="text-muted d-block mb-2">
                        آخر تحديث
                    </small>

                    <strong>
                        {{ $user->updated_at
                            ? $user->updated_at->format('Y-m-d H:i')
                            : '—' }}
                    </strong>
                </div>

            </div>

        </x-info-card>

    </div>

</div>

