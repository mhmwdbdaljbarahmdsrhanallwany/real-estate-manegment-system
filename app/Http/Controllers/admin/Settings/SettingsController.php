<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Admin\ActionType;
use App\Models\Admin\Subscriptions\Subscription;
use App\Models\Admin\SystemSetting\SystemSetting;
use App\Services\MenuService;
use App\Services\Settings\ActionSettingsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    protected $actionSettingsService;
    protected $menuService;
    public function __construct(
        MenuService $menuService, 
        ActionSettingsService $actionSettingsService
        )
    {
        $this->menuService = $menuService;
        $this->actionSettingsService = $actionSettingsService;
    }
    public function index( $tab = 'general'){

        $settings = new SystemSetting();
        $links = $this->menuService->getLinks($settings);
          return View(
            'admin.settings.index',
            compact(
                'settings',
                'links',
                'tab'
            ));
    }
    public function show($tab='general')
    {
        $settings = new SystemSetting();
        $links = $this->menuService->getLinks($settings);
        $data = [];
        switch ($tab) {
            case 'actions':
                $data = $this->actionSettingsService->indexData();
                break;
            default:
                $data = [];
        }
        return view('admin.settings.show',array_merge(compact('settings','links','tab'),$data));
    
    }
    public function actionShow($id,$tab='actions_show')
{

    $settings = new SystemSetting();
    $links = $this->menuService->getLinks($settings);
    $data = $this->actionSettingsService->showData($id);
    return view('admin.settings.show',array_merge(
        compact('settings','links','tab'),$data

        )
    );

}
}
