<?php

namespace App\Services;

class MenuService
{

    public function getLinks($model)
    {
        return collect($model->menuLinks())

        ->map(function ($link) use ($model) {

            $link['url'] =
                $model->show_route . '/' . $link['tab'];
            // التبويب الحالي
            $currentTab =
                request()->route('tab') ?? 'info';

            $link['active'] =
                $currentTab == $link['tab'];
            return $link;
        })

        ->toArray();

    }


}