@if($inactiveCashboxesCount > 0)
    <div class="col-12">
        <x-admin-notice
            type="warning"
            title="تنبيه: توجد صناديق غير نشطة"
        >
            يوجد {{ $inactiveCashboxesCount }}
            صندوق غير نشط في النظام.
            تحقق من حالتها قبل محاولة إجراء عمليات مالية عليها.
        </x-admin-notice>
    </div>
@endif

@if($activeMainCashboxesCount === 0)
    <div class="col-12">
        <x-admin-notice
            type="danger"
            title="تنبيه: لا يوجد صندوق رئيسي نشط"
        >
            لا يوجد حاليًا صندوق رئيسي نشط.
            تحقق من إعدادات الصندوق الرئيسي قبل متابعة العمليات المالية.
        </x-admin-notice>
    </div>
@endif

@if($activeMainCashboxesCount > 0 && $inactiveCashboxesCount === 0)
    <div class="col-12">
        <x-admin-notice
            type="success"
            title="حالة الصناديق سليمة"
        >
            يوجد صندوق رئيسي نشط، ولا توجد صناديق غير نشطة.
        </x-admin-notice>
    </div>
@endif