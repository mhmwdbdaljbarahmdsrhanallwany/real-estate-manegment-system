<?php

namespace App\Http\Controllers\admin\agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class controlleraccessHistory extends Controller
{
    public function index(){
        return View('admin.agency.accessHistory');
    }
}

