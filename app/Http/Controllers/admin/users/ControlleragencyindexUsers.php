<?php

namespace App\Http\Controllers\Admin\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ControlleragencyindexUsers extends Controller
{
    public function indexUsers()
    {
        return view('admin.users_roles.users.index');
    }


    public function create()
    {
        return view('admin.users_roles.users.create');
    }
}
