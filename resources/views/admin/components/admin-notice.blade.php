<div class="glass-note {{ $type }} mt-4">

    <div class="glass-icon">
        <i class="{{ $icon }}"></i>
    </div>

    <div class="glass-body">

        @if($title)
            <div class="glass-title">
                {{ $title }}
            </div>
        @endif

        <div class="glass-text">
            {{ $slot }}
        </div>

    </div>

</div>
