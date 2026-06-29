<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionPlanRequest extends FormRequest
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
       
        return [
            'plan_name'=> 'required|string|max:100',
            'plan_tier'=> 'required|in:Basic,Standard,Premium,Enterprise',
            'plan_price'=> 'required|numeric|min:0',
            'currency'=> 'required|in:USD,EUR,SAR,GBP,YER',
            'tax_rate'=> 'nullable|numeric|min:0',
            'discount_rate'=> 'nullable|numeric|min:0',
            'discount_amount'=> 'nullable|numeric|min:0',
            'max_users'=> 'required|integer|min:1',
            'max_branches'=> 'required|integer|min:1',
            'storage_space'=> 'required|numeric|min:0',
            'features'=> 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'plan_name.required'=>'اسم الباقة مطلوب.',
            'plan_tier.in'=>'مستوى الباقة يجب أن يكون Basic أو Standard أو Premium أو Enterprise.',
            'currency.in'=>'العملة يجب أن تكون USD أو EUR أو SAR أو GBP.',
            'plan_price.numeric'=>'السعر يجب أن يكون رقمًا.',
            'max_users.integer'=>'عدد المستخدمين يجب أن يكون عدد صحيح.',
            'max_branches.integer'=>'عدد الفروع يجب أن يكون عدد صحيح.',
        ];
    }
    
}
