<?php

namespace App\Observers;

use App\Models\StoreWithdrawal;
use App\Models\StoreBankAccount;
use App\Models\StoreTransaction;

class StoreWithdrawalObserver
{
    public function created(StoreWithdrawal $withdrawal): void
    {
        $this->recalculateBankAccountBalance($withdrawal->store_bank_account_id);
    }

    public function updated(StoreWithdrawal $withdrawal): void
    {
        if ($withdrawal->isDirty('store_bank_account_id')) {
            $this->recalculateBankAccountBalance($withdrawal->getOriginal('store_bank_account_id'));
        }
        $this->recalculateBankAccountBalance($withdrawal->store_bank_account_id);
    }

    public function deleted(StoreWithdrawal $withdrawal): void
    {
        $this->recalculateBankAccountBalance($withdrawal->store_bank_account_id);
    }

    public function restored(StoreWithdrawal $withdrawal): void
    {
        $this->recalculateBankAccountBalance($withdrawal->store_bank_account_id);
    }

    public function forceDeleted(StoreWithdrawal $withdrawal): void
    {
        $this->recalculateBankAccountBalance($withdrawal->store_bank_account_id);
    }

    protected function recalculateBankAccountBalance($bankAccountId)
    {
        if (!$bankAccountId) return;
        
        $bankAccount = StoreBankAccount::find($bankAccountId);
        if (!$bankAccount) return;

        $totalPayments = StoreTransaction::where('store_bank_account_id', $bankAccountId)
            ->where('type', 'payment')
            ->sum('amount');
        
        $totalWithdrawals = StoreWithdrawal::where('store_bank_account_id', $bankAccountId)
            ->sum('amount');
            
        $bankAccount->updateQuietly(['current_balance' => $totalPayments - $totalWithdrawals]);
    }
}
