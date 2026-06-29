@extends('admin.layout.master')

@section('title', 'تفاصيل اشتراك المؤسسة')

@section('contect')
@include('admin.incloude.agencynavepare')

<div class="container-fluid py-4">

  <!-- معلومات أساسية -->
  <div class="row mb-4">
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <i class="fas fa-id-badge fa-2x text-primary mb-2"></i>
          <h6>رقم الاشتراك</h6>
          <h4>{{ $subscription->AgencySubscriptionID }}</h4>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <i class="fas fa-building fa-2x text-success mb-2"></i>
          <h6>المؤسسة</h6>
          <h4>{{ $subscription->agency->agency_name ?? '---' }}</h4>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <i class="fas fa-box fa-2x text-warning mb-2"></i>
          <h6>الباقة</h6>
          <h4>{{ $subscription->plan->plan_name ?? '---' }}</h4>
        </div>
      </div>
    </div>
  </div>

  <!-- تفاصيل الاشتراك -->
  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card shadow-lg rounded-4 border-0 h-100">
        <div class="card-header bg-gradient-primary text-white fw-bold">
          <i class="fas fa-info-circle me-2"></i> تفاصيل الاشتراك
        </div>
        <div class="card-body">
          <p><strong>نوع الاشتراك:</strong> {{ $subscription->SubscriptionType }}</p>
          <p><strong>تاريخ البداية:</strong> <i class="fas fa-calendar-plus text-success"></i> {{ $subscription->StartDate }}</p>
          <p><strong>تاريخ الانتهاء:</strong> <i class="fas fa-calendar-times text-danger"></i> {{ $subscription->EndDate }}</p>
          <p><strong>تاريخ التجديد:</strong> <i class="fas fa-sync-alt text-info"></i> {{ $subscription->RenewalDate }}</p>
          <p>
            <strong>الفترة التجريبية:</strong>
            <span class="{{ $subscription->TrialPeriod > 0 ? 'text-success fw-bold' : 'text-muted' }}">
              <i class="{{ $subscription->TrialPeriod > 0 ? 'fas fa-check-circle text-success' : 'fas fa-times-circle text-muted' }}"></i>
              {{ $subscription->TrialPeriod }} يوم
            </span>
          </p>
          <p>
            <strong>الحالة:</strong>
            <span class="badge 
              @if($subscription->PlanStatus == 'active') bg-success 
              @elseif($subscription->PlanStatus == 'suspended') bg-warning 
              @elseif($subscription->PlanStatus == 'expired') bg-danger 
              @else bg-secondary @endif">
              <i class="
                @if($subscription->PlanStatus == 'active') fas fa-check-circle 
                @elseif($subscription->PlanStatus == 'suspended') fas fa-pause-circle 
                @elseif($subscription->PlanStatus == 'expired') fas fa-times-circle 
                @else fas fa-question-circle @endif"></i>
              {{ $subscription->PlanStatus }}
            </span>
          </p>
        </div>
      </div>
    </div>

    <!-- المالية والرسوم -->
    <div class="col-lg-6">
      <div class="card shadow-lg rounded-4 border-0 h-100">
        <div class="card-header bg-gradient-success text-white fw-bold">
          <i class="fas fa-money-check-alt me-2"></i> المالية والرسوم
        </div>
        <div class="card-body">
          <p><strong>سعر الباقة:</strong> <i class="fas fa-dollar-sign text-primary"></i> {{ $subscription->PlanPrice }}</p>
          <p><strong>نسبة الضريبة:</strong> <i class="fas fa-receipt text-secondary"></i> {{ $subscription->TaxRate }}%</p>
          <p>
            <strong>غرامة التأخير:</strong>
            <span class="{{ $subscription->LateFee > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
              <i class="{{ $subscription->LateFee > 0 ? 'fas fa-exclamation-triangle text-danger' : 'fas fa-minus-circle text-muted' }}"></i>
              {{ $subscription->LateFee }}
            </span>
          </p>
          <p><strong>تم دفع الغرامة:</strong> 
            @if($subscription->LateFeePaid)
              <span class="text-success"><i class="fas fa-check-circle"></i> نعم</span>
              <small>بتاريخ {{ $subscription->LateFeePaymentDate }}</small>
            @else
              <span class="text-danger"><i class="fas fa-times-circle"></i> لا</span>
            @endif
          </p>
          <p><strong>فترة السماح:</strong> <i class="fas fa-clock text-info"></i> {{ $subscription->GracePeriod }} يوم</p>
          <p><strong>نسبة الخصم:</strong> <i class="fas fa-percentage text-danger"></i> {{ $subscription->DiscountRate }}%</p>
          <p><strong>قيمة الخصم:</strong> <i class="fas fa-tag text-success"></i> {{ $subscription->DiscountAmount }}</p>
          <p><strong>سبب الخصم:</strong> {{ $subscription->DiscountReason ?? '---' }}</p>
          <p><strong>المبلغ المدفوع:</strong> <i class="fas fa-check text-success"></i> {{ $subscription->AmountPaid }}</p>
          <p><strong>المبلغ المتبقي:</strong> <i class="fas fa-hourglass-half text-warning"></i> {{ $subscription->AmountRemaining }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- التحذيرات والملاحظات -->
  <div class="row mt-4">
    <div class="col-lg-6">
      <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-gradient-warning text-dark fw-bold">
          <i class="fas fa-bell me-2"></i> التحذيرات
        </div>
        <div class="card-body">
          <p><strong>تم إرسال تحذير:</strong> {{ $subscription->WarningSent ? 'نعم' : 'لا' }}</p>
          <p><strong>عدد التحذيرات:</strong> {{ $subscription->WarningCount }}</p>
          <p><strong>آخر تحذير بتاريخ:</strong> {{ $subscription->LastWarningDate }}</p>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-gradient-secondary text-white fw-bold">
          <i class="fas fa-sticky-note me-2"></i> الملاحظات الإدارية
        </div>
        <div class="card-body">
          {!! $subscription->AdminNotes !!}
        </div>
      </div>
    </div>
  </div>

  <!-- سجل التواريخ -->
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
                <td><i class="fas fa-calendar-plus text-success"></i> {{ $subscription->created_at }}</td>
                <td><i class="fas fa-calendar-check text-info"></i> {{ $subscription->updated_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
