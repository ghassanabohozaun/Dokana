<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'regex:/^[0-9]{10}$/'],
            'bypass_debt_limit' => ['nullable', 'boolean'],
        ];

        if (user()->store_id == 1) {
            $rules['store_id'] = ['required', 'exists:stores,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => __('store_customers.name_required'),
            'phone.regex' => __('store_customers.phone_invalid'),
        ];
    }
}
