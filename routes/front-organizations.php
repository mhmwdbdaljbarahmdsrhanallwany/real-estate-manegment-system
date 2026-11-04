<?php


use App\Http\Livewire\FrontOrganizations\Owner\Createowner;
use App\Http\Livewire\FrontOrganizations\Owner\Owner;
use App\Models\AccessContext;
use Illuminate\Support\Facades\Route;

Route::domain('{subdomain}.' . config('services.tenant.domain'))->where(['subdomain' => '[a-z0-9-]+',])->as('organizations.')
    ->middleware('auth')->group(function () {

        // لوحة تحكم المؤسسة
        Route::get('/organizations/dashboard', function () {

            // $context = app(AccessContext::class);

            // abort_unless(
            //     $context->context_type === 'ORGANIZATION' &&
            //     $context->status === 'ACTIVE',
            //     403
            // );

            return view('front-organizations.index', [
              
            ]);

        })->name('dashboard');

        // إدارة الملاك
        Route::prefix('owners')->name('owners.')->group(function () {

                Route::livewire('/', Owner::class)
                    ->name('index');
                 // إضافة مالك جديد
        // Route::livewire('/create', Createowner::class)
        //     ->name('create');

            });

    });