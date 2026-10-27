<div class="card shadow-sm border-0 mb-4" style="border-right: 4px solid #00F5D4 !important;margin-top: 10px">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <div>

                <h4 class="mb-1">

                    @if($icon)
                        {{-- استبدلنا text-primary بلون الأخضر الرقمي أو كلاس مخصص --}}
                        <i class="{{ $icon }}" style="color: #00F5D4;"></i>
                    @endif

                    {{ $title }}

                </h4>

                @if($code)

                    <code class="text-muted">
                        {{ $code }}
                    </code>

                @endif

            </div>

            <div>

                {{ $slot }}

            </div>

        </div>

    </div>

</div>