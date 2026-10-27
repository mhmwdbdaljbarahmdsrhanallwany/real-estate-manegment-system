
@props([
    'title',
    'value',
    'icon',
    'color' => 'info',
    'statKey' => null,
])

<div class="col-xl-3 col-md-6 mb-3">
    <div class="card shadow-sm text-center h-100">
        <div class="card-body">

            <i class="{{ $icon }} fa-2x mb-2 text-{{ $color }}"></i>

            <h3
                @if($statKey)
                    data-stat-key="{{ $statKey }}"
                @endif
            >{{ $value }}</h3>

            <small class="text-muted">{{ $title }}</small>

        </div>
    </div>
</div>
