<?php

namespace App\Http\Requests\Admin\Packages;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
            ],
    
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('packages', 'code')
                    ->ignore($id, 'package_id'),
            ],

            // المستوى
            'level' => 'required|integer|min:1',
            // ملاحظة: مستوى الباقة

            // ترتيب العرض
            'sort_order' => 'nullable|integer',
            // ملاحظة: ترتيب ظهور الباقات

            // الوصف
            'description' => 'nullable|string',
            // ملاحظة: وصف الباقة

            // السعر الأساسي
            'base_price' => 'required|numeric|min:0',
            // ملاحظة: السعر قبل الخصم والضريبة

            // الخصم
            'discount_type' => 'nullable|in:fixed,percent',
            'discount_value' => 'nullable|numeric|min:0',
            // ملاحظة: الخصم

            // الضريبة
            'tax_type' => 'nullable|in:fixed,percent',
            'tax_value' => 'nullable|numeric|min:0',
            // ملاحظة: الضريبة

            // دورة الفوترة
            'billing_cycle' => 'required|in:monthly,yearly,weekly',
            // ملاحظة: نوع الاشتراك

            // مدة الاشتراك
            'duration_days' => 'required|integer|min:1',
            // ملاحظة: مدة الباقة بالأيام

            // التنبيهات
            'reminder_before_days' => 'nullable|integer|min:0',
            'reminder_after_expiry_days' => 'nullable|integer|min:0',

            // فترة السماح
            'grace_period_days' => 'nullable|integer|min:0',

            // الغرامات
            'penalty_type' => 'nullable|in:fixed,percent',
            'penalty_value' => 'nullable|numeric|min:0',
            'penalty_repeat' => 'nullable|in:once,daily,weekly,monthly',
            'max_penalty' => 'nullable|numeric|min:0',

            // الإيقاف
            'suspend_after_days' => 'nullable|integer|min:0',

            // الصلاحيات بعد الانتهاء
            'allow_login_after_expiry' => 'boolean',
            'allow_readonly_mode' => 'boolean',

            // الحدود
            'max_users' => 'nullable|integer|min:0',
            'max_branches' => 'nullable|integer|min:0',
            'max_properties' => 'nullable|integer|min:0',
            'max_units' => 'nullable|integer|min:0',
            'storage_limit_mb' => 'nullable|integer|min:0',
            'max_api_requests' => 'nullable|integer|min:0',

            // العرض
            'is_featured' => 'boolean',
            'is_active' => 'boolean',

            // ملاحظات
            'notes' => 'nullable|string',
        ];
    }

    /**
     * رسائل التحقق باللغة العربية.
     */
    public function messages(): array
    {
        return [
            'required' => 'حقل :attribute مطلوب، يرجى إدخال قيمته.',
            'string' => 'يجب أن تكون قيمة :attribute نصًا صحيحًا.',
            'integer' => 'يجب أن تكون قيمة :attribute عددًا صحيحًا.',
            'numeric' => 'يجب أن تكون قيمة :attribute رقمًا صحيحًا أو عشريًا.',
            'min.numeric' => 'يجب ألا تقل قيمة :attribute عن :min.',
            'min.integer' => 'يجب ألا تقل قيمة :attribute عن :min.',
            'max.string' => 'يجب ألا يتجاوز :attribute عدد :max حرفًا.',
            'unique' => 'قيمة :attribute مستخدمة بالفعل، يرجى اختيار قيمة أخرى.',
            'in' => 'القيمة المحددة في :attribute غير صحيحة، يرجى اختيار قيمة من الخيارات المتاحة.',
            'boolean' => 'قيمة :attribute غير صحيحة، يرجى تحديد نعم أو لا.',
            'nullable' => 'قيمة :attribute غير صحيحة.',
        ];
    }
}
