@extends('admin.layout.master')

@section('title', 'تفاصيل الباقة')

@section('contect')
@include('admin.incloude.agencynavepare')

<div class="container-fluid py-4">

  <!-- معلومات أساسية -->
  <div class="row mb-4">
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <i class="fas fa-box fa-2x text-primary mb-2"></i>
          <h6>اسم الباقة</h6>
          <h4>{{ $plan->plan_name }}</h4>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <i class="fas fa-layer-group fa-2x text-success mb-2"></i>
          <h6>مستوى الباقة</h6>
          <h4>{{ $plan->plan_tier_label }}</h4>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <i class="fas fa-money-bill fa-2x text-warning mb-2"></i>
          <h6>السعر</h6>
          <h4>{{ $plan->plan_price }} {{ $plan->currency_label }}</h4>
        </div>
      </div>
    </div>
  </div>

  <!-- تفاصيل الباقة -->
  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card shadow-lg rounded-4 border-0 h-100">
        <div class="card-header bg-gradient-primary text-white fw-bold">
          <i class="fas fa-info-circle me-2"></i> تفاصيل الباقة
        </div>
        <div class="card-body">
          <p><strong>المدة:</strong> {{ $plan->duration }} يوم</p>
          <p><strong>نوع الباقة:</strong> {{ $plan->subscription_type_label }}</p>
          <p>
            <strong>الفترة التجريبية:</strong>
            <span class="{{ $plan->trial_period_value > 0 ? 'text-success fw-bold' : 'text-muted' }}">
              <i class="{{ $plan->trial_period_value > 0 ? 'fas fa-check-circle text-success' : 'fas fa-times-circle text-muted' }}"></i>
              {{ $plan->trial_period_value }} {{ $plan->trial_period_unit_label }}
            </span>
          </p>
          <p>
            <strong>غرامة التأخير:</strong>
            <span class="{{ $plan->late_fee > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
              <i class="{{ $plan->late_fee > 0 ? 'fas fa-exclamation-triangle text-danger' : 'fas fa-minus-circle text-muted' }}"></i>
              {{ $plan->late_fee }}
            </span>
          </p>
          <p>
            <strong>فترة السماح:</strong>
            <span class="{{ $plan->grace_period > 0 ? 'text-info fw-bold' : 'text-muted' }}">
              <i class="{{ $plan->grace_period > 0 ? 'fas fa-clock text-info' : 'fas fa-minus-circle text-muted' }}"></i>
              {{ $plan->grace_period }} يوم
            </span>
          </p>
          <p>
            <strong>الحالة:</strong>
            <span class="badge 
              @if($plan->status == 'active') bg-success 
              @elseif($plan->status == 'suspended') bg-warning 
              @elseif($plan->status == 'expired') bg-danger 
              @else bg-secondary @endif">
              <i class="
                @if($plan->status == 'active') fas fa-check-circle 
                @elseif($plan->status == 'suspended') fas fa-pause-circle 
                @elseif($plan->status == 'expired') fas fa-times-circle 
                @else fas fa-question-circle @endif"></i>
              {{ $plan->status_label }}
            </span>
          </p>
        </div>
      </div>
    </div>

    <!-- الإعدادات والقيود -->
    <div class="col-lg-6">
      <div class="card shadow-lg rounded-4 border-0 h-100">
        <div class="card-header bg-gradient-success text-white fw-bold">
          <i class="fas fa-cogs me-2"></i> الإعدادات والقيود
        </div>
        <div class="card-body">
          <p><strong>عدد المستخدمين:</strong> <i class="fas fa-users text-primary"></i> {{ $plan->max_users }}</p>
          <p><strong>عدد الفروع:</strong> <i class="fas fa-code-branch text-success"></i> {{ $plan->max_branches }}</p>
          <p><strong>مساحة التخزين:</strong> <i class="fas fa-hdd text-info"></i> {{ $plan->storage_space }} GB</p>
          <p><strong>الميزات:</strong> <i class="fas fa-star text-warning"></i> {{ $plan->features }}</p>
          <p><strong>نسبة الخصم:</strong> <i class="fas fa-percentage text-danger"></i> {{ $plan->discount_rate }}%</p>
          <p><strong>قيمة الخصم:</strong> <i class="fas fa-tag text-success"></i> {{ $plan->discount_amount }}</p>
          <p><strong>نسبة الضريبة:</strong> <i class="fas fa-receipt text-secondary"></i> {{ $plan->tax_rate }}%</p>
        </div>
      </div>
    </div>
  </div>

  <!-- جدول التواريخ -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-dark text-white fw-bold">
          <i class="fas fa-history me-2"></i> سجل التواريخ
        </div>
        <div class="card-body">
          <table class="table table-bordered table-striped">
            <thead class="table-light">
              <tr>
                <th>تاريخ الإنشاء</th>
                <th>آخر تعديل</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><i class="fas fa-calendar-plus text-success"></i> {{ $plan->created_at }}</td>
                <td><i class="fas fa-calendar-check text-info"></i> {{ $plan->updated_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
