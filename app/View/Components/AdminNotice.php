<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdminNotice extends Component
{

    public $type;
    public $title;
    public $icon;

    /**
     * Create a new component instance.
     */
    public function __construct( $type = 'info',$title = null)
    {
      
        $this->type = $type;
        $this->title = $title;

        $this->icon = match($type) {

            'success' => 'fas fa-check-circle',

            'warning' => 'fas fa-triangle-exclamation',

            'danger' => 'fas fa-ban',

            default => 'fas fa-shield-alt',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('admin.components.admin-notice');
    }
}
