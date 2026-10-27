<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        View::composer('admin.incloude.agencynavepare', function ($view) {
            $links = [
                [
                    'title' => ' المؤسسات',
                    'route' => route('admin.organizations.index'),
                    'active_pattern' => 'admin.organizations.*'
                ],
                [
                    'title' => ' الباقات',
                    'route' => route('admin.packages.index'),
                    'active_pattern' => 'admin.packages.*'
                ],
                [
                    'title' => ' الاشتراكات',
                    'route' => route('admin.subscriptions.index'),
                    'active_pattern' => 'admin.subscriptions.*'
                ],
                // [
                //     'title' => ' المستندات',
                //     'route' =>'admin.agencies.documents',
                //     'active_pattern' => 'gggg'
                // ],
            ];
    
            $menu = [
                ['title' => 'إضافة مؤسسة', 'route' => route('admin.organizations.create')],
                ['title' => 'إضافة باقة جديدة', 'route' => route('admin.packages.create')],
                ['title' => 'إضافة اشتراك جديد', 'route' => route('admin.subscriptions.create')],
            ];
    
            $view->with(compact('links', 'menu'));
        });
    }
    
}
