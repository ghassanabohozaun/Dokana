<?php

namespace App\Observers;

use App\Models\StoreTransaction;

class StoreTransactionObserver
{
    public function created(StoreTransaction $transaction): void
    {
        $this->recalculateBalance($transaction->customer);
        $this->recalculateBankAccountBalance($transaction->store_bank_account_id);
    }

    public function updated(StoreTransaction $transaction): void
    {
        $this->recalculateBalance($transaction->customer);
        if ($transaction->isDirty('store_bank_account_id')) {
            $this->recalculateBankAccountBalance($transaction->getOriginal('store_bank_account_id'));
        }
        $this->recalculateBankAccountBalance($transaction->store_bank_account_id);
    }

    public function deleted(StoreTransaction $transaction): void
    {
        $this->recalculateBalance($transaction->customer);
        $this->recalculateBankAccountBalance($transaction->store_bank_account_id);
    }

    public function restored(StoreTransaction $transaction): void
    {
        $this->recalculateBalance($transaction->customer);
        $this->recalculateBankAccountBalance($transaction->store_bank_account_id);
    }

    public function forceDeleted(StoreTransaction $transaction): void
    {
        $this->recalculateBalance($transaction->customer);
        $this->recalculateBankAccountBalance($transaction->store_bank_account_id);
    }

    protected function recalculateBalance($customer)
    {
        if (!$customer) return;

        $debts = $customer->transactions()->where('type', 'debt')->sum('amount');
        $payments = $customer->transactions()->where('type', 'payment')->sum('amount');
        
        $customer->updateQuietly(['balance' => $debts - $payments]);
    }

    protected function recalculateBankAccountBalance($bankAccountId)
    {
        if (!$bankAccountId) return;
        
        $bankAccount = \App\Models\StoreBankAccount::find($bankAccountId);
        if (!$bankAccount) return;

        $totalPayments = \App\Models\StoreTransaction::where('store_bank_account_id', $bankAccountId)
            ->where('type', 'payment')
            ->sum('amount');
        
        $totalWithdrawals = \App\Models\StoreWithdrawal::where('store_bank_account_id', $bankAccountId)
            ->sum('amount');
            
        $bankAccount->updateQuietly(['current_balance' => $totalPayments - $totalWithdrawals]);
    }
}
