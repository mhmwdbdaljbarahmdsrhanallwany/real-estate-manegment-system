@extends('admin.layout.master')

@section('title')
    إضافة مستخدم جديد
@endsection

@section('contect')

<div class="card shadow-lg border-0 rounded-4 overflow-hidden">
  <!-- الهيدر -->
  <div class="card-header bg-gradient bg-light d-flex align-items-center">
    <h4 class="mb-0 fw-bold text-primary">
      <i class="fas fa-users me-2"></i> إدارة المستخدمين
    </h4>
  </div>

  <!-- الجسم -->
  <div class="card-body bg-white">
    <form class="row g-3">
      <!-- المؤسسة -->
      <div class="col-md-6">
        <label for="agency_id" class="form-label fw-semibold">
          <i class="fas fa-building text-primary me-1"></i> المؤسسة
        </label>
        <select id="agency_id" class="form-select shadow-sm">
          <option value="">اختر المؤسسة</option>
        </select>

        
      </div>

      <!-- الفرع -->
      <div class="col-md-6">
        <label for="branch_id" class="form-label fw-semibold">
          <i class="fas fa-code-branch text-success me-1"></i> الفرع
        </label>
        <select id="branch_id" class="form-select shadow-sm">
          <option value="">اختر الفرع</option>
        </select>
      </div>

      <!-- اسم المستخدم -->
      <div class="col-md-6">
        <label for="username" class="form-label fw-semibold">
          <i class="fas fa-user text-info me-1"></i> اسم المستخدم
        </label>
        <input type="text" id="username" class="form-control shadow-sm" placeholder="اسم المستخدم للدخول">
      </div>

      <!-- الاسم الكامل -->
      <div class="col-md-6">
        <label for="name" class="form-label fw-semibold">
          <i class="fas fa-id-card text-warning me-1"></i> الاسم الكامل
        </label>
        <input type="text" id="name" class="form-control shadow-sm" placeholder="الاسم الكامل">
      </div>

      <!-- البريد الإلكتروني -->
      <div class="col-md-6">
        <label for="email" class="form-label fw-semibold">
          <i class="fas fa-envelope text-danger me-1"></i> البريد الإلكتروني
        </label>
        <input type="email" id="email" class="form-control shadow-sm" placeholder="example@mail.com">
      </div>

      <!-- رقم الجوال -->
      <div class="col-md-6">
        <label for="phone_number" class="form-label fw-semibold">
          <i class="fas fa-phone text-success me-1"></i> رقم الجوال
        </label>
        <input type="text" id="phone_number" class="form-control shadow-sm" placeholder="05XXXXXXXX">
      </div>

      <!-- كلمة المرور -->
      <div class="col-md-6">
        <label for="password" class="form-label fw-semibold">
          <i class="fas fa-lock text-secondary me-1"></i> كلمة المرور
        </label>
        <input type="password" id="password" class="form-control shadow-sm" placeholder="••••••••">
      </div>

      <!-- الدور -->
      <div class="col-md-6">
        <label for="role" class="form-label fw-semibold">
          <i class="fas fa-user-shield text-primary me-1"></i> الدور
        </label>
        <select id="role" class="form-select shadow-sm">
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
        <label for="profile_image" class="form-label fw-semibold">
          <i class="fas fa-image text-info me-1"></i> صورة الملف الشخصي
        </label>
        <input type="file" id="profile_image" class="form-control shadow-sm">
      </div>

      <!-- اللغة -->
      <div class="col-md-3">
        <label for="language" class="form-label fw-semibold">
          <i class="fas fa-language text-success me-1"></i> اللغة
        </label>
        <select id="language" class="form-select shadow-sm">
          <option value="ar">العربية</option>
          <option value="en">English</option>
        </select>
      </div>

      <!-- الثيم -->
      <div class="col-md-3">
        <label for="theme" class="form-label fw-semibold">
          <i class="fas fa-paint-brush text-warning me-1"></i> الثيم
        </label>
        <select id="theme" class="form-select shadow-sm">
          <option value="light">فاتح</option>
          <option value="dark">داكن</option>
        </select>
      </div>

      <!-- الإشعارات -->
      <div class="col-md-3 d-flex align-items-center">
        <div class="form-check mt-4">
          <input class="form-check-input" type="checkbox" id="notifications_enabled" checked>
          <label class="form-check-label fw-semibold" for="notifications_enabled">
            <i class="fas fa-bell text-danger me-1"></i> تفعيل الإشعارات
          </label>
        </div>
      </div>

      <!-- التحقق بخطوتين -->
      <div class="col-md-3 d-flex align-items-center">
        <div class="form-check mt-4">
          <input class="form-check-input" type="checkbox" id="two_factor_enabled">
          <label class="form-check-label fw-semibold" for="two_factor_enabled">
            <i class="fas fa-shield-alt text-primary me-1"></i> التحقق بخطوتين
          </label>
        </div>
      </div>

      <!-- زر الحفظ -->
      <div class="col-12 text-end">
        <button type="submit" class="btn btn-gradient btn-primary px-4 py-2 shadow-sm rounded-pill">
          <i class="fas fa-save me-1"></i> حفظ المستخدم
        </button>
      </div>
    </form>
  </div>
</div>

    
@endsection