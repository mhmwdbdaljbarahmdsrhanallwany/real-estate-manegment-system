@extends('admin.layout.master')

@section('title')
المستخدمين
@endsection

@section('homepageurl')
المستخدمين
    
@endsection

@section('urlpagebranch')
  جميع المستخدمين
@endsection
@section('contect')
{{-- 
<div class="card shadow-lg border-0 rounded-3">
  <div class="card-header bg-light">
    <h4 class="mb-0 fw-semibold text-primary">
      <i class="fas fa-users me-2"></i> إدارة المستخدمين
    </h4>
  </div>
  <div class="card-body">
    <form class="row g-3">
      <!-- معرف المؤسسة -->
      <div class="col-md-6">
        <label for="agency_id" class="form-label">المؤسسة</label>
        <select id="agency_id" class="form-select">
          <option value="">اختر المؤسسة</option>
          <!-- خيارات المؤسسات -->
        </select>
      </div>

      <!-- معرف الفرع -->
      <div class="col-md-6">
        <label for="branch_id" class="form-label">الفرع</label>
        <select id="branch_id" class="form-select">
          <option value="">اختر الفرع</option>
          <!-- خيارات الفروع -->
        </select>
      </div>

      <!-- اسم المستخدم -->
      <div class="col-md-6">
        <label for="username" class="form-label">اسم المستخدم</label>
        <input type="text" id="username" class="form-control" placeholder="اسم المستخدم للدخول">
      </div>

      <!-- الاسم الكامل -->
      <div class="col-md-6">
        <label for="name" class="form-label">الاسم الكامل</label>
        <input type="text" id="name" class="form-control" placeholder="الاسم الكامل">
      </div>

      <!-- البريد الإلكتروني -->
      <div class="col-md-6">
        <label for="email" class="form-label">البريد الإلكتروني</label>
        <input type="email" id="email" class="form-control" placeholder="example@mail.com">
      </div>

      <!-- رقم الجوال -->
      <div class="col-md-6">
        <label for="phone_number" class="form-label">رقم الجوال</label>
        <input type="text" id="phone_number" class="form-control" placeholder="05XXXXXXXX">
      </div>

      <!-- كلمة المرور -->
      <div class="col-md-6">
        <label for="password" class="form-label">كلمة المرور</label>
        <input type="password" id="password" class="form-control" placeholder="••••••••">
      </div>

      <!-- الدور -->
      <div class="col-md-6">
        <label for="role" class="form-label">الدور</label>
        <select id="role" class="form-select">
          <option value="super_admin">مدير عام</option>
          <option value="agency_admin">مدير مؤسسة</option>
          <option value="branch_manager">مدير فرع</option>
          <option value="staff">موظف</option>
          <option value="accountant">محاسب</option>
          <option value="inspector">مفتش</option>
          <option value="maintenance">صيانة</option>
        </select>
      </div>

      <!-- صورة الملف الشخصي -->
      <div class="col-md-6">
        <label for="profile_image" class="form-label">صورة الملف الشخصي</label>
        <input type="file" id="profile_image" class="form-control">
      </div>

      <!-- اللغة المفضلة -->
      <div class="col-md-3">
        <label for="language" class="form-label">اللغة</label>
        <select id="language" class="form-select">
          <option value="ar">العربية</option>
          <option value="en">English</option>
        </select>
      </div>

      <!-- الثيم -->
      <div class="col-md-3">
        <label for="theme" class="form-label">الثيم</label>
        <select id="theme" class="form-select">
          <option value="light">فاتح</option>
          <option value="dark">داكن</option>
        </select>
      </div>

      <!-- تفعيل الإشعارات -->
      <div class="col-md-3 d-flex align-items-center">
        <div class="form-check mt-4">
          <input class="form-check-input" type="checkbox" id="notifications_enabled" checked>
          <label class="form-check-label" for="notifications_enabled">تفعيل الإشعارات</label>
        </div>
      </div>

      <!-- التحقق بخطوتين -->
      <div class="col-md-3 d-flex align-items-center">
        <div class="form-check mt-4">
          <input class="form-check-input" type="checkbox" id="two_factor_enabled">
          <label class="form-check-label" for="two_factor_enabled">تفعيل التحقق بخطوتين</label>
        </div>
      </div>

      <!-- زر الحفظ -->
      <div class="col-12 text-end">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> حفظ المستخدم
        </button>
      </div>
    </form>
  </div>
</div> --}}



<div class="card shadow-lg border-0 rounded-3">
  <div class="card-header bg-light">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <h4 class="mb-0 fw-semibold text-primary">
        <i class="fas fa-users me-2"></i> قائمة المستخدمين
      </h4>
      <a href="#" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> إضافة مستخدم جديد
      </a>
    </div>
  </div>

  <div class="card-body">
    <!-- الفلاتر -->
    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text bg-white border-0"><i class="fas fa-search"></i></span>
          <input type="text" id="searchUserBox" class="form-control" placeholder="بحث بالاسم أو البريد...">
        </div>
      </div>
      <div class="col-md-3">
        <select id="roleFilter" class="form-select">
          <option value="all">كل الأدوار</option>
          <option value="super_admin">مدير عام</option>
          <option value="agency_admin">مدير مؤسسة</option>
          <option value="branch_manager">مدير فرع</option>
          <option value="staff">موظف</option>
          <option value="accountant">محاسب</option>
          <option value="inspector">مفتش</option>
          <option value="maintenance">صيانة</option>
        </select>
      </div>
      <div class="col-md-3">
        <select id="statusFilter" class="form-select">
          <option value="all">كل الحالات</option>
          <option value="active">نشط</option>
          <option value="inactive">غير نشط</option>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-secondary w-100"><i class="fas fa-sync-alt"></i> تحديث</button>
      </div>
    </div>

    <!-- جدول المستخدمين -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>المعرف</th>
            <th>الاسم الكامل</th>
            <th>اسم المستخدم</th>
            <th>البريد الإلكتروني</th>
            <th>رقم الجوال</th>
            <th>الدور</th>
            <th>الحالة</th>
            <th>آخر دخول</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>أحمد محمد</td>
            <td>ahmed123</td>
            <td>ahmed@mail.com</td>
            <td>0501234567</td>
            <td><span class="badge bg-success">موظف</span></td>
            <td><span class="badge bg-primary">نشط</span></td>
            <td>2026-04-14 22:30</td>
            <td>
              <div class="btn-group">
                <button class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></button>
                <button class="btn btn-sm btn-warning text-dark"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
              </div>
            </td>
          </tr>
          <!-- باقي المستخدمين -->
        </tbody>
      </table>
    </div>

    <!-- أزرار التقسيم -->
    <div class="d-flex justify-content-between align-items-center mt-3">
      <div id="userInfoBox">إظهار 1 إلى 10 من أصل 50 مستخدم</div>
      <div>
        <button class="btn btn-secondary btn-sm">السابق</button>
        <button class="btn btn-secondary btn-sm">التالي</button>
      </div>
    </div>
  </div>
</div>

@endsection