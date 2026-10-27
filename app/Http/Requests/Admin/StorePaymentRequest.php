<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_id' => [
                'required',
                'integer',
            ],

            'subscription_id' => [
                'required',
                'integer',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
            ],

            'payment_method' => [
                'required',
                Rule::in(['cash', 'bank']),
            ],

            'transaction_id' => [
                'nullable',
                'string',
                'max:255',
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'metadata' => [
                'nullable',
                'array',
            ],

            'cashboxe_id' => [
                'nullable',
                'required_if:payment_method,cash',
                'prohibited_unless:payment_method,cash',
                'integer',
                Rule::exists('cashboxes', 'cashboxe_id')
                    ->where('status', 'active')
                    ->where('code', 'CASH-MAIN'),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes(
            'transaction_id',
            'required_without:reference_number',
            fn ($input) => ($input->payment_method ?? null) === 'bank'
        );

        $validator->sometimes(
            'reference_number',
            'required_without:transaction_id',
            fn ($input) => ($input->payment_method ?? null) === 'bank'
        );
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'transaction_id' => $this->normalizeBankReference(
                $this->input('transaction_id')
            ),
            'reference_number' => $this->normalizeBankReference(
                $this->input('reference_number')
            ),
        ]);
    }
    
    private function normalizeBankReference(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }
    
        $value = trim($value);
    
        return $value === '' ? null : $value;
    }
    public function messages(): array
    {
        return [
            'invoice_id.required' =>
                'يرجى اختيار الفاتورة.',

            'invoice_id.integer' =>
                'معرّف الفاتورة غير صحيح.',

            'subscription_id.required' =>
                'يرجى اختيار الاشتراك.',

            'subscription_id.integer' =>
                'معرّف الاشتراك غير صحيح.',

            'amount.required' =>
                'يرجى إدخال مبلغ الدفعة.',

            'amount.numeric' =>
                'يجب أن يكون مبلغ الدفعة رقمًا صحيحًا أو عشريًا.',

            'amount.gt' =>
                'يجب أن يكون مبلغ الدفعة أكبر من صفر.',

            'amount.decimal' =>
                'يجب ألا يزيد مبلغ الدفعة عن منزلتين عشريتين.',

            'payment_method.required' =>
                'يرجى تحديد طريقة الدفع.',

            'payment_method.in' =>
                'طريقة الدفع غير مدعومة. الطرق المتاحة هي النقد والتحويل البنكي.',

            'transaction_id.required_without' =>
                'يرجى إدخال رقم العملية البنكية أو الرقم المرجعي للتحويل.',

            'transaction_id.string' =>
                'رقم المعاملة يجب أن يكون نصًا.',

            'transaction_id.max' =>
                'رقم المعاملة يجب ألا يتجاوز 255 حرفًا.',

            'reference_number.required_without' =>
                'يرجى إدخال رقم العملية البنكية أو الرقم المرجعي للتحويل.',

            'reference_number.string' =>
                'رقم المرجع يجب أن يكون نصًا.',

            'reference_number.max' =>
                'رقم المرجع يجب ألا يتجاوز 255 حرفًا.',

            'metadata.array' =>
                'بيانات الدفع الإضافية يجب أن تكون بصيغة صحيحة.',

            'cashboxe_id.required_if' =>
                'يرجى اختيار الصندوق عند الدفع النقدي.',

            'cashboxe_id.prohibited_unless' =>
                'لا يجوز تحديد صندوق للدفع إلا عند اختيار الدفع النقدي.',

            'cashboxe_id.integer' =>
                'الصندوق المحدد غير صالح.',

            'cashboxe_id.exists' =>
                'يجب اختيار الصندوق النقدي الرئيسي النشط، ورمزه CASH-MAIN.',
        ];
    }

    public function attributes(): array
    {
        return [
            'invoice_id' => 'الفاتورة',
            'subscription_id' => 'الاشتراك',
            'amount' => 'مبلغ الدفعة',
            'payment_method' => 'طريقة الدفع',
            'cashboxe_id' => 'الصندوق المالي',
            'transaction_id' => 'رقم المعاملة',
            'reference_number' => 'رقم المرجع',
            'metadata' => 'بيانات الدفع الإضافية',
        ];
    }
}
