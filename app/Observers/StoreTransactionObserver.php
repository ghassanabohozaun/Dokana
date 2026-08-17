<?php

namespace App\Observers;

use App\Models\StoreTransaction;
use App\Models\StoreCustomer;
use App\Models\StoreBankAccount;

class StoreTransactionObserver
{
    /**
     * Prevent infinite recursion during linked transaction updates.
     */
    protected static array $syncing = [];

    public function creating(StoreTransaction $transaction): void
    {
        if ($transaction->type === 'debt' && !$transaction->skip_limit_check) {
            $customer = $transaction->customer;
            if ($customer && $customer->max_debt_limit !== null && !$customer->bypass_debt_limit) {
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
        $this->applyTransactionDelta($transaction, 'add');
    }

    public function updated(StoreTransaction $transaction): void
    {
        $this->syncLinkedTransaction($transaction, 'update');

        // Revert old values
        $oldCustomerId = $transaction->getOriginal('store_customer_id');
        $oldBankAccountId = $transaction->getOriginal('store_bank_account_id');
        $oldType = $transaction->getOriginal('type');
        $oldAmount = $transaction->getOriginal('amount');

        // Create a dummy representation of old transaction to subtract its impact
        $oldTx = new StoreTransaction([
            'store_customer_id' => $oldCustomerId,
            'store_bank_account_id' => $oldBankAccountId,
            'type' => $oldType,
            'amount' => $oldAmount
        ]);

        $this->applyTransactionDelta($oldTx, 'subtract');
        $this->applyTransactionDelta($transaction, 'add');
    }

    public function deleted(StoreTransaction $transaction): void
    {
        $this->syncLinkedTransaction($transaction, 'delete');
        $this->applyTransactionDelta($transaction, 'subtract');
    }

    public function restored(StoreTransaction $transaction): void
    {
        $this->applyTransactionDelta($transaction, 'add');
    }

    public function forceDeleted(StoreTransaction $transaction): void
    {
        $this->syncLinkedTransaction($transaction, 'forceDelete');
        $this->applyTransactionDelta($transaction, 'subtract');
    }

    /**
     * Apply O(1) atomic delta updates to Customer balance and Bank Account current balance.
     */
    protected function applyTransactionDelta(StoreTransaction $transaction, string $mode): void
    {
        // 1. Customer balance adjustment
        if ($transaction->store_customer_id) {
            $customer = StoreCustomer::find($transaction->store_customer_id);
            if ($customer) {
                // For debt: add increases balance, subtract decreases balance
                // For payment: add decreases balance, subtract increases balance
                $amount = (float) $transaction->amount;
                $change = ($transaction->type === 'debt') ? $amount : -$amount;
                if ($mode === 'subtract') {
                    $change = -$change;
                }

                if ($change != 0) {
                    if ($change > 0) {
                        $customer->increment('balance', abs($change));
                    } else {
                        $customer->decrement('balance', abs($change));
                    }
                }
            }
        }

        // 2. Bank Account balance adjustment (only for payments)
        if ($transaction->store_bank_account_id && $transaction->type === 'payment') {
            $bankAccount = StoreBankAccount::find($transaction->store_bank_account_id);
            if ($bankAccount) {
                $amount = (float) $transaction->amount;
                // Add payment increases bank balance, subtract payment decreases bank balance
                $change = ($mode === 'add') ? $amount : -$amount;

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

    /**
     * Sync linked transaction safely without session dependencies or recursion bugs.
     */
    protected function syncLinkedTransaction(StoreTransaction $transaction, string $action): void
    {
        if (!$transaction->linked_transaction_id) {
            return;
        }

        $linkedId = $transaction->linked_transaction_id;

        if (isset(static::$syncing[$transaction->id]) || isset(static::$syncing[$linkedId])) {
            return;
        }

        static::$syncing[$transaction->id] = true;
        static::$syncing[$linkedId] = true;

        try {
            $linked = StoreTransaction::find($linkedId);
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
        } finally {
            unset(static::$syncing[$transaction->id], static::$syncing[$linkedId]);
        }
    }
}
