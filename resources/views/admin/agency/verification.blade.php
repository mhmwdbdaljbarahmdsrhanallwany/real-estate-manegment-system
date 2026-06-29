@extends('admin.layout.master')

@section('tite')
    عمليات التوثيق
@endsection

@section('contect')
@include('admin.incloude.agencynavepare')

<div class="card shadow-lg border-0 rounded-3">
  <!-- الهيدر -->
  <div class="card-header bg-light">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <h4 class="mb-0 fw-semibold text-primary">
        <i class="fas fa-check-circle me-2"></i> طلبات التوثيق
      </h4>
      <button class="btn btn-secondary btn-sm">
        <i class="fas fa-sync-alt"></i> تحديث
      </button>
    </div>
  </div>

  <!-- الجسم -->
  <div class="card-body">
    <!-- الفلاتر -->
    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <div class="input-group shadow-sm">
          <span class="input-group-text bg-white border-0"><i class="fas fa-search"></i></span>
          <input type="text" id="searchVerificationBox" class="form-control" placeholder="بحث باسم المؤسسة أو السجل التجاري...">
        </div>
      </div>
      <div class="col-md-4">
        <select id="verificationStatusFilter" class="form-select shadow-sm">
          <option value="all">كل الحالات</option>
          <option value="pending">قيد المراجعة</option>
          <option value="verified">موثق</option>
          <option value="rejected">مرفوض</option>
        </select>
      </div>
      <div class="col-md-4">
        <select id="cityFilter" class="form-select shadow-sm">
          <option value="all">كل المدن</option>
          <option value="riyadh">الرياض</option>
          <option value="jeddah">جدة</option>
          <option value="dammam">الدمام</option>
        </select>
      </div>
    </div>

    <!-- جدول الطلبات -->
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>المعرف</th>
            <th>اسم المؤسسة</th>
            <th>السجل التجاري</th>
            <th>المدينة</th>
            <th>حالة التوثيق</th>
            <th>تاريخ الطلب</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>201</td>
            <td>مؤسسة العقار الحديثة</td>
            <td>123456789</td>
            <td>الرياض</td>
            <td><span class="badge bg-warning text-dark">قيد المراجعة</span></td>
            <td>2026-04-10</td>
            <td>
              <div class="btn-group">
                <button class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></button>
                <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
              </div>
            </td>
          </tr>
          <tr>
            <td>202</td>
            <td>شركة الوساطة العقارية</td>
            <td>987654321</td>
            <td>جدة</td>
            <td><span class="badge bg-success">موثق</span></td>
            <td>2026-04-12</td>
            <td>
              <div class="btn-group">
                <button class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></button>
                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
              </div>
            </td>
          </tr>
          <!-- باقي الطلبات -->
        </tbody>
      </table>
    </div>

    <!-- أزرار التقسيم -->
    <div class="d-flex justify-content-between align-items-center mt-3">
      <div id="verificationInfoBox">إظهار 1 إلى 10 من أصل 25 طلب</div>
      <div>
        <button class="btn btn-secondary btn-sm">السابق</button>
        <button class="btn btn-secondary btn-sm">التالي</button>
      </div>
    </div>
  </div>
</div>
@endsection
