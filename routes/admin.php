<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\HomeController;

// ==================== المجموعة العامة (بدون مصادقة) ====================
// تجميع جميع مسارات الموقع العامة التي لا تحتاج تسجيل دخول
Route::group([
    'as' => 'admin.', // بادئة لأسماء المسارات (مثال: front.home, front.about.index)
], function () {

    // ==================== الصفحة الرئيسية ====================
    // المسار الرئيسي للموقع (الصفحة الافتتاحية)
    Route::get('/', [HomeController::class, 'index']) // طلب GET للصفحة الرئيسية
        ->name('home'); // اسم المسار: admin.home - يستخدم في الروابط مثل route('admin.home')

    // ==================== صفحة من نحن ====================
    // جميع المسارات المتعلقة بصفحة "من نحن" تبدأ بـ /about
    Route::prefix('about')->name('about.')->group(function () {

        // Route::get('/aboutUS', [AboutController::class, 'index'])
        //     ->name('index');

        // صفحة الرؤية - /about/vision
        // Route::get('/vision', [AboutController::class, 'vision']) // طلب GET لصفحة الرؤية
        //     ->name('aboutUS.about'); // اسم المسار: front.about.vision - عرض رؤية المستشفى

        // صفحة الرسالة - /about/mission
        // Route::get('/mission', [AboutController::class, 'mission']) // طلب GET لصفحة الرسالة
        //     ->name('mission'); // اسم المسار: front.about.mission - عرض رسالة المستشفى

        // صفحة التاريخ - /about/history
        // Route::get('/history', [AboutController::class, 'history']) // طلب GET لصفحة التاريخ
        //     ->name('history'); // اسم المسار: front.about.history - عرض تاريخ المستشفى

        // صفحة الشهادات والاعتمادات - /about/certificates
        // Route::get('/certificates', [AboutController::class, 'certificates']) // طلب GET لصفحة الشهادات
        //     ->name('certificates'); // اسم المسار: front.about.certificates - عرض شهادات المستشفى
    });

    // ==================== الأقسام الطبية ====================
    // جميع المسارات المتعلقة بالأقسام الطبية تبدأ بـ /departments
    Route::prefix('departments')->name('departments.')->group(function () {

        // // ==================== الأقسام الطبية (التشخيصية) ====================
        // // المسارات تبدأ بـ /departments/medical
        // Route::prefix('medical')->name('medical.')->group(function () {

        //     Route::get('cardiology', [CardiologyController::class, 'index'])
        //         ->name('cardiology');
        // });

        // // الاقسام الجراحيه
        // Route::prefix('Surgical')->name('Surgical.')->group(function () {
        //     Route::get('Othopedic', [OrthopedicController::class, 'index'])->name('page_Othopedic');
        // });

        // Route::prefix('Support')->name('Support.')->group(function () {
        //     Route::get('Laboratory', [LaboratoryController::class, 'index'])->name('page_Laboratory');

        // });

    });

   
}); 


// ملف Sitemap XML لمحركات البحث - /sitemap.xml
Route::get('/sitemap.xml', function () { // طلب GET لملف sitemap.xml
    return response()->file(public_path('sitemap.xml')); // إرجاع الملف من المجلد public
})->name('sitemap.xml'); // اسم المسار: sitemap.xml

// ملف robots.txt لمحركات البحث - /robots.txt
Route::get('/robots.txt', function () { // طلب GET لملف robots.txt
    return response()->file(public_path('robots.txt')); // إرجاع الملف من المجلد public
})->name('robots.txt'); // اسم المسار: robots.txt

// ==================== مسارات المطور (فقط في البيئة المحلية) ====================
// التحقق مما إذا كان التطبيق يعمل في البيئة المحلية (development)
if (app()->environment('local')) { // فقط إذا كانت البيئة local
    // جميع مسارات المطور تبدأ بـ /dev
    Route::prefix('dev')->name('dev.')->group(function () {
        // عرض جميع مسارات التطبيق - /dev/routes
        Route::get('/routes', function () { // طلب GET لعرض جميع المسارات
            // جلب جميع المسارات المسجلة في التطبيق
            $routes = collect(Route::getRoutes())->map(function ($route) {
                return [ // تحويل كل مسار إلى مصفوفة لعرضها
                    'uri' => $route->uri(), // رابط المسار
                    'name' => $route->getName(), // اسم المسار
                    'methods' => $route->methods(), // طرق HTTP المسموحة
                    'middleware' => $route->middleware(), // middleware المطبقة
                ];
            });

            return view('dev.routes', compact('routes')); // عرض صفحة المسارات
        })->name('routes'); // اسم المسار: dev.routes - صفحة عرض جميع المسارات

        // صفحة تجريبية لإرسال البريد - /dev/test-email
        Route::get('/test-email', function () { // طلب GET لصفحة تجربة البريد
            return view('dev.test-email'); // عرض نموذج تجربة البريد
        })->name('test-email'); // اسم المسار: dev.test-email - صفحة تجربة البريد
    });
}

// ==================== مسار 404 مخصص ====================
// مسار fallback يتم استدعاؤه عندما لا يجد Laravel أي مسار مطابق
// Route::fallback(function () { // دالة يتم تنفيذها عندما لا يجد النظام مساراً مطابقاً
//     return view('front.errors.404'); // عرض صفحة خطأ 404 المخصصة
// })->name('front.404'); // اسم المسار: front.404 - صفحة خطأ 404
