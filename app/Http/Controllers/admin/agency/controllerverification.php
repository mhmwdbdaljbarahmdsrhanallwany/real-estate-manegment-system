<?php

namespace App\Http\Controllers\admin\agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class controllerverification extends Controller
{
    public function verification(){
        return View('admin.agency.verification');
    }
}
