<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserControlleragencyRole extends Controller
{
    public function indexRoles()
    {
        return view('admin.users_roles.roles.index');
    }
}
