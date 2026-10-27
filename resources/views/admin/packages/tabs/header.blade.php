<div class="card border-0 shadow-sm mb-4">

    <div class="card-body py-4">

        <div class="media align-items-center">

            {{-- أيقونة الباقة --}}
            <div class="rounded-circle bg-warning text-white text-center mr-4"
                 style="width:75px;height:75px;line-height:75px;">

                <i class="fas fa-crown fa-2x"></i>

            </div>

            {{-- هوية الباقة --}}
            <div class="media-body">

                <h3 class="font-weight-bold mb-1">

                    {{ $package->name }}

                </h3>

                <p class="text-muted mb-3">

                    {{ $package->description ?: 'لا يوجد وصف لهذه الباقة.' }}

                </p>

                <span class="badge badge-primary px-3 py-2">

                    {{ strtoupper($package->code) }}

                </span>

                <span class="badge badge-info px-3 py-2">

                    المستوى {{ $package->level }}

                </span>

                <span class="badge badge-{{ $package->featured_color }} px-3 py-2">

                    {{ $package->featured_label }}

                </span>

                <span class="badge badge-{{ $package->status_color }} px-3 py-2">

                    {{ $package->status_label }}

                </span>

            </div>

        </div>

    </div>

</div>