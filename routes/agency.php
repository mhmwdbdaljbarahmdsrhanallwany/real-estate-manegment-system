<?php

use Illuminate\Support\Facades\Route;

Route::prefix('agency')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('agencies.index');
        });

});