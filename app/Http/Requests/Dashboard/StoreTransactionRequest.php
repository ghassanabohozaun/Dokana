<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\StoreCustomer;

class StoreTransactionRequest extends FormRequest
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
            'store_customer_id' => [
                'required', 
                'exists:store_customers,id',
                function ($attribute, $value, $fail) {
                    $customer = StoreCustomer::find($value);
                    if ($customer && $customer->status == 0) {
                        $fail(__('store_transactions.customer_is_disabled'));
                    }
                }
            ],
            'type' => 'required|in:debt,payment',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ];

        if (user()->role_id == 1 || user()->id == 1) {
            $rules['store_id'] = ['required', 'exists:stores,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'store_id.required' => __('store_transactions.store_required'),
            'store_customer_id.required' => __('store_transactions.customer_required'),
            'store_customer_id.exists' => __('store_transactions.customer_exists'),
            'type.required' => __('store_transactions.type_required'),
            'type.in' => __('store_transactions.type_in'),
            'amount.required' => __('store_transactions.amount_required'),
            'amount.numeric' => __('store_transactions.amount_numeric'),
            'amount.min' => __('store_transactions.amount_min'),
            'transaction_date.required' => __('store_transactions.date_required'),
            'transaction_date.date' => __('store_transactions.date_invalid'),
        ];
    }
}
