<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PageHeader extends Component
{
    public string $title;

    public ?string $icon;

    public ?string $code;


    public function __construct(
        string $title,
        ?string $icon = null,
        ?string $code = null
    )
    {

        $this->title = $title;

        $this->icon = $icon;

        $this->code = $code;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('admin.components.page-header');
    }
}
