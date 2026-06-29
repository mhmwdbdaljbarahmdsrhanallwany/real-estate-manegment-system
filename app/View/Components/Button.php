<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
//ازرار التفاصيل والتعديل والاضافة القابلة لاعادة الاستخدام 
class Button extends Component
{
    public $showRoute;
    public $editRoute;
    public $deleteRoute;
    public $deletedId;
    public $save;
    public $text; 
    public $check; 
    public $icon; 

   
    public function __construct(
        $showRoute = null,
         $editRoute = null,
          $deleteRoute = null,
          $deletedId=null,
          $save = null,
          $text =null,
          $check=null,
          )
    {
        $this->showRoute=$showRoute;
        $this->editRoute=$editRoute;
        $this->deleteRoute=$deleteRoute;
        $this->deletedId=$deletedId;
        $this->save=$save;
        $this->text=$text;
        $this->check = is_array($check) ? $check : null;
        $this->icon = match($check['status'] ?? null) {

            'active' => 'fas fa-check',

            'pending_approval' => 'fas fa-clipboard-list',

            'suspended' => 'fas fa-ban',
            default => 'fas fa-circle-info',

        };
        





    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('admin.components.constrint');
    }
}
