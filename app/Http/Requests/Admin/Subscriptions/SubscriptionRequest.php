<?php

namespace App\Http\Requests\Admin\Subscriptions;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules()
{
    return [

        'organization_id' => 'required|exists:organizations,organization_id',

        'package_id' => 'required|exists:packages,package_id',

'subscription_type' => 'required|in:trial,paid',

'billing_mode' => 'required_if:subscription_type,paid|nullable|in:full,periodic',

'trial_period_days' => 'nullable|integer|min:1|required_if:subscription_type,trial',

'trial_end_date' => 'nullable|date|after_or_equal:today',

        'start_date' => 'nullable|date',

        'end_date' => 'nullable|date|after_or_equal:start_date',

        'status' => 'required|in:pending,active,expired,cancelled',

        'activated_at' => 'nullable|date',

        'auto_renew' => 'required|boolean',

        'notes' => 'nullable|string|max:1000',

    ];
}

public function messages()
{
    return [

        'organization_id.required' => 'يجب اختيار المؤسسة.',

        'organization_id.exists' => 'المؤسسة المحددة غير موجودة.',

        'package_id.required' => 'يجب اختيار الباقة.',

        'package_id.exists' => 'الباقة المحددة غير موجودة.',
        'auto_renew.required' => 'يجب اختيار هل التحديث تلقائي او لا.',
        'subscription_type.required' => 'يجب تحديد نوع الاشتراك.',
'billing_mode.required' => 'يجب تحديد طريقة فوترة الاشتراك.',
'billing_mode.in' => 'طريقة الفوترة المحددة غير صحيحة. يرجى اختيار الفوترة الكاملة أو الدورية.',
        'subscription_type.in' => 'نوع الاشتراك غير صحيح.',

        'trial_period_days.integer' => 'مدة الفترة التجريبية يجب أن تكون رقماً.',

        'trial_period_days.min' => 'مدة الفترة التجريبية لا يمكن أن تكون أقل من صفر.',

        'trial_end_date.date' => 'تاريخ انتهاء الفترة التجريبية غير صحيح.',

        'start_date.date' => 'تاريخ بداية الاشتراك غير صحيح.',

        'end_date.after_or_equal' => 'تاريخ نهاية الاشتراك يجب أن يكون بعد أو يساوي تاريخ البداية.',

        'status.required' => 'يجب تحديد حالة الاشتراك.',

        'status.in' => 'حالة الاشتراك غير صحيحة.',

        'activated_at.date' => 'تاريخ تفعيل الاشتراك غير صحيح.',

        'auto_renew.boolean' => 'قيمة التجديد التلقائي غير صحيحة.',

        'notes.max' => 'الملاحظات يجب ألا تتجاوز 1000 حرف.',

    ];
}
}
