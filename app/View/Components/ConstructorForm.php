<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConstructorForm extends Component
{
    public $selectes;
    public $rows;
    public $types=[];
    /**
     * Create a new component instance.
     */
    public function __construct($types=null, $rows = null,$selectes=null)
    {
        $this->types=$types;
        $this->rows= $rows;
        $this->selectes=$selectes;
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        
        return view('admin.components.constructor-form');
    }
}
