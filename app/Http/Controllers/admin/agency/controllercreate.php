<?php

namespace App\Http\Controllers\admin\agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class controllercreate extends Controller
{
    public function create(){
        return View('admin.agency.create');
    }
}
