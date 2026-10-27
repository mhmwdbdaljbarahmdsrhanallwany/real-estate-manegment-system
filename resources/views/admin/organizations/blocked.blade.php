@extends('admin.layout.master')

@section('title')
    المؤسسات المحضورة
@endsection

@section('homepageurl')
    المؤسسات العقارية
@endsection
@section('urlpagebranch')
المؤسسات المحضورة
    
@endsection
@section('contect')
@include('admin.incloude.agencynavepare')

<div class="card shadow-lg border-0 rounded-3 mt-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
      <h4 class="mb-0 fw-semibold text-primary">
        <i class="fas fa-history me-2"></i> سجل عمليات المؤسسات
      </h4>
      <button class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i> تحديث</button>
    </div>
  
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle text-center">
          <thead class="table-primary">
            <tr>
              <th>المعرف</th>
              <th>المؤسسة</th>
              <th>الإجراء</th>
              <th>الحالة القديمة</th>
              <th>الحالة الجديدة</th>
              <th>السبب</th>
              <th>منفذ الإجراء</th>
              <th>عنوان IP</th>
              <th>الجهاز/المتصفح</th>
              <th>تاريخ الإجراء</th>
              <th>الإجراءات</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>501</td>
              <td>مؤسسة العقار الحديثة</td>
              <td><span class="badge bg-info">activated</span></td>
              <td>pending</td>
              <td>active</td>
              <td>تم التفعيل بعد التحقق</td>
              <td>المشرف العام</td>
              <td>192.168.1.10</td>
              <td>Chrome / Windows</td>
              <td>2026-04-10</td>
              <td>
                <div class="btn-group">
                  <button class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></button>
                  <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </div>
              </td>
            </tr>
            <!-- باقي السجل -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection