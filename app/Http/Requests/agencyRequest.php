<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;


class agencyRequest extends FormRequest
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
    public function rules(): array
    {
        $id = $this->route('id');

    return [
        'agency_name' => 'required',
        'commercial_registration' => [
            'required',
            Rule::unique('agency', 'commercial_registration')->ignore($id, 'agency_id')
        ],
        'tax_number' => [
            'required',
            Rule::unique('agency', 'tax_number')->ignore($id, 'agency_id')
        ],
       
          // رخصة البلية: مطلوبة عند الإضافة فقط
        'municipality_license.*' => $this->isMethod('post')
        ? 'required|file|mimes:pdf,jpg,png|max:2048'
        : 'nullable|file|mimes:pdf,jpg,png|max:2048',
     
          // رخصة الوسيط العقاري: مطلوبة عند الإضافة فقط
        'real_estate_broker_license.*' => $this->isMethod('post')
        ? 'required|file|mimes:pdf,jpg,png|max:2048'
        : 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'unified_national_number' => 'required',
        'phone' => 'required',
        'mobile' => 'required',
        'email' => 'required|email',
        'website' => 'required|url',
        'address' => 'required',
        'city' => 'required',
        'district' => 'required',
        'postal_code' => 'required',
        'po_box' => 'required',
        'legal_representative_name' => 'required',
        'legal_representative_id' => 'required',
        'legal_representative_phone' => 'required',
        'bank_name' => 'required',
        'bank_account_name' => 'required',
        'bank_account_number' => 'required',
        // 'bank_iban' => 'required',
        // 'status' => 'required',
        // 'subscription_expiry' => 'required|date',
        // 'subscription_status' => 'required',
        // 'trial_expiry' => 'required|date',
        // 'access_blocked' => 'required',
        // 'block_reason' => 'required',
        // 'last_payment_check' => 'required|date',
        // 'max_branches' => 'required|integer',
        // 'max_users' => 'required|integer',
        // 'max_units' => 'required|integer',
        // 'api_key' => 'required',
        // 'api_enabled' => 'required',
        // 'grace_period_days' => 'required|integer',
        // 'auto_suspend' => 'required',
         // المستندات: مطلوبة عند الإضافة فقط
         'documents_path.*' => $this->isMethod('post')
         ? 'required|file|mimes:pdf,jpg,png|max:2048'
         : 'nullable|file|mimes:pdf,jpg,png|max:2048',
        // 'verification_date' => 'required|date',
        // 'verified_by' => 'required',
        // 'rejection_reason' => 'required',
    ];
}

public function messages(): array
{
    return [
        'agency_name.required' => 'حقل اسم الشركة مطلوب',
        'commercial_registration.required' => 'حقل السجل التجاري مطلوب',
        'commercial_registration.unique' => '⚠️ السجل التجاري مستخدم من قبل مؤسسة أخرى.',
        'tax_number.required' => 'حقل الرقم الضريبي مطلوب',
        'tax_number.unique' => '⚠️ الرقم الضريبي مستخدم من قبل مؤسسة أخرى.',
        'municipality_license.required' => 'حقل رخصة البلدية مطلوب',
        'real_estate_broker_license.required' => 'حقل رخصة الوسيط العقاري مطلوب',
        'unified_national_number.required' => 'حقل الرقم الوطني الموحد مطلوب',
        'phone.required' => 'حقل الهاتف مطلوب',
        'mobile.required' => 'حقل الجوال مطلوب',
        'email.required' => 'حقل البريد الإلكتروني مطلوب',
        'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
        'website.required' => 'حقل الموقع الإلكتروني مطلوب',
        'website.url' => 'صيغة الموقع الإلكتروني غير صحيحة',
        'address.required' => 'حقل العنوان مطلوب',
        'city.required' => 'حقل المدينة مطلوب',
        'district.required' => 'حقل الحي مطلوب',
        'postal_code.required' => 'حقل الرمز البريدي مطلوب',
        'po_box.required' => 'حقل صندوق البريد مطلوب',
        'legal_representative_name.required' => 'حقل اسم الممثل القانوني مطلوب',
        'legal_representative_id.required' => 'حقل هوية الممثل القانوني مطلوب',
        'legal_representative_phone.required' => 'حقل هاتف الممثل القانوني مطلوب',
        'bank_name.required' => 'حقل اسم البنك مطلوب',
        'bank_account_name.required' => 'حقل اسم الحساب البنكي مطلوب',
        'bank_account_number.required' => 'حقل رقم الحساب البنكي مطلوب',
        // 'bank_iban.required' => 'حقل رقم الآيبان مطلوب',
        // 'status.required' => 'حقل الحالة مطلوب',
        // 'subscription_expiry.required' => 'حقل تاريخ انتهاء الاشتراك مطلوب',
        // 'subscription_expiry.date' => 'صيغة تاريخ انتهاء الاشتراك غير صحيحة',
        // 'subscription_status.required' => 'حقل حالة الاشتراك مطلوب',
        // 'trial_expiry.required' => 'حقل تاريخ انتهاء الفترة التجريبية مطلوب',
        // 'trial_expiry.date' => 'صيغة تاريخ انتهاء الفترة التجريبية غير صحيحة',
        // 'access_blocked.required' => 'حقل حظر الوصول مطلوب',
        // 'block_reason.required' => 'حقل سبب الحظر مطلوب',
        // 'last_payment_check.required' => 'حقل آخر فحص للدفع مطلوب',
        // 'last_payment_check.date' => 'صيغة آخر فحص للدفع غير صحيحة',
        // 'max_branches.required' => 'حقل الحد الأقصى للفروع مطلوب',
        // 'max_users.required' => 'حقل الحد الأقصى للمستخدمين مطلوب',
        // 'max_units.required' => 'حقل الحد الأقصى للوحدات مطلوب',
        // 'api_key.required' => 'حقل مفتاح الـ API مطلوب',
        // 'api_enabled.required' => 'حقل تفعيل الـ API مطلوب',
        // 'grace_period_days.required' => 'حقل فترة السماح مطلوب',
        // 'auto_suspend.required' => 'حقل الإيقاف التلقائي مطلوب',
        'documents_path.required' => 'حقل مسار المستندات مطلوب',
        // 'verification_date.required' => 'حقل تاريخ التحقق مطلوب',
        // 'verification_date.date' => 'صيغة تاريخ التحقق غير صحيحة',
        // 'verified_by.required' => 'حقل تم التحقق بواسطة مطلوب',
        // 'rejection_reason.required' => 'حقل سبب الرفض مطلوب',
    ];
}
}
