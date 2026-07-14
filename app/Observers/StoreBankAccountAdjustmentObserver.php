<?php

namespace App\Observers;

use App\Models\StoreBankAccountAdjustment;

class StoreBankAccountAdjustmentObserver
{
    public function created(StoreBankAccountAdjustment $adjustment): void
    {
        $this->recalculateBankAccountBalance($adjustment->store_bank_account_id);
    }

    public function updated(StoreBankAccountAdjustment $adjustment): void
    {
        if ($adjustment->isDirty('store_bank_account_id')) {
            $this->recalculateBankAccountBalance($adjustment->getOriginal('store_bank_account_id'));
        }
        $this->recalculateBankAccountBalance($adjustment->store_bank_account_id);
    }

    public function deleted(StoreBankAccountAdjustment $adjustment): void
    {
        $this->recalculateBankAccountBalance($adjustment->store_bank_account_id);
    }

    public function restored(StoreBankAccountAdjustment $adjustment): void
    {
        $this->recalculateBankAccountBalance($adjustment->store_bank_account_id);
    }

    public function forceDeleted(StoreBankAccountAdjustment $adjustment): void
    {
        $this->recalculateBankAccountBalance($adjustment->store_bank_account_id);
    }

    protected function recalculateBankAccountBalance($bankAccountId)
    {
        if (!$bankAccountId) return;
        
        $bankAccount = \App\Models\StoreBankAccount::find($bankAccountId);
        if (!$bankAccount) return;

        $opening = $bankAccount->opening_balance ?? 0;
        
        $totalPayments = \App\Models\StoreTransaction::where('store_bank_account_id', $bankAccountId)
            ->where('type', 'payment')
            ->sum('amount');
        
        $totalWithdrawals = \App\Models\StoreWithdrawal::where('store_bank_account_id', $bankAccountId)
            ->sum('amount');
            
        $totalAdjustments = \App\Models\StoreBankAccountAdjustment::where('store_bank_account_id', $bankAccountId)
            ->sum('amount');

        // Note: Check if supplier payments need to be included. Currently the StoreTransactionObserver only sums StoreWithdrawal and StoreTransaction
        // Wait, does StoreTransactionObserver sum StoreSupplierPayment?
        // Let's rely on the same formula.
        
        $bankAccount->updateQuietly(['current_balance' => $opening + $totalPayments + $totalAdjustments - $totalWithdrawals]);
    }
}
