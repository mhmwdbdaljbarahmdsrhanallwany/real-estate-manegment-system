<?php

namespace App\Http\Controllers\Admin\AccessContexts;

use App\Http\Controllers\Controller;
use App\Models\AccessContext;
use Illuminate\Http\Request;

class AccessContextController extends Controller
{
    /**
     * عرض جميع سياقات الوصول.
     */
    public function index()
    {
        $contexts = AccessContext::with('organization')
            ->withCount('assignments')
            ->latest()
            ->paginate(15);
    
        $totalContexts = AccessContext::count();
    
        $activeContexts = AccessContext::where('status', 'ACTIVE')
            ->count();
    
        $inactiveContexts = AccessContext::where('status', 'INACTIVE')
            ->count();
    
        return view('admin.access_contexts.index', compact(
            'contexts',
            'totalContexts',
            'activeContexts',
            'inactiveContexts'
        ));
    }

    /**
     * عرض تفاصيل سياق الوصول.
     */
 
public function show($id, $tab = 'info')
{
    $context = AccessContext::with([
        'organization',
        'createdBy',
        'updatedBy',
    ])
        ->withCount('assignments')
        ->findOrFail($id);

        if ($tab === 'assignments') {
            $context->load([
                'assignments.user',
                'assignments.userRoles.role',
            ]);
        }

    $links = app(\App\Services\MenuService::class)
        ->getLinks($context);

    return view('admin.access_contexts.show', compact(
        'context',
        'links',
        'tab'
    ));
}


}

