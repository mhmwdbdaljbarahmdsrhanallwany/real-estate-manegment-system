<?php

use App\Http\Livewire\FrontOrganizations\Auth\ForgotPassword;
use App\Http\Livewire\FrontOrganizations\Auth\Login;
use App\Http\Livewire\FrontOrganizations\Auth\ResetPassword;
use App\Http\Livewire\FrontOrganizations\Dashboard\Index;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// use App\Http\Livewire\FrontOrganizations\Dashboard\Index;
// use App\Http\Livewire\FrontOrganizations\Dashboard\Overview;


Route::domain('{subdomain}.' . config('services.tenant.domain'))
    ->as('organizations.')
    ->group(function () {



        Route::middleware('guest')->group(function () {

            // تسجيل الدخول
            Route::livewire('/', Login::class)
                ->name('login');
        });


        /*
        |--------------------------------------------------------------------------
        | Dashboard Routes
        |--------------------------------------------------------------------------
        */

        Route::middleware('auth')->group(function () {

            // الصفحة الرئيسية للداش بورد
            Route::livewire('/dashboard', Index::class)
                ->name('dashboard');



            // تسجيل الخروج والعودة مباشرة إلى صفحة Login
            Route::post('/logout', function (\Illuminate\Http\Request $request) {

                // حفظ النطاق الفرعي
                $subdomain = $request->route('subdomain');

                // تسجيل خروج المستخدم من الحارس نفسه المستخدم في Login
                Auth::guard('web')->logout();

                // إلغاء الجلسة الحالية وحذف بياناتها
                $request->session()->invalidate();

                // إنشاء CSRF Token جديد
                $request->session()->regenerateToken();

                // العودة إلى صفحة تسجيل الدخول الخاصة بالمؤسسة
                return redirect()->route('organizations.login', [
                    'subdomain' => $subdomain,
                ]);
            })->name('logout');
        });
    });

    












//      <?php

// use App\Models\AccessContext;
// use Illuminate\Support\Facades\Route;

// Route::middleware(['auth'])
//     ->prefix('organizations')
//     ->name('organizations.')
//     ->group(function () {

//         Route::get('/dashboard', function () {

//             $context = app(AccessContext::class);

//             // التأكد من أن السياق الحالي تابع لمؤسسة.
//             abort_unless(
//                 $context->context_type === 'ORGANIZATION',
//                 403,
//                 'This page is only available for organization contexts.'
//             );

//             return view('front-organizations.index', [
//                 'organizationId' => $context->organization_id,
//                 'userId' => auth()->id(),
//             ]);

//         })->name('dashboard');

//     });