<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\StoreCustomer;
use App\Models\StoreBankAccount;
use App\Models\StoreTransaction;

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
                    if ($customer && $customer->status == 0 && request()->input('type') === 'debt') {
                        $fail(__('store_transactions.customer_is_disabled') ?? 'العميل معطل، يُسمح بتسجيل الدفعات فقط.');
                    }
                }
            ],
            'type' => 'required|in:debt,payment',
            'store_bank_account_id' => 'nullable|required_if:type,payment|exists:store_bank_accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ];

        if (user()->role_id == 1 || user()->id == 1) {
            $rules['store_id'] = ['required', 'exists:stores,id'];
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $transactionId = $this->route('store_transaction') ?? $this->route('id') ?? $this->id;
            $transaction = $transactionId ? StoreTransaction::find($transactionId) : null;

            $effectiveStoreId = $transaction ? $transaction->store_id : ($this->store_id ?? user()->store_id);

            if ($transaction && $this->filled('store_id') && $this->store_id != $transaction->store_id) {
                $validator->errors()->add('store_id', 'لا يمكن تغيير الدكانة بعد إنشاء الحركة المالية.');
                return;
            }

            if ($this->store_customer_id && $effectiveStoreId) {
                $customer = StoreCustomer::where('id', $this->store_customer_id)->where('store_id', $effectiveStoreId)->first();
                if (!$customer) {
                    $validator->errors()->add('store_customer_id', 'العميل المحدد لا ينتمي إلى الدكانة المختارة.');
                }
            }

            if ($this->type === 'payment' && $this->store_bank_account_id && $effectiveStoreId) {
                $bankAccount = StoreBankAccount::where('id', $this->store_bank_account_id)->where('store_id', $effectiveStoreId)->first();
                if (!$bankAccount) {
                    $validator->errors()->add('store_bank_account_id', 'الحساب البنكي المحدد لا ينتمي إلى الدكانة المختارة.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'store_id.required' => __('store_transactions.store_required'),
            'store_customer_id.required' => __('store_transactions.customer_required'),
            'store_customer_id.exists' => __('store_transactions.customer_exists'),
            'type.required' => __('store_transactions.type_required'),
            'type.in' => __('store_transactions.type_in'),
            'store_bank_account_id.required_if' => __('bank_accounts.bank_account_required'),
            'amount.required' => __('store_transactions.amount_required'),
            'amount.numeric' => __('store_transactions.amount_numeric'),
            'amount.min' => __('store_transactions.amount_min'),
            'transaction_date.required' => __('store_transactions.date_required'),
            'transaction_date.date' => __('store_transactions.date_invalid'),
        ];
    }
}
