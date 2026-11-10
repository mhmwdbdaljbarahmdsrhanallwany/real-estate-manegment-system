<?php

use App\Http\Livewire\FrontOrganizations\Building\Buildings;
use App\Http\Livewire\FrontOrganizations\Building\Buildingscreate;
use App\Http\Livewire\FrontOrganizations\Building\BuildingsIndex;
use App\Http\Livewire\FrontOrganizations\Building\PropertyMapIndex;
use App\Http\Livewire\FrontOrganizations\Owner\Agents\OwnersAgents;
use App\Http\Livewire\FrontOrganizations\Owner\Createowner;
use App\Http\Livewire\FrontOrganizations\Owner\Owner;
use App\Http\Livewire\FrontOrganizations\Shared\PropertyMap;
use App\Models\AccessContext;
use Illuminate\Support\Facades\Route;

Route::domain('{subdomain}.' . config('services.tenant.domain'))
    ->where(['subdomain' => '[a-z0-9-]+'])
    ->as('organizations.')
    ->middleware('auth')
    ->group(function () {

        // لوحة تحكم المؤسسة
        Route::get('/organizations/dashboard', function () {
            return view('front-organizations.index', []);
        })->name('dashboard');

        // إدارة الملاك
        Route::prefix('owners')->name('owners.')->group(function () {
            Route::livewire('/', Owner::class)->name('index');
            Route::livewire('/create', Createowner::class)->name('create');
            Route::livewire('/Owner/Owner-agents', OwnersAgents::class)->name('Owner-agents-index');
        });

        // إدارة المباني
        Route::prefix('buildings')->name('buildings.')->group(function () {
            //  Route::livewire('/', Buildings::class)->name('buildings-index');
            Route::livewire('/Buildings/create', Buildingscreate::class)->name('CreateBuildings');
            Route::livewire('/Buildings/PropertyMap', PropertyMapIndex::class)->name('BuildingsPropertyMap');

           
        });
    });