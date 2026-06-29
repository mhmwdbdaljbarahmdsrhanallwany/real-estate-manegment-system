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
                    'route' => route('admin.agencies.index'),
                    'active_pattern' => 'admin.agencies.*'
                ],
                [
                    'title' => ' الباقات',
                    'route' => route('admin.subscription.index'),
                    'active_pattern' => 'admin.subscription.*'
                ],
                [
                    'title' => ' الاشتراكات',
                    'route' => route('admin.agencysubscription.index'),
                    'active_pattern' => 'admin.agencysubscription.*'
                ],
                [
                    'title' => ' المستندات',
                    'route' => route('admin.agencies.documents'),
                    'active_pattern' => 'admin.agencies.*'
                ],
            ];
    
            $menu = [
                ['title' => 'إضافة مؤسسة', 'route' => route('admin.agencies.create')],
                ['title' => 'إضافة باقة جديدة', 'route' => route('admin.subscription.create')],
                ['title' => 'إضافة اشتراك جديد', 'route' => route('admin.agencysubscription.create')],
            ];
    
            $view->with(compact('links', 'menu'));
        });
    }
    
}
