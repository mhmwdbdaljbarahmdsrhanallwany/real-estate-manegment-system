@extends('admin.layout.master')

@section('title')
    إضافة دور جديد
@endsection

@section('content')

  
    
    <script src="https://cdn.tailwindcss.com"></script>
 
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar (نفس السابق) -->
        <aside class="w-72 bg-white shadow-2xl overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-building text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">نظام العقارات</h1>
                        <p class="text-xs text-gray-400">الإدارة المتكاملة</p>
                    </div>
                </div>
            </div>
            
            <nav class="p-4">
                <div class="space-y-1">
                    <p class="text-xs text-gray-400 uppercase tracking-wider px-4 py-2">الرئيسية</p>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600"><i class="fas fa-tachometer-alt w-5"></i><span>لوحة التحكم</span></a>
                    
                    <p class="text-xs text-gray-400 uppercase tracking-wider px-4 py-2 mt-4">إدارة المستخدمين</p>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600"><i class="fas fa-users w-5"></i><span>جميع المستخدمين</span></a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600"><i class="fas fa-user-plus w-5"></i><span>إضافة مستخدم جديد</span></a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-blue-600 bg-blue-50 rounded-xl shadow-sm"><i class="fas fa-user-tag w-5"></i><span>الأدوار والصلاحيات</span></a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600"><i class="fas fa-lock w-5"></i><span>إدارة الصلاحيات</span></a>
                    
                    <p class="text-xs text-gray-400 uppercase tracking-wider px-4 py-2 mt-4">الأمان والتقارير</p>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600"><i class="fas fa-laptop w-5"></i><span>الجلسات النشطة</span></a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600"><i class="fas fa-history w-5"></i><span>سجل التدقيق</span></a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600"><i class="fas fa-ban w-5"></i><span>التوكنات المحظورة</span></a>
                </div>
            </nav>
        </aside>

        <!-- المحتوى الرئيسي -->
        <main class="flex-1 overflow-y-auto">
            
            <header class="bg-white shadow-sm sticky top-0 z-20">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div class="relative">
                        <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="بحث..." class="pr-10 pl-4 py-2 border border-gray-200 rounded-xl w-80 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="relative p-2 hover:bg-gray-100 rounded-xl"><i class="fas fa-bell text-gray-500"></i><span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span></button>
                        <div class="flex items-center gap-3">
                            <div class="text-right"><p class="text-sm font-semibold text-gray-700">أحمد محمد</p><p class="text-xs text-gray-400">مدير النظام</p></div>
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold shadow-md">أ</div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-8 animate-fade-in-up">
                
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">الأدوار والصلاحيات</h1>
                        <p class="text-gray-500 mt-1">إدارة أدوار المستخدمين وتحديد صلاحياتهم</p>
                    </div>
                    <button class="px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>إضافة دور جديد</span>
                    </button>
                </div>

                <!-- بطاقات الأدوار -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    
                    <!-- دور: مدير النظام -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden role-card">
                        <div class="p-6 border-b border-gray-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="flex justify-between items-start">
                                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-crown text-white text-2xl"></i>
                                </div>
                                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-xs backdrop-blur-sm">نظامي</span>
                            </div>
                            <h3 class="text-white text-xl font-bold mt-4">مدير النظام</h3>
                            <p class="text-white/70 text-sm mt-1">صلاحيات كاملة على النظام</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">عدد المستخدمين</span>
                                    <span class="font-semibold text-gray-800">1</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">عدد الصلاحيات</span>
                                    <span class="font-semibold text-gray-800">24</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">تاريخ الإنشاء</span>
                                    <span class="font-semibold text-gray-800">2024-01-01</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 px-3 py-2 text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition text-sm"><i class="fas fa-edit ml-1"></i>تعديل</button>
                                <button class="flex-1 px-3 py-2 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm"><i class="fas fa-copy ml-1"></i>نسخ</button>
                            </div>
                        </div>
                    </div>

                    <!-- دور: مدير مؤسسة -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden role-card">
                        <div class="p-6 border-b border-gray-100" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);">
                            <div class="flex justify-between items-start">
                                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-building text-white text-2xl"></i>
                                </div>
                                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-xs backdrop-blur-sm">نظامي</span>
                            </div>
                            <h3 class="text-white text-xl font-bold mt-4">مدير مؤسسة</h3>
                            <p class="text-white/70 text-sm mt-1">إدارة المؤسسة وفروعها</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-sm"><span class="text-gray-500">عدد المستخدمين</span><span class="font-semibold text-gray-800">5</span></div>
                                <div class="flex justify-between text-sm"><span class="text-gray-500">عدد الصلاحيات</span><span class="font-semibold text-gray-800">18</span></div>
                                <div class="flex justify-between text-sm"><span class="text-gray-500">تاريخ الإنشاء</span><span class="font-semibold text-gray-800">2024-01-01</span></div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 px-3 py-2 text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition text-sm"><i class="fas fa-edit ml-1"></i>تعديل</button>
                                <button class="flex-1 px-3 py-2 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm"><i class="fas fa-copy ml-1"></i>نسخ</button>
                            </div>
                        </div>
                    </div>

                    <!-- دور: محاسب -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden role-card">
                        <div class="p-6 border-b border-gray-100" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                            <div class="flex justify-between items-start">
                                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-chart-line text-white text-2xl"></i>
                                </div>
                                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-xs backdrop-blur-sm">نظامي</span>
                            </div>
                            <h3 class="text-white text-xl font-bold mt-4">محاسب</h3>
                            <p class="text-white/70 text-sm mt-1">إدارة الفواتير والمدفوعات</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-sm"><span class="text-gray-500">عدد المستخدمين</span><span class="font-semibold text-gray-800">3</span></div>
                                <div class="flex justify-between text-sm"><span class="text-gray-500">عدد الصلاحيات</span><span class="font-semibold text-gray-800">12</span></div>
                                <div class="flex justify-between text-sm"><span class="text-gray-500">تاريخ الإنشاء</span><span class="font-semibold text-gray-800">2024-01-01</span></div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 px-3 py-2 text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition text-sm"><i class="fas fa-edit ml-1"></i>تعديل</button>
                                <button class="flex-1 px-3 py-2 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm"><i class="fas fa-copy ml-1"></i>نسخ</button>
                            </div>
                        </div>
                    </div>

                    <!-- دور: محصل -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden role-card">
                        <div class="p-6 border-b border-gray-100" style="background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);">
                            <div class="flex justify-between items-start">
                                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas fa-hand-holding-usd text-white text-2xl"></i>
                                </div>
                                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-xs backdrop-blur-sm">مخصص</span>
                            </div>
                            <h3 class="text-white text-xl font-bold mt-4">محصل</h3>
                            <p class="text-white/70 text-sm mt-1">تحصيل المدفوعات من المستأجرين</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-sm"><span class="text-gray-500">عدد المستخدمين</span><span class="font-semibold text-gray-800">4</span></div>
                                <div class="flex justify-between text-sm"><span class="text-gray-500">عدد الصلاحيات</span><span class="font-semibold text-gray-800">6</span></div>
                                <div class="flex justify-between text-sm"><span class="text-gray-500">تاريخ الإنشاء</span><span class="font-semibold text-gray-800">2024-01-15</span></div>
                            </div>
                            <div class="flex gap-2">
                                <button class="flex-1 px-3 py-2 text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition text-sm"><i class="fas fa-edit ml-1"></i>تعديل</button>
                                <button class="flex-1 px-3 py-2 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm"><i class="fas fa-copy ml-1"></i>نسخ</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- جدول الصلاحيات التفصيلي -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-bold text-gray-800">الصلاحيات التفصيلية</h3>
                        <p class="text-sm text-gray-500">عرض جميع الصلاحيات والأدوار المرتبطة بها</p>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوحدة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصلاحية</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الأدوار</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="table-row-hover">
                                    <td class="px-6 py-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm">العقود</span></td>
                                    <td class="px-6 py-4 font-medium text-gray-800">عرض العقود</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">صلاحية عرض جميع العقود</td>
                                    <td class="px-6 py-4"><div class="flex flex-wrap gap-1"><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">مدير نظام</span><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">مدير مؤسسة</span><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">مدير فرع</span></div></td>
                                </tr>
                                <tr class="table-row-hover">
                                    <td class="px-6 py-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm">العقود</span></td>
                                    <td class="px-6 py-4 font-medium text-gray-800">إنشاء عقود</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">صلاحية إنشاء عقود جديدة</td>
                                    <td class="px-6 py-4"><div class="flex flex-wrap gap-1"><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">مدير نظام</span><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">مدير مؤسسة</span></div></td>
                                </tr>
                                <tr class="table-row-hover">
                                    <td class="px-6 py-4"><span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm">المدفوعات</span></td>
                                    <td class="px-6 py-4 font-medium text-gray-800">تسجيل مدفوعات</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">صلاحية تسجيل مدفوعات جديدة</td>
                                    <td class="px-6 py-4"><div class="flex flex-wrap gap-1"><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">محاسب</span><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">محصل</span><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">مدير مؤسسة</span></div></td>
                                </tr>
                                <tr class="table-row-hover">
                                    <td class="px-6 py-4"><span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-sm">المستخدمين</span></td>
                                    <td class="px-6 py-4 font-medium text-gray-800">إدارة المستخدمين</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">إضافة وتعديل وحذف المستخدمين</td>
                                    <td class="px-6 py-4"><div class="flex flex-wrap gap-1"><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">مدير نظام</span><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">مدير مؤسسة</span></div></td>
                                </tr>
                                <tr class="table-row-hover">
                                    <td class="px-6 py-4"><span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-sm">التقارير</span></td>
                                    <td class="px-6 py-4 font-medium text-gray-800">تصدير التقارير</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">تصدير التقارير بصيغ مختلفة (PDF, Excel)</td>
                                    <td class="px-6 py-4"><div class="flex flex-wrap gap-1"><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">محاسب</span><span class="px-2 py-0.5 bg-gray-100 rounded text-xs">مدير مؤسسة</span></div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <style>
        .table-row-hover { transition: all 0.2s ease; }
        .table-row-hover:hover { background-color: #f9fafb; }
    </style>

@endsection