<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatCard extends Component
{
    public string $title;
    public mixed $value;
    public string $icon;
    public string $color;
    public ?string $statKey;

    public function __construct(
        string $title,
        mixed $value,
        string $icon = 'fas fa-chart-bar',
        string $color = 'primary',
        ?string $statKey = null
    ) {
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
        $this->color = $color;
        $this->statKey = $statKey;
    }

    public function render(): View|Closure|string
    {
        return view('admin.components.stat-card');
    }
}
