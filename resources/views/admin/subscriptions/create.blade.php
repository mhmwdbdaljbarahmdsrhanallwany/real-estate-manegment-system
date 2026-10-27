@extends('admin.layout.master')
@section('title')
    إضافة اشتراك جديد
@endsection
@section('contect')
    @include('admin.incloude.agencynavepare')

    <x-admin-notice title="ملاحظات مهمة">

        <ul>
    
            <li>
                يتم إنشاء دورة حياة الاشتراك تلقائياً بناءً على الباقة ونوع الاشتراك.
            </li>
    
            <li>
                الاشتراك التجريبي يبدأ بعد تحديد مدة التجربة، والمدفوع يبدأ مباشرة.
            </li>
    
            <li>
                ستُطبق إجراءات السماح والغرامات والإيقاف حسب إعدادات الباقة.
            </li>
    
        </ul>
    
    </x-admin-notice>
        <form action="{{ route('admin.subscriptions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="trial_end_date" id="trialEndDate">
            <input type="hidden" name="start_date" id="startDate">
         <input type="hidden" name="end_date" id="endDate">
            <div class="row">
 
                {{-- ===========================
                    بيانات الاشتراك
            =========================== --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-file-signature text-primary"></i>
                                اضافة بيانات الاشتراك 
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <x-constructor-form :selectes="[
                                    'organization_id' => [
                                        'label' => 'المؤسسة',
                                        'icon' => 'fas fa-building',
                                        'type' => 'relation',
                                        'name' => 'organization_id',
                                        'option' => $organizations,
                                        'value' => 'organization_id',
                                        'display' => 'name',
                                    ],
                                    'package_id' => [
                                        'label' => 'الباقة',
                                        'icon' => 'fas fa-box',
                                        'type' => 'relation',
                                        'name' => 'package_id',
                                        'id' => 'packageSelect',
                                        'option' => $packages,
                                        'value' => 'package_id',
                                        'display' => 'name',
                                        'meta' => ['duration_days', 'grace_period_days', 'suspend_after_days'],
                                    ],
                                    'subscription_type' => [
                                        'label' => 'نوع الاشتراك',
                                        'name' => 'subscription_type',
                                        'id' => 'subscriptionType',
                                        'icon' => 'fas fa-layer-group',
                                        'option' => [
                                            'trial' => 'تجريبي',
                                            'paid' => 'مدفوع',
                                        ],
                                    ],
    'billing_mode' => [
    'label' => 'طريقة الفوترة',
    'name' => 'billing_mode',
    'id' => 'billingMode',
    'wrapper_class' => 'billing-mode-wrapper',
    'icon' => 'fas fa-file-invoice-dollar',
    'default' => 'full',
    'option' => [
        'full' => 'فوترة كاملة — فاتورة واحدة لكامل المدة',
        'periodic' => 'فوترة دورية — فاتورة لكل دورة',
    ],
],
                                    'status' => [
                                        'label' => 'الحالة',
                                        'name' => 'status',
                                        'icon' => 'fas fa-toggle-on',
                                        'option' => [
                                            'pending' => 'قيد الانتظار',
                                            'active' => 'نشط',
                                        ],
                                    ],
                                    'auto_renew' => [
                                        'label' => 'التجديد التلقائي',
                                        'name' => 'auto_renew',
                                        'icon' => 'fas fa-sync',
                                        'option' => [
                                            1 => 'نعم',
                                            0 => 'لا',
                                        ],
                                    ],
                                ]" />
                            </div>
                        </div>
                    </div>
                    {{-- ===========================
                        الفترة التجريبية
                =========================== --}}
                    <div class="card shadow-sm border-0 mb-4" id="trialCard">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-hourglass-half text-warning"></i>
                                إعدادات الفترة التجريبية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light border">
                                يتم احتساب بداية الاشتراك ونهايته تلقائياً
                                اعتماداً على مدة الفترة التجريبية
                                ومدة الباقة.
                            </div>
                            <div class="row">
                                <x-constructor-form :types="[
                                    'trial_period_days' => [
                                        'label' => 'مدة الفترة التجريبية',
                                        'type' => 'number',
                                        'name' => 'trial_period_days',
                                        'id' => 'trialDays',
                                        'icon' => 'fas fa-clock',
                                    ],
                                ]" />
                            </div>
                        </div>
                    </div>
                    {{-- ===========================
                        ملاحظات
                =========================== --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-sticky-note text-info"></i>
                                ملاحظات
                            </h5>
                        </div>
                        <div class="card-body">
                            <x-constructor-form :types="[
                                'notes' => [
                                    'label' => 'الملاحظات',
                                    'name' => 'notes',
                                    'icon' => 'fas fa-pen',
                                ],
                            ]" />
                        </div>
                    </div>
                </div>
                {{-- ===========================
                    المعاينة
            =========================== --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-eye text-success"></i>
                                معاينة الاشتراك
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="subscription-journey">
                                <h5 class="font-weight-bold mb-4 text-center">
                                    <i class="fas fa-route text-primary"></i>
                                    رحلة دورة حياة الاشتراك
                                </h5>
                                {{-- إنشاء الاشتراك --}}
                                <div class="journey-step active">
                                    <div class="step-icon bg-primary">
                                        <i class="fas fa-plus text-white"></i>
                                    </div>
                                    <div class="step-card">
                                        <h6>
                                            إنشاء الاشتراك
                                        </h6>
                                        <small class="text-muted">
                                            تاريخ الإنشاء
                                        </small>
                                        <div class="font-weight-bold">
                                            {{ date('Y-m-d') }}
                                        </div>
                                    </div>
                                </div>
                                {{-- الفترة التجريبية --}}
                                <div class="journey-step" id="trialStep">
                                    <div class="step-icon bg-warning">
                                        <i class="fas fa-flask text-white"></i>
                                    </div>
                                    <div class="step-card">
                                        <h6>
                                            الفترة التجريبية
                                        </h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    المدة
                                                </small>
                                                <div id="trialDaysPreview">
                                                    0 يوم
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    النهاية
                                                </small>
                                                <div id="trialEndPreview">
                                                    --
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- بداية الاشتراك --}}
                                <div class="journey-step">
                                    <div class="step-icon bg-success">
                                        <i class="fas fa-check text-white"></i>
                                    </div>
                                    <div class="step-card">
                                        <h6>
                                            بداية الاشتراك
                                        </h6>
                                        <div id="startPreview">
                                            --
                                        </div>
                                    </div>
                                </div>
                                {{-- نهاية الاشتراك --}}
                                <div class="journey-step">
                                    <div class="step-icon bg-danger">
                                        <i class="fas fa-flag-checkered text-white"></i>
                                    </div>
                                    <div class="step-card">
                                        <h6>
                                            نهاية الاشتراك
                                        </h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    التاريخ
                                                </small>
                                                <div id="endPreview">
                                                    --
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    المدة
                                                </small>
                                                <div id="durationPreview">
                                                    --
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- فترة السماح --}}
                                <div class="journey-step">
                                    <div class="step-icon bg-info">
                                        <i class="fas fa-hourglass-half text-white"></i>
                                    </div>
                                    <div class="step-card">
                                        <h6>
                                            فترة السماح
                                        </h6>
                                        <div>
                                            بعد انتهاء الاشتراك
                                        </div>
                                    </div>
                                </div>
                                {{-- الغرامة --}}
                                <div class="journey-step">
                                    <div class="step-icon bg-warning">
                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                    </div>
                                    <div class="step-card">
                                        <h6>
                                            تطبيق الغرامة
                                        </h6>
                                        <div>
                                            يبدأ بعد فترة السماح
                                        </div>
                                    </div>
                                </div>
                                {{-- الإيقاف --}}
                                <div class="journey-step">
                                    <div class="step-icon bg-dark">
                                        <i class="fas fa-lock text-white"></i>
                                    </div>
                                    <div class="step-card">
                                        <h6>
                                            إيقاف الاشتراك
                                        </h6>
                                        <div>
                                            تعطيل خدمات المؤسسة
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body text-right">
                    <x-button :save='true' text='اضافة اشتراك جديد' />
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-light">
                        إلغاء
                    </a>
                </div>
            </div>
   
    </form>
    {{-- الأزرار --}}
    
@section('script')
    <script>
      document.addEventListener('DOMContentLoaded', function () {
const packageSelect = document.getElementById('packageSelect');
const typeSelect = document.getElementById('subscriptionType');
const billingModeWrapper = document.querySelector('.billing-mode-wrapper');
const billingModeSelect = document.getElementById('billingMode');
const trialCard = document.getElementById('trialCard');
const trialDaysInput = document.getElementById('trialDays');
const trialEndInput = document.getElementById('trialEndDate');
const startDateInput = document.getElementById('startDate');
const endDateInput = document.getElementById('endDate');
const trialDaysPreview = document.getElementById('trialDaysPreview');
const trialEndPreview = document.getElementById('trialEndPreview');
const startPreview = document.getElementById('startPreview');
const endPreview = document.getElementById('endPreview');
const durationPreview = document.getElementById('durationPreview');
function formatDate(date)
{
    let y = date.getFullYear();
    let m = String(date.getMonth()+1).padStart(2,'0');
    let d = String(date.getDate()).padStart(2,'0');
    return `${y}-${m}-${d}`;
}
function calculateSubscription()
{
    if(!packageSelect || !packageSelect.value)
        return;
    let option =packageSelect.options[packageSelect.selectedIndex];
    let duration =Number(option.dataset.duration_days) || 0;
    let grace =Number(option.dataset.grace_period_days) || 0;
    let suspend =Number(option.dataset.suspend_after_days) || 0;
    let today = new Date();
    let trialDays = 0;
    let trialEnd = null;
    if(typeSelect && typeSelect.value === 'trial')
    {
        trialDays =Number(trialDaysInput.value) || 0;
        if(trialDays > 0)
        {
            trialEnd = new Date(today);
            trialEnd.setDate(today.getDate()+trialDays);
        }
    }
    let startDate =trialEnd ?? today;
    let endDate =new Date(startDate);
    endDate.setDate(startDate.getDate()+duration);
    /*
    تعبئة الحقول المخفية
    */
    if(trialEndInput)
    {
        trialEndInput.value =trialEnd?formatDate(trialEnd):'';
    }
    if(startDateInput)
    {
        startDateInput.value =formatDate(startDate);
    }
    if(endDateInput)
    {
        endDateInput.value =formatDate(endDate);
    }
    /*
    تحديث المعاينة
    */
    if(trialDaysPreview)
    {
        trialDaysPreview.innerHTML =trialDays+' يوم';
    }

    if(trialEndPreview)
    {
        trialEndPreview.innerHTML =trialEnd?formatDate(trialEnd):'-';
    }
    if(startPreview)
    {
        startPreview.innerHTML =formatDate(startDate);
    }
    if(endPreview)
    {
        endPreview.innerHTML =formatDate(endDate);
    }
    if(durationPreview)
    {
        durationPreview.innerHTML =duration+' يوم';
    }
}
function toggleBillingMode() {
    if (!typeSelect || !billingModeWrapper || !billingModeSelect) {
        return;
    }

    const isPaid = typeSelect.value === 'paid';

    billingModeWrapper.style.display = isPaid ? '' : 'none';
    billingModeSelect.disabled = !isPaid;

    if (!isPaid) {
        billingModeSelect.value = 'full';
    }
}
function toggleTrial()
{
    if(!typeSelect)
        return;
    if(typeSelect.value === 'trial')
    {
        if(trialCard)
            trialCard.style.display='block';
    }
    else
    {
        if(trialCard)
            trialCard.style.display='none';
        if(trialDaysInput)
            trialDaysInput.value='';
    }
    calculateSubscription();
    toggleBillingMode();
}

/*
الأحداث
*/

if(typeSelect)
{
    typeSelect.addEventListener('change',toggleTrial
    );
}

if(packageSelect)
{
    packageSelect.addEventListener('change',calculateSubscription);
}
if(trialDaysInput)
{
    trialDaysInput.addEventListener('input',calculateSubscription );
}
/*
تشغيل أولي
*/
toggleTrial();

});
    </script>
@endsection
@endsection
