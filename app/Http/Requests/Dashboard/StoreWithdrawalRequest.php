<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreWithdrawalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'store_bank_account_id' => 'required|exists:store_bank_accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'withdrawal_date' => 'required|date',
            'reason' => 'required|string|max:500',
        ];

        // If user is super admin, require store_id
        if (app(\App\Services\TenantService::class)->isSuperAdmin()) {
            $rules['store_id'] = 'required|exists:stores,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'store_bank_account_id.required' => __('payment_entities.bank_account_required'),
            'amount.required' => __('store_transactions.amount_required'),
            'amount.numeric' => __('store_transactions.amount_numeric'),
            'withdrawal_date.required' => __('store_transactions.date_required'),
            'reason.required' => __('store_withdrawals.reason_required'),
            'store_id.required' => __('stores.store_required'),
        ];
    }

    protected function prepareForValidation()
    {
        if (!$this->has('created_by')) {
            $this->merge(['created_by' => auth()->id()]);
        }
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->store_bank_account_id && $this->amount) {
                $bankAccount = \App\Models\StoreBankAccount::find($this->store_bank_account_id);
                
                if ($bankAccount) {
                    $availableBalance = (float) $bankAccount->current_balance;

                    // If it's an update, add back the old amount of the current withdrawal
                    $withdrawalId = $this->route('store_withdrawal') ?? $this->route('id');
                    if ($withdrawalId) {
                        $withdrawal = \App\Models\StoreWithdrawal::find($withdrawalId);
                        if ($withdrawal && $withdrawal->store_bank_account_id == $this->store_bank_account_id) {
                            $availableBalance += (float) $withdrawal->amount;
                        }
                    }

                    if ((float) $this->amount > $availableBalance) {
                        $validator->errors()->add('amount', __('store_withdrawals.insufficient_balance', ['balance' => number_format($availableBalance, 2)]));
                    }
                }
            }
        });
    }
}
