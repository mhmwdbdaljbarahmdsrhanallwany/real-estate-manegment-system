<?php

namespace App\Http\Requests\Admin\Organization;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrganizationRequest extends FormRequest
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

            // =========================
            // بيانات المؤسسة
            // =========================

            'name' => [
                'required',
                'string',
                Rule::unique('organizations', 'name')
                    ->ignore($id, 'organization_id'),
            ],

            'commercial_name' => [
                'nullable',
                'string',
                Rule::unique('organizations', 'commercial_name')
                    ->ignore($id, 'organization_id'),
            ],

           'commercial_registration' => [
    'required',
    'regex:/^[\x{0621}-\x{064A}]\/[0-9]{3}$/u',
    Rule::unique('organizations', 'commercial_registration')
        ->ignore($id, 'organization_id'),
],

            'tax_number' => [
                'required',
                'digits:9',
                Rule::unique('organizations', 'tax_number')
                    ->ignore($id, 'organization_id'),
            ],

            'municipality_license' => $this->isMethod('post')
                ? 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
                : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            'real_estate_broker_license' => $this->isMethod('post')
                ? 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
                : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            'logo' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            'unified_national_number' => [
                'nullable',
                'regex:/^[0-9]+$/',
            ],

            // =========================
            // بيانات الاتصال
            // =========================

            'phone' => [
                'required',
                'regex:/^(70|71|73|77|78)[0-9]{7}$/',
            ],

            'mobile' => [
                'nullable',
                'regex:/^(70|71|73|77|78)[0-9]{7}$/',
            ],

            'email' => [
                'required',
                'email',
            ],

            'website' => [
                'nullable',
                'url',
            ],

            // =========================
            // العنوان
            // =========================

            'country' => [
                'required',
                'string',
            ],

            'state' => [
                'required',
                'string',
            ],

            'city' => [
                'required',
                'string',
            ],

            'district' => [
                'required',
                'string',
            ],

            'street' => [
                'nullable',
                'string',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'postal_code' => [
                'nullable',
                'regex:/^[0-9]+$/',
            ],

            'po_box' => [
                'nullable',
                'regex:/^[0-9]+$/',
            ],

            // =========================
            // الموقع الجغرافي
            // =========================

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'map_url' => [
                'required',
                'url',
            ],

            // =========================
            // الممثل القانوني
            // =========================

            'legal_representative_name' => [
                'nullable',
                'string',
            ],

            'legal_representative_id' => [
                'nullable',
                'regex:/^[0-9]+$/',
            ],

            'legal_representative_phone' => [
                'nullable',
                'regex:/^(70|71|73|77|78)[0-9]{7}$/',
            ],

            // =========================
            // البيانات البنكية
            // =========================

            'bank_name' => [
                'required',
                'string',
            ],

            'bank_account_name' => [
                'required',
                'string',
            ],

            'bank_account_number' => [
                'required',
                'regex:/^[0-9]+$/',
            ],

            'bank_iban' => [
                'nullable',
                'regex:/^YE[0-9]{2}[A-Z]{4}[0-9]{4}[A-Z0-9]{18}$/',
            ],

            // =========================
            // الملاحظات
            // =========================

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            // بيانات المؤسسة
            'name.required' => 'حقل اسم المؤسسة مطلوب.',
            'name.unique' => 'اسم المؤسسة مستخدم بالفعل.',

            'commercial_name.unique' => 'الاسم التجاري مستخدم بالفعل.',
'commercial_registration.required' =>
    'حقل السجل التجاري مطلوب.',

'commercial_registration.regex' =>
    'السجل التجاري يجب أن يتكون من حرف عربي واحد ثم / ثم 3 أرقام، مثل: ش/657.',

'commercial_registration.unique' =>
    'السجل التجاري مستخدم من قبل مؤسسة أخرى.',

            'tax_number.required' => 'حقل الرقم الضريبي مطلوب.',
            'tax_number.digits' =>
                'الرقم الضريبي يجب أن يتكون من 9 أرقام بالضبط.',
            'tax_number.unique' =>
                'الرقم الضريبي مستخدم من قبل مؤسسة أخرى.',

            'unified_national_number.regex' =>
                'الرقم الوطني الموحد يجب أن يحتوي على أرقام فقط.',

            // الملفات
            'municipality_license.required' =>
                'حقل رخصة البلدية مطلوب.',
            'municipality_license.file' =>
                'رخصة البلدية يجب أن تكون ملفًا صحيحًا.',
            'municipality_license.mimes' =>
                'رخصة البلدية يجب أن تكون بصيغة PDF أو JPG أو JPEG أو PNG.',
            'municipality_license.max' =>
                'حجم رخصة البلدية يجب ألا يتجاوز 2 ميجابايت.',

            'real_estate_broker_license.required' =>
                'حقل رخصة الوساطة العقارية مطلوب.',
            'real_estate_broker_license.file' =>
                'رخصة الوساطة العقارية يجب أن تكون ملفًا صحيحًا.',
            'real_estate_broker_license.mimes' =>
                'رخصة الوساطة العقارية يجب أن تكون بصيغة PDF أو JPG أو JPEG أو PNG.',
            'real_estate_broker_license.max' =>
                'حجم رخصة الوساطة العقارية يجب ألا يتجاوز 2 ميجابايت.',

            'logo.file' =>
                'الشعار يجب أن يكون ملفًا صحيحًا.',
            'logo.mimes' =>
                'الشعار يجب أن يكون بصيغة JPG أو JPEG أو PNG.',
            'logo.max' =>
                'حجم الشعار يجب ألا يتجاوز 2 ميجابايت.',

            // الاتصال
            'phone.required' => 'حقل الهاتف مطلوب.',
            'phone.regex' =>
                'رقم الهاتف يجب أن يتكون من 9 أرقام ويبدأ بـ 70 أو 71 أو 73 أو 77 أو 78.',

            'mobile.regex' =>
                'رقم الجوال يجب أن يتكون من 9 أرقام ويبدأ بـ 70 أو 71 أو 73 أو 77 أو 78.',

            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' =>
                'صيغة البريد الإلكتروني غير صحيحة.',

            'website.url' =>
                'صيغة الموقع الإلكتروني غير صحيحة.',

            // العنوان
            'country.required' => 'حقل الدولة مطلوب.',
            'state.required' => 'حقل المحافظة مطلوب.',
            'city.required' => 'حقل المدينة مطلوب.',
            'district.required' => 'حقل المنطقة مطلوب.',

            'postal_code.regex' =>
                'الرمز البريدي يجب أن يحتوي على أرقام فقط.',

            'po_box.regex' =>
                'صندوق البريد يجب أن يحتوي على أرقام فقط.',

            // الموقع الجغرافي
            'latitude.required' =>
                'حقل خط العرض مطلوب.',
            'latitude.numeric' =>
                'خط العرض يجب أن يكون رقمًا.',
            'latitude.between' =>
                'خط العرض يجب أن يكون بين -90 و 90.',

            'longitude.required' =>
                'حقل خط الطول مطلوب.',
            'longitude.numeric' =>
                'خط الطول يجب أن يكون رقمًا.',
            'longitude.between' =>
                'خط الطول يجب أن يكون بين -180 و 180.',

            'map_url.required' =>
                'رابط الموقع على الخريطة مطلوب.',
            'map_url.url' =>
                'صيغة رابط الخريطة غير صحيحة.',

            // الممثل القانوني
            'legal_representative_id.regex' =>
                'رقم هوية الممثل القانوني يجب أن يحتوي على أرقام فقط.',

            'legal_representative_phone.regex' =>
                'جوال الممثل القانوني يجب أن يتكون من 9 أرقام ويبدأ بـ 70 أو 71 أو 73 أو 77 أو 78.',

            // البيانات البنكية
            'bank_name.required' =>
                'حقل اسم البنك مطلوب.',

            'bank_account_name.required' =>
                'حقل اسم صاحب الحساب البنكي مطلوب.',

            'bank_account_number.required' =>
                'حقل رقم الحساب البنكي مطلوب.',

            'bank_account_number.regex' =>
                'رقم الحساب البنكي يجب أن يحتوي على أرقام فقط.',

            'bank_iban.regex' =>
                'صيغة IBAN اليمني غير صحيحة. يجب أن يتكون من 30 خانة ويبدأ بـ YE.',

        ];
    }
}