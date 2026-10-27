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
    public $restoreRoute;
public $forceDeleteRoute;
public $entity;
public $entityPlural;

public function __construct(
    $showRoute = null,
    $editRoute = null,
    $deleteRoute = null,
    $restoreRoute = null,
    $forceDeleteRoute = null,
    $deletedId = null,
    $save = null,
    $text = null,
    $check = null,
    $entity = null,
$entityPlural = null,
)
    {
        $this->restoreRoute = $restoreRoute;
$this->forceDeleteRoute = $forceDeleteRoute;
        $this->showRoute=$showRoute;
        $this->editRoute=$editRoute;
        $this->deleteRoute=$deleteRoute;
        $this->deletedId=$deletedId;
        $this->save=$save;
        $this->text=$text;
        $this->entity = $entity;
        $this->entityPlural = $entityPlural;
$this->check = is_array($check) ? $check : null;

$this->icon = match (strtoupper($check['status'] ?? '')) {
    'ACTIVE' => 'fas fa-check',
    'INACTIVE' => 'fas fa-ban',
    'EXPIRED' => 'fas fa-clock',

    // الحالات المستخدمة في أزرار المؤسسات الحالية
    'PENDING_APPROVAL' => 'fas fa-clipboard-list',
    'SUSPENDED' => 'fas fa-ban',

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
