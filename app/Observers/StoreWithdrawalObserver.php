<?php

namespace App\Observers;

use App\Models\StoreWithdrawal;
use App\Models\StoreBankAccount;

class StoreWithdrawalObserver
{
    public function created(StoreWithdrawal $withdrawal): void
    {
        $this->applyWithdrawalDelta($withdrawal, 'add');
    }

    public function updated(StoreWithdrawal $withdrawal): void
    {
        $oldBankAccountId = $withdrawal->getOriginal('store_bank_account_id');
        $oldAmount = $withdrawal->getOriginal('amount');

        $oldWithdrawal = new StoreWithdrawal([
            'store_bank_account_id' => $oldBankAccountId,
            'amount' => $oldAmount
        ]);

        $this->applyWithdrawalDelta($oldWithdrawal, 'subtract');
        $this->applyWithdrawalDelta($withdrawal, 'add');
    }

    public function deleted(StoreWithdrawal $withdrawal): void
    {
        $this->applyWithdrawalDelta($withdrawal, 'subtract');
    }

    public function restored(StoreWithdrawal $withdrawal): void
    {
        $this->applyWithdrawalDelta($withdrawal, 'add');
    }

    public function forceDeleted(StoreWithdrawal $withdrawal): void
    {
        $this->applyWithdrawalDelta($withdrawal, 'subtract');
    }

    /**
     * Apply O(1) atomic delta updates to Bank Account current balance.
     */
    protected function applyWithdrawalDelta(StoreWithdrawal $withdrawal, string $mode): void
    {
        if ($withdrawal->store_bank_account_id) {
            $bankAccount = StoreBankAccount::find($withdrawal->store_bank_account_id);
            if ($bankAccount) {
                $amount = (float) $withdrawal->amount;
                // Add withdrawal decreases bank balance, subtract withdrawal increases bank balance
                $change = ($mode === 'add') ? -$amount : $amount;

                if ($change != 0) {
                    if ($change > 0) {
                        $bankAccount->increment('current_balance', abs($change));
                    } else {
                        $bankAccount->decrement('current_balance', abs($change));
                    }
                }
            }
        }
    }
}
