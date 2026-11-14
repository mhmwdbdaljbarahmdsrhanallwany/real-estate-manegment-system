<?php

use App\Http\Livewire\FrontOrganizations\Building\Buildings;
use App\Http\Livewire\FrontOrganizations\Building\Buildingscreate;
use App\Http\Livewire\FrontOrganizations\Building\BuildingsIndex;
use App\Http\Livewire\FrontOrganizations\Building\PropertyMapIndex;
use App\Http\Livewire\FrontOrganizations\Building\Unets\TypesUnite\TypsUnitsIndex;
use App\Http\Livewire\FrontOrganizations\Building\Unets\UnitsCreate;
use App\Http\Livewire\FrontOrganizations\Building\Unets\UnitsIndex;
use App\Http\Livewire\FrontOrganizations\Owner\Agents\OwnersAgents;
use App\Http\Livewire\FrontOrganizations\Owner\Createowner;
use App\Http\Livewire\FrontOrganizations\Owner\Owner;
use App\Http\Livewire\FrontOrganizations\Shared\PropertyMap;
use App\Http\Livewire\FrontOrganizations\Tenants\TenantsCreate;
use App\Http\Livewire\FrontOrganizations\Tenants\TenantsIndex;
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

               // إدارة الوحدات
        Route::prefix('units')->name('units.')->group(function () {
            // اظافه انواع الوحدات
             Route::livewire('/TypsUnits/create', TypsUnitsIndex::class)->name('CreateTypsUnits');
            //  اظهار الوحدات
             Route::livewire('/', UnitsIndex::class)->name('units-index');
            //   اضافه الوحدات
              Route::livewire('/units/Create', UnitsCreate::class)->name('units-create');
            // اظهار الوحدات المتاحة
              Route::livewire('/units/units-available', UnitsCreate::class)->name('units-available');
           
           
        });
        });

                // إدارة المستأجرين
        Route::prefix('tenants')->name('tenants.')->group(function () {
        
            // قائمه المستأجرين
             Route::livewire('/', TenantsIndex::class)->name('tenants-index');
            //  اظهار الوحدات
             Route::livewire('/create', TenantsCreate::class)->name('tenants-create');
            // //   اضافه الوحدات
            //   Route::livewire('/units/Create', UnitsCreate::class)->name('units-create');
            // // اظهار الوحدات المتاحة
            //   Route::livewire('/units/units-available', UnitsCreate::class)->name('units-available');
           
           
        });
    });