<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConstructorTable extends Component
{
    public $files;
    public $id;
    public $title;
    public $icon;
    public $filters;
    public $sorts;
    public $columns;
    public $fields;
    public $rows;
    public $actions;
    public $primaryKey;
    public $tableid;
    public $firstItem;
    public $lastItem;
    public $total;
    public $pagination;

    public function __construct(
       
        $title, 
        $id=null,
        $icon = 'fas fa-table',
        $filters = null,
        $sorts  =null,
        $columns = [],
        $fields = null,
        $rows = [],
        $actions = null,
        $primaryKey = 'id',
        $tableid = null,
        $firstItem = null,
        $lastItem = null,
        $total = null,
        $files = null,
        $pagination = null
    ) {
        $this->files=$files;
        $this->title= $title;
        $this->icon= $icon;
        $this->id= $id;
        $this->filters= $filters;
        $this->sorts= $sorts;
        $this->columns= $columns;
        $this->fields= $fields;
        $this->rows= $rows;
        $this->actions= $actions;
        $this->primaryKey= $primaryKey;
        $this->tableid= $tableid;
        $this->firstItem= $firstItem;
        $this->lastItem= $lastItem;
        $this->total= $total;
        $this->pagination= $pagination;
  //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('admin.components.constructor-table');
    }
}
