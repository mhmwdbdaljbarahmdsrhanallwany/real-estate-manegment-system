<?php

use Illuminate\Support\Facades\Route;

Route::prefix('branch')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('agency-branches.index');
        });

});