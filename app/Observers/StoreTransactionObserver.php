<?php

namespace App\Observers;

use App\Models\StoreTransaction;

class StoreTransactionObserver
{
    public function creating(StoreTransaction $transaction): void
    {
        if ($transaction->type === 'debt' && !$transaction->skip_limit_check) {
            $customer = $transaction->customer;
            if ($customer && $customer->max_debt_limit !== null && !$customer->bypass_debt_limit) {
                // Calculate expected balance
                $expectedBalance = $customer->balance + $transaction->amount;
                
                if ($expectedBalance > $customer->max_debt_limit) {
                    throw new \Exception(__('store_transactions.debt_limit_exceeded', [
                        'limit' => $customer->max_debt_limit,
                        'expected' => $expectedBalance
                    ]));
                }
            }
        }
    }

    public function created(StoreTransaction $transaction): void
    {
        $this->recalculateBalance($transaction->customer);
        $this->recalculateBankAccountBalance($transaction->store_bank_account_id);
    }

    public function updated(StoreTransaction $transaction): void
    {
        $this->syncLinkedTransaction($transaction, 'update');
        
        if ($transaction->isDirty('store_customer_id')) {
            $oldCustomer = \App\Models\StoreCustomer::find($transaction->getOriginal('store_customer_id'));
            $this->recalculateBalance($oldCustomer);
        }
        $this->recalculateBalance($transaction->customer);
        
        if ($transaction->isDirty('store_bank_account_id')) {
            $this->recalculateBankAccountBalance($transaction->getOriginal('store_bank_account_id'));
        }
        $this->recalculateBankAccountBalance($transaction->store_bank_account_id);
    }

    public function deleted(StoreTransaction $transaction): void
    {
        $this->syncLinkedTransaction($transaction, 'delete');
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
        $this->syncLinkedTransaction($transaction, 'forceDelete');
        $this->recalculateBalance($transaction->customer);
        $this->recalculateBankAccountBalance($transaction->store_bank_account_id);
    }

    protected function syncLinkedTransaction(StoreTransaction $transaction, $action)
    {
        if ($transaction->linked_transaction_id && !session()->has('syncing_tx_'.$transaction->id)) {
            session()->put('syncing_tx_'.$transaction->linked_transaction_id, true);
            
            $linked = \App\Models\StoreTransaction::find($transaction->linked_transaction_id);
            if ($linked) {
                if ($action === 'update') {
                    $linked->update([
                        'amount' => $transaction->amount,
                        'transaction_date' => $transaction->transaction_date
                    ]);
                } elseif ($action === 'delete') {
                    $linked->delete();
                } elseif ($action === 'forceDelete') {
                    $linked->forceDelete();
                }
            }
            
            session()->forget('syncing_tx_'.$transaction->linked_transaction_id);
        }
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

        $opening = $bankAccount->opening_balance ?? 0;

        $totalPayments = \App\Models\StoreTransaction::where('store_bank_account_id', $bankAccountId)
            ->where('type', 'payment')
            ->sum('amount');
        
        $totalWithdrawals = \App\Models\StoreWithdrawal::where('store_bank_account_id', $bankAccountId)
            ->sum('amount');
            
        $totalAdjustments = \App\Models\StoreBankAccountAdjustment::where('store_bank_account_id', $bankAccountId)
            ->sum('amount');
            
        $bankAccount->updateQuietly(['current_balance' => $opening + $totalPayments + $totalAdjustments - $totalWithdrawals]);
    }
}
