<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InfoCard extends Component
{
    
    public string $title;

    public ?string $icon;



    public function __construct(

        string $title,

        ?string $icon = null

    )
    {

        $this->title = $title;

        $this->icon = $icon;

    }



    
     
    public function render(): View|Closure|string
    {
        return view('admin.components.info-card');
    }
}
