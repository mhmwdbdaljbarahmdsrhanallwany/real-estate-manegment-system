<?php

use App\Http\Controllers\Admin\agency\controlleraccesshistory;
use App\Http\Controllers\Admin\agency\Controlleragency;
use App\Http\Controllers\Admin\agency\controllerblocked;
use App\Http\Controllers\Admin\agency\controllercreate;
use App\Http\Controllers\Admin\agency\controllerdocument;
use App\Http\Controllers\admin\agency\controllerverification;
use App\Http\Controllers\Admin\agency\controllerview;
use App\Http\Controllers\Admin\AgencySubscriptions\AgencySubscriptionController;
use App\Http\Controllers\Admin\HomeController;
// todo نهاية مسارات المؤسسات

// todo مسارات المستخدمين
use App\Http\Controllers\Admin\Roles\UserControlleragencyRole;
use App\Http\Controllers\Admin\subscription\ControllerSubscription;
use App\Http\Controllers\Admin\users\ControlleragencyindexUsers;
use App\Http\Controllers\Admin\users\UserControlleragency;
use Illuminate\Support\Facades\Route;





require __DIR__.'/agency.php';
require __DIR__.'/agency-branches.php';
// ==================== المجموعة العامة (بدون مصادقة) ====================
// تجميع جميع مسارات الموقع العامة التي لا تحتاج تسجيل دخول
Route::group([
    'as' => 'admin.', // بادئة لأسماء المسارات (مثال: front.home, front.about.index)
],
    function () {

        // ==================== الصفحة الرئيسية ====================
        // المسار الرئيسي للموقع (الصفحة الافتتاحية)
        Route::get('/', [HomeController::class, 'index']) // طلب GET للصفحة الرئيسية
            ->name('home'); // اسم المسار: admin.home - يستخدم في الروابط مثل route('admin.home')

        // ==================== المؤسسات ====================
        Route::prefix('agencies')->name('agencies.')->group(function () {

            // عرض جميع المؤسسات
            Route::get('/', [Controlleragency::class, 'index'])
                ->name('index'); // admin.agencies.index

            Route::post('/ajax_search', [Controlleragency::class, 'ajax_search'])
                ->name('ajax_search'); // admin.agencies.ajax_search

            Route::post('/store', [Controlleragency::class, 'store'])
                ->name('store'); // admin.agencies.store

            Route::get('/edit/{id}', [Controlleragency::class, 'edit'])
                ->name('edit'); // admin.agencies.edit
            // حفظ التحديث على المؤسسة
            Route::put('/update/{id}', [Controlleragency::class, 'update'])
                ->name('update'); // admin.agencies.update
            // حذف مؤسسة
            Route::post('/delete/{id}', [Controlleragency::class, 'delete'])
                ->name('delete');         // admin.agencies.delete
            Route::post('/delete-file/{id}/{field}/{index}', [Controlleragency::class, 'deleteFile'])
                ->name('deleteFile');

            // عرض تفاصيل المؤسسة
            Route::get('/detaile/{id}', [controllerview::class, 'detaile'])
                ->name('detaile'); // admin.agencies.Detaile
            // تحديث بيانت الموسسة في صفحة الفاصيل
            Route::get('/agencydata/{id}', [controllerview::class, 'agencydata'])
                ->name('agencydata');
            //    فورم تعديل بيانات المؤسسة الاساسية
            Route::get('/edit-section', [controllerview::class, 'editSection'])
                ->name('editSection'); // admin.agencies.editSection
            // حفظ تعديلات الفورم
            Route::post('/update-section', [controllerview::class, 'updateSection'])
                ->name('updateSection');
            // /////////////////////////////////////////////////////////

            // /////////////////////////////////////////////////////

            // صفحة إضافة مؤسسة جديدة
            Route::get('/create', [controllercreate::class, 'create'])
                ->name('create'); // admin.agencies.create

            // طلبات التوثيق
            Route::get('/verification', [controllerverification::class, 'verification'])
                ->name('verification'); // admin.agencies.verification

            // مستندات المؤسسات
            Route::get('/documents', [controllerdocument::class, 'index'])
                ->name('documents'); // admin.agencies.documents
            Route::post('/ajax_search_document', [controllerdocument::class, 'ajax_searh_agency_document'])
                ->name('ajax_search_document'); // admin.agencies.ajax_search_document
            Route::post(
                '/change-status',
                [controllerdocument::class, 'changeStatus']
            )->name('change_status'); // admin.agencies.change_status

            // سجل العمليات
            Route::get('/access-history', [controlleraccesshistory::class, 'index'])
                ->name('access_history'); // admin.agencies.access_history

            // المؤسسات المحظورة
            Route::get('/blocked', [controllerblocked::class, 'index'])
                ->name('blocked'); // admin.agencies.blocked

        });
        // باقات الاشتراك
        Route::prefix('subscription')->name('subscription.')->group(function () {
            // عرض الباقات
            Route::get('/', [ControllerSubscription::class, 'index'])
                ->name('index'); // admin.subscription.index
            // عرض واجهة الاضاف لاضافة باقة جديدة
            Route::get('/create', [ControllerSubscription::class, 'create'])
                ->name('create'); // admin.subscription.create

            Route::post('/ajax_searchsup', [ControllerSubscription::class, 'ajax_searchsup'])
                ->name('ajax_searchsup'); // admin.agencies.ajax_search

            Route::get('/detaile/{id}', [ControllerSubscription::class, 'detaile'])
                ->name('detaile'); // admin.subscription.Detaile

            //  ادخال بيانات الباقة وحفظها
            Route::post('/store', [ControllerSubscription::class, 'store'])
                ->name('store'); // admin.subscription.store
            // صفحة تعديل بيانات باقة موجودة
            Route::get('/edit/{id}', [ControllerSubscription::class, 'edit'])
                ->name('edit'); // admin.subscription.edit
            // تحديث بيانات باقة وحفظها
            Route::post('/update/{id}', [ControllerSubscription::class, 'update'])
                ->name('update'); // admin.subscription.edit
            //  حذف باقة موجودة
            Route::post('/delete/{id}', [ControllerSubscription::class, 'delete'])
                ->name('delete'); // admin.subscription.delete

        });

        // اشتراكات المؤسسات
        Route::prefix('agencysubscription')->name('agencysubscription.')->group(function () {
            // عرض الاشتراكات
            Route::get('/', [AgencySubscriptionController::class, 'index'])
                ->name('index'); // admin.agency.agency_subscription.index
            //    البحث والفلترة في جدول الاشتراكات باستخدام اجاكس
            Route::post('/ajax_search_ag_sup', [AgencySubscriptionController::class, 'ajax_search_ag_sup'])
                ->name('ajax_search_ag_sup'); // admin.agencies.ajax_search

            //    عرض تفاصيل اشتراك حسب معرف محدد
            Route::get('/detaile/{id}', [AgencySubscriptionController::class, 'detaile'])
                ->name('detaile'); // admin.agency.agency_subscription.detaile

            // عرض واجهة الاضاف لاضافة اشتراك جديد
            Route::get('/create', [AgencySubscriptionController::class, 'create'])
                ->name('create'); // admin.agency.agency_subscription.create
            // ادخال بيانات الاشتراك وحفظ الاشتراك
            Route::post('/store', [AgencySubscriptionController::class, 'store'])
                ->name('store'); // admin.subscription.store
            // صفحة تعديل بيانات اشتراك موجودة
            Route::get('/edit/{id}', [AgencySubscriptionController::class, 'edit'])
                ->name('edit'); // admin.subscription.edit
            // تحديث بيانات الاشتراك وحفظها
            Route::post('/update/{id}', [AgencySubscriptionController::class, 'update'])
                ->name('update'); // admin.subscription.edit
            //  حذف اشتراك موجودة
            Route::post('/delete/{id}', [AgencySubscriptionController::class, 'delete'])
                ->name('delete'); // admin.subscription.delete

            Route::get('/subscription_entities', [AgencySubscriptionController::class, 'subscription_entitie'])
                ->name('subscription_entities'); // admin.agencies.access_history
            Route::get('/serch1', [AgencySubscriptionController::class, 'serch1'])
                ->name('serch1');
            Route::get('/serch2', [AgencySubscriptionController::class, 'serch2'])
                ->name('serch2');
        });

        // المستخدمين المسارات المتعلقة بإدارة المستخدمين تبدأ بـ /users
        Route::prefix('users')->name('users.')->group(function () {
            // عرض جميع المستخدمين
            Route::get('/', [ControlleragencyindexUsers::class, 'indexUsers'])
                ->name('index'); // admin.users.index

            // صفحة إضافة مستخدم جديد
            Route::get('/create', [UserControlleragency::class, 'create'])
                ->name('create'); // admin.users.create
        });

        Route::prefix('roles')->name('roles.')->group(function () {
            // عرض جميع الأدوار
            Route::get('/', [UserControlleragencyRole::class, 'indexRoles'])
                ->name('index'); // admin.roles.index

            // صفحة إضافة دور جديد
            Route::get('/create', [UserControlleragency::class, 'createRole'])
                ->name('create'); // admin.roles.create

        });
    });
