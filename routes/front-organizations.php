 <?php

use App\Models\AccessContext;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('organizations')
    ->name('organizations.')
    ->group(function () {

        Route::get('/dashboard', function () {

            $context = app(AccessContext::class);

            // التأكد من أن السياق الحالي تابع لمؤسسة.
            abort_unless(
                $context->context_type === 'ORGANIZATION',
                403,
                'This page is only available for organization contexts.'
            );

            return view('agencies.index', [
                'organizationId' => $context->organization_id,
                'userId' => auth()->id(),
            ]);

        })->name('dashboard');

    });