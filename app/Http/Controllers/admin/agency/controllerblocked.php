<?php

namespace App\Http\Controllers\admin\agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class controllerblocked extends Controller
{
    public function index(){
        return View('admin.agency.blocked');
    }
}

