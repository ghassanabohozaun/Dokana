<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class PaymentEntityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Gates will be handled in controller
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.ar' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
            'type' => 'required|in:bank,wallet',
            'status' => 'nullable|in:on,1,0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.ar.required' => __('payment_entities.name_ar_required'),
            'name.en.required' => __('payment_entities.name_en_required'),
            'type.required' => __('payment_entities.type_required'),
        ];
    }
}
