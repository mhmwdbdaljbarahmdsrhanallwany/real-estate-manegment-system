<?php

use App\Http\Controllers\Admin\AccessContexts\AccessContextController;
use App\Http\Controllers\Admin\Cashboxes\CashboxController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\Invoices\InvoiceController;
use App\Http\Controllers\Admin\Organizations\DocumentController;
use App\Http\Controllers\Admin\Organizations\OrganizationController;
// todo نهاية مسارات المؤسسات
use App\Http\Controllers\Admin\Packages\PackageController;
use App\Http\Controllers\Admin\Payments\PaymentController;
use App\Http\Controllers\Admin\Profile\ProfileController;
// todo مسارات المستخدمين
use App\Http\Controllers\Admin\Roles\UserControllerRole;
use App\Http\Controllers\Admin\Settings\SettingsController;
use App\Http\Controllers\Admin\Subscriptions\SubscriptionController;
use App\Http\Controllers\Admin\UsersRoles\PermissionController;
use App\Http\Controllers\Admin\UsersRoles\PermissionModuleController;
use App\Http\Controllers\Admin\UsersRoles\UserAssignmentController;
use App\Http\Controllers\Admin\UsersRoles\UserController;
use App\Models\AccessContext;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['access.context'])->get('/test-context', function () {

    $context = app(AccessContext::class);

    return [
        'id' => $context->id,
        'name' => $context->name,
        'type' => $context->context_type,
        'domain' => $context->domain,
    ];

});
Route::get('/login-test', function () {

    $user = User::where('email', 'admin@example.com')->first();

    auth()->login($user);

    return 'Logged in';

});

Route::middleware([
    'auth',
    'access.context',
    'permission:properties.create',
])->get('/test-permission', function () {

    return 'Permission Granted';

});
require __DIR__.'/front-organizations.php';
require __DIR__.'/organization-branches.php';
// ==================== المجموعة العامة (بدون مصادقة) ====================
// تجميع جميع مسارات الموقع العامة التي لا تحتاج تسجيل دخول
Route::group([
    'as' => 'admin.', // بادئة لأسماء المسارات (مثال: front.home, front.about.index)
],
    function () {

        // ==================== الصفحة الرئيسية ====================
        // المسار الرئيسي للموقع (الصفحة الافتتاحية)
        Route::middleware(['auth', 'access.context'])
            ->get('/', [HomeController::class, 'index'])
            ->name('home'); // اسم المسار: admin.home - يستخدم في الروابط مثل route('admin.home')

        Route::get('/waiting-for-activation', function () {
            return view('auth.organization-waiting');
        })->name('organization.waiting');
        // ==================== المؤسسات ====================

        Route::prefix('organizations')->name('organizations.')->group(function () {
            Route::get('/', [OrganizationController::class, 'index'])
                ->name('index'); // admin.organizations.index
            Route::get('/registration-form', [OrganizationController::class, 'registrationForm'])
                ->name('registration_form');
            // عرض واجهة الاضاف لاضافة مؤسسه جديدة
            Route::get('/create', [OrganizationController::class, 'create'])
                ->name('create'); // admin.organizations.create
            Route::post('/delete-file/{id}/{field}', [OrganizationController::class, 'deleteFile'])
                ->name('deleteFile');
            Route::post('/ajax_organizations', [OrganizationController::class, 'ajax_organizations'])
                ->name('ajax_organizations'); // admin.organizations.ajax_packages

            Route::get('/detaile/{id}', [OrganizationController::class, 'detaile'])
                ->name('detaile'); // admin.organizations.Detaile

            //  ادخال بيانات مؤسسه وحفظها
            Route::post('/store', [OrganizationController::class, 'store'])
                ->name('store'); // admin.organizations.store
            // صفحة تعديل بيانات مؤسسه موجودة
            Route::get('/edit/{id}', [OrganizationController::class, 'edit'])
                ->name('edit'); // admin.organizations.edit

            Route::delete('/destroy/{id}', [OrganizationController::class, 'destroy'])
                ->name('destroy'); // admin.organizations.destroy
            Route::get('/trashed', [OrganizationController::class, 'trashed'])
                ->name('trashed'); // admin.organizations.trashed
            Route::post('/restore/{id}', [OrganizationController::class, 'restore'])
                ->name('restore'); // admin.organizations.restore
            Route::post('/force-delete/{id}', [OrganizationController::class, 'forceDestroy'])
                ->name('forceDestroy'); // admin.organizations.forceDestroy
            Route::post('/ajax-trashed', [OrganizationController::class, 'ajax_trashed_organizations'])
                ->name('ajax_trashed'); // admin.organizations.ajax_trashed

            Route::get('/show/{id}/{tab?}', [OrganizationController::class, 'show'])
                ->name('show'); // admin.organizations.show
            Route::put('/update/{id}', [OrganizationController::class, 'update'])
                ->name('update'); // admin.organizations.edit

            Route::get('/documents', [DocumentController::class, 'index'])
                ->name('documents'); // admin.agencies.documents
            Route::match(['get', 'post'], '/ajax_search_documents', [DocumentController::class, 'ajax_organization_documents'])
                ->name('ajax_search_documents'); // admin.organizations.delete
            Route::match(['get', 'post'],
                '/change_status',
                [DocumentController::class, 'changeStatus']
            )->name('change_status'); // admin.organizations.change_status

        });

        Route::prefix('packages')->name('packages.')->group(function () {
            // عرض الباقات
            Route::get('/', [PackageController::class, 'index'])
                ->name('index'); // admin.packages.index
            // عرض واجهة الاضاف لاضافة باقة جديدة
            Route::get('/create', [PackageController::class, 'create'])
                ->name('create'); // admin.packages.create

            Route::match(['get', 'post'], '/ajax_packages', [PackageController::class, 'ajax_packages'])
                ->name('ajax_packages'); // admin.packages.ajax_packages

            Route::get('/show/{id}/{tab?}', [PackageController::class, 'show'])
                ->name('show'); // admin.organizations.show

            //  ادخال بيانات الباقة وحفظها
            Route::post('/store', [PackageController::class, 'store'])
                ->name('store'); // admin.packages.store
            // صفحة تعديل بيانات باقة موجودة
            Route::get('/edit/{id}', [PackageController::class, 'edit'])
                ->name('edit'); // admin.packages.edit
            Route::post('/delete/{id}', [PackageController::class, 'delete'])
                ->name('delete'); // admin.packages.delete
            Route::post('/update/{id}', [PackageController::class, 'update'])
                ->name('update'); // admin.packages.edit
        });

        // اشتراكات المؤسسات
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            // عرض الاشتراكات
            Route::get('/', [SubscriptionController::class, 'index'])
                ->name('index'); // admin.agency.subscriptions.index
            //    البحث والفلترة في جدول الاشتراكات باستخدام اجاكس
            Route::match(['get', 'post'], '/ajax_search_subscription', [SubscriptionController::class, 'ajax_search_subscription'])
                ->name('ajax_search_subscription'); // admin.agencies.ajax_search_subscription
            Route::get('/show/{id}/{tab?}', [SubscriptionController::class, 'show'])
                ->name('show'); // admin.subscriptions.show
            //    عرض تفاصيل اشتراك حسب معرف محدد
            Route::get('/detaile/{id}', [SubscriptionController::class, 'detaile'])
                ->name('detaile'); // admin.subscriptions.detaile

            // عرض واجهة الاضاف لاضافة اشتراك جديد
            Route::get('/create', [SubscriptionController::class, 'create'])
                ->name('create'); // admin.agency.subscriptions.create
            // ادخال بيانات الاشتراك وحفظ الاشتراك
            Route::post('/store', [SubscriptionController::class, 'store'])
                ->name('store'); // admin.subscriptions.store
            // صفحة تعديل بيانات اشتراك موجودة
            Route::get('/edit/{id}', [SubscriptionController::class, 'edit'])
                ->name('edit'); // admin.subscriptions.edit
            // تحديث بيانات الاشتراك وحفظها
            Route::put('/update/{id}', [SubscriptionController::class, 'update'])
                ->name('update'); // admin.subscriptions.edit

            // تفعيل الاشتراك
            Route::post('/activate/{id}', [SubscriptionController::class, 'activate'])
                ->name('activate');

            // تجديد الاشتراك
            Route::post('/renew/{id}', [SubscriptionController::class, 'renew'])
                ->name('renew');

            // ترقية باقة الاشتراك
            Route::post('/upgrade/{id}', [SubscriptionController::class, 'upgrade'])
                ->name('upgrade');

            // تمديد فترة الاشتراك
            Route::post('/extend/{id}', [SubscriptionController::class, 'extend'])
                ->name('extend');

            // تطبيق الغرامة المستحقة على الاشتراك
            Route::post('/apply-penalty/{id}', [SubscriptionController::class, 'applyPenalty'])
                ->name('apply-penalty');

            // إيقاف الاشتراك
            Route::post('/stop/{id}', [SubscriptionController::class, 'stop'])
                ->name('stop');

            //  حذف اشتراك موجودة
            Route::post('/delete/{id}', [SubscriptionController::class, 'delete'])
                ->name('delete'); // admin.subscriptions.delete

        });

        Route::prefix('invoices')->name('invoices.')->group(function () {

            // عرض قائمة الفواتير (إذا كانت دالة index موجودة).
            Route::get('/', [InvoiceController::class, 'index'])
                ->name('index');
            Route::post(
                '/ajax-search',
                [InvoiceController::class, 'ajaxSearch']
            )->name('ajax_search');
            Route::get(
                '/test-billing-periods',
                [InvoiceController::class, 'testBillingPeriods']
            )->name('test-billing-periods');
            // عرض تفاصيل فاتورة محددة.
            Route::get('/show/{id}', [InvoiceController::class, 'show'])
                ->name('show');

            // فتح الفاتورة في وضع الطباعة.
            Route::get('/print/{id}', [InvoiceController::class, 'print'])
                ->name('print');

        });

        Route::prefix('cashboxes')->name('cashboxes.')->middleware('auth')->group(function () {
            Route::get('/', [CashboxController::class, 'index'])
                ->name('index');

            Route::get('/create', [CashboxController::class, 'create'])
                ->name('create');

            Route::post('/store', [CashboxController::class, 'store'])
                ->name('store');

            Route::get('/ajax-search', [CashboxController::class, 'ajaxSearch'])
                ->name('ajax_search');

            Route::get('/{cashboxe}/edit', [CashboxController::class, 'edit'])
                ->name('edit');
            Route::post(
                '/change-status',
                [CashboxController::class, 'changeStatus']
            )->name('change_status');
            Route::get(
                '/notices',
                [CashboxController::class, 'notices']
            )->name('notices');
            Route::put('/{cashboxe}', [CashboxController::class, 'update'])
                ->name('update');
        });

        Route::prefix('payments')
            ->name('payments.')
            ->middleware('auth')
            ->group(function () {

                // عرض صفحة الدفعات
                Route::get('/', [PaymentController::class, 'index'])
                    ->name('index');

                // جلب الدفعات عبر AJAX
                Route::get('/ajax-search', [PaymentController::class, 'ajaxSearch'])
                    ->name('ajax_search');
// عرض نموذج تسجيل دفعة جديدة
Route::get('/create', [PaymentController::class, 'create'])
    ->name('create');
                // تسجيل دفعة جديدة
                Route::post('/store', [PaymentController::class, 'store'])
                    ->name('store');

              

            });

        Route::prefix('access_contexts')->name('access_contexts.')->group(function () {

            Route::get('/', [AccessContextController::class, 'index'])
                ->name('index');

            Route::get('/show/{id}/{tab?}', [AccessContextController::class, 'show'])
                ->name('show');

        });

        Route::prefix('settings')
            ->name('settings.')
            ->group(function () {

                Route::get(
                    '/{tab?}',
                    [SettingsController::class, 'show']
                )
                    ->name('show');
                Route::get(
                    '/actions/{id}',
                    [SettingsController::class, 'actionShow']
                )
                    ->name('actions.show');

                Route::get('/actions',
                    [SettingsController::class, 'index']
                )
                    ->name('actions');

                Route::get('/notifications',
                    [SettingsController::class, 'index']
                )
                    ->name('notifications');

            });
        // المستخدمين المسارات المتعلقة بإدارة المستخدمين تبدأ بـ /users
        Route::prefix('users')->name('users.')->group(function () {
            // عرض جميع المستخدمين
            Route::get('/', [UserController::class, 'index'])
                
                ->name('index');

            Route::match(['get', 'post'], '/ajax_users', [UserController::class, 'ajax_users'])
                ->middleware('permission:users.view')
                ->name('ajax_users'); // admin.users.create
            Route::get('/edit/{id}', [UserController::class, 'edit'])
                ->name('edit');
            Route::get('/show/{id}/{tab?}', [UserController::class, 'show'])
                ->name('show');
            Route::put('/update/{id}', [UserController::class, 'update'])
                ->name('update');

            Route::delete('/delete/{id}', [UserController::class, 'delete'])
                ->name('delete');
            Route::post('/restore/{id}', [UserController::class, 'restore'])
                ->name('restore');
            // صفحة إضافة مستخدم جديد
            Route::get('/create', [UserController::class, 'create'])
                ->name('create'); // admin.users.create
            Route::post('/store', [UserController::class, 'store'])
                ->name('store');
        });

        Route::prefix('permissions')
                   ->name('permissions.')
                   ->group(function () {

                       // عرض جميع الصلاحيات
                       Route::get('/', [PermissionController::class, 'index'])
                           ->name('index');

                       // صفحة إضافة صلاحية
                       Route::get('/create', [PermissionController::class, 'create'])
                           ->name('create');
                       Route::post('/', [PermissionController::class, 'store'])
                           ->name('store');
                       Route::get('/{permission}/edit', [PermissionController::class, 'edit'])
                           ->name('edit');

                       Route::put('/{permission}', [PermissionController::class, 'update'])
                           ->name('update');

                   });

        Route::prefix('permission-modules')
            ->name('permission-modules.')
            ->group(function () {

                // صفحة إنشاء وحدة جديدة
                Route::get('/create', [
                    PermissionModuleController::class,
                    'create',
                ])->name('create');

                // حفظ الوحدة الجديدة
                Route::post('/', [
                    PermissionModuleController::class,
                    'store',
                ])->name('store');

            });

        Route::prefix('user-assignments')
            ->name('user_assignments.')
            ->group(function () {
                Route::get('/', [UserAssignmentController::class, 'index'])->name('index');
                Route::post('/ajax', [UserAssignmentController::class, 'ajax_assignments'])
                    ->name('ajax_assignments');
                Route::get('/stats', [UserAssignmentController::class, 'stats'])
                    ->name('stats');
                Route::get('/{id}/edit', [UserAssignmentController::class, 'edit'])->name('edit');
                Route::put('/{id}', [UserAssignmentController::class, 'update'])->name('update');
                Route::patch('/{id}/status', [UserAssignmentController::class, 'updateStatus'])
                    ->name('updateStatus');
            });

        Route::prefix('roles')->name('roles.')->group(function () {

            Route::get('/', [
                UserControllerRole::class,
                'index',
            ])->name('index');

            Route::post('/ajax', [
                UserControllerRole::class,
                'ajax_roles',
            ])->name('ajax_roles');

            Route::get('/create', [
                UserControllerRole::class,
                'createRole',
            ])->name('create');

            Route::post('/', [
                UserControllerRole::class,
                'storeRole',
            ])->name('store');

            // تعديل الدور وحفظ التعديلات
            Route::get('/{id}/edit', [
                UserControllerRole::class,
                'editRole',
            ])->name('edit');

            Route::put('/{id}', [
                UserControllerRole::class,
                'updateRole',
            ])->name('update');

            // الحذف المنطقي
            Route::delete('/{id}', [
                UserControllerRole::class,
                'destroy',
            ])->name('destroy');
            Route::post('/{id}/change-status', [
                UserControllerRole::class,
                'changeStatus',
            ])->name('change_status');
            Route::post('/{id}/restore', [UserControllerRole::class, 'restoreRole'])
                ->name('restore');
            Route::delete('/{id}/force-delete', [UserControllerRole::class, 'forceDestroy'])
                ->name('forceDestroy');
            Route::put(
                '/{id}/permissions',
                [UserControllerRole::class, 'updatePermissions']
            )->name('updatePermissions');
            // يجب أن يأتي مسار العرض العام أخيرًا
            Route::get('/{id}/{tab?}', [
                UserControllerRole::class,
                'show',
            ])->name('show');

        });
        Route::prefix('profile')->name('profile.')->group(function () {

            Route::get('/', [ProfileController::class, 'show'])
                ->name('show');

            Route::get('/edit', [ProfileController::class, 'edit'])
                ->name('edit');

            Route::put('/update', [ProfileController::class, 'update'])
                ->name('update');
        });
    });
