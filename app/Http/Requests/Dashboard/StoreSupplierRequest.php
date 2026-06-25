<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'mobile' => ['required', 'regex:/^[0-9]{10}$/'],
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'nullable|boolean',
        ];

        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $rules['store_id'] = 'required|exists:stores,id';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'mobile.regex' => __('store_suppliers.mobile_invalid'),
        ];
    }
}
