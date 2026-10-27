<?php

namespace App\Models\Admin\SystemSetting;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    
   


    public function menuLinks()
    {
        return [

            [
                'title' => 'الإعدادات العامة',
                'tab'   => 'general',
            ],


            [
                'title' => 'المؤسسات',
                'tab'   => 'organizations',
            ],


            [
                'title' => 'الاشتراكات',
                'tab'   => 'subscriptions',
            ],


            [
                'title' => ' ادارةالإجراءات',
                'tab'   => 'actions',
            ],


            [
                'title' => 'الإشعارات',
                'tab'   => 'notifications',
            ],


        ];
    }
    public function getShowRouteAttribute()
    {
        return route('admin.settings.show');
    }

}
