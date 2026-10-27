<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessContext;
use Illuminate\Http\Request;

class HomeController extends Controller
{
public function index()
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    // سياق الوصول الذي حدده AccessContextMiddleware
    $context = app(AccessContext::class);
    // تعيين المستخدم في سياق الوصول الحالي
    $assignment = null;
    $role = null;

    if ($context) {
        $assignment = $user->assignments()
            ->where('access_context_id', $context->id)
            ->where('status', 'ACTIVE')
            ->first();

        $role = $assignment?->userRoles()
            ->where('status', 'ACTIVE')
            ->with('role')
            ->first()
            ?->role;
    }

    return view('admin.home', compact(
        'user',
        'role',
        'context'
    ));
}



}
