<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // السماح للجميع، يمكنك تخصيصه لاحقًا
    }

    public function rules()
    {
        return [
            'AgencyID'=> 'required|exists:agency,agency_id',
            'PlanID'=> 'required|exists:subscription_plans,plan_id',
            'StartDate'=> 'required|date',
            'EndDate'=> 'nullable|date|after_or_equal:StartDate',
            'RenewalDate'=> 'nullable|date|after_or_equal:EndDate',
            'TrialPeriod'=> 'nullable|integer|min:0',

        //    'PlanStatus' => 'required|string|in:trial,active,expired,grace_period',

            'PlanPrice'=> 'nullable|numeric|min:0',
            'TaxRate'=> 'nullable|numeric|min:0|max:100',

            'LateFee'=> 'nullable|numeric|min:0',
            'LateFeePaid'=> 'boolean',
            'LateFeePaymentDate'=> 'nullable|date',

            'GracePeriod'=> 'nullable|integer|min:0',

            'WarningSent'=> 'boolean',
            'WarningCount'=> 'integer|min:0',
            'LastWarningDate'=> 'nullable|date',

            'DiscountRate'=> 'nullable|numeric|min:0|max:100',
            'DiscountAmount'=> 'nullable|numeric|min:0',
            'DiscountReason'=> 'nullable|string|max:255',

            'AmountPaid'=> 'numeric|min:0',
            'AmountRemaining'=> 'nullable|numeric|min:0',

            'AdminNotes'=> 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'AgencyID.required' => 'يجب اختيار المؤسسة المرتبطة بالاشتراك.',
            'PlanID.required'   => 'يجب اختيار الباقة.',
            'StartDate.required' => 'تاريخ بداية الاشتراك مطلوب.',
            'EndDate.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ البداية.',
            // 'PlanStatus.required' => 'يجب تحديد حالة الباقة.',
            'WarningCount'=> 'يجب ادخال عدد التحذيرات وان لم يكن هناك تحذيرات ادخل القيمة صفر',
            'AmountPaid.min' => 'المبلغ المدفوع لا يمكن أن يكون أقل من صفر.',
        ];
    }
}
