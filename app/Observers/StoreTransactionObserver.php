<?php

namespace App\Observers;

use App\Models\StoreTransaction;

class StoreTransactionObserver
{
    public function created(StoreTransaction $transaction): void
    {
        $this->recalculateBalance($transaction->customer);
    }

    public function updated(StoreTransaction $transaction): void
    {
        $this->recalculateBalance($transaction->customer);
    }

    public function deleted(StoreTransaction $transaction): void
    {
        $this->recalculateBalance($transaction->customer);
    }

    public function restored(StoreTransaction $transaction): void
    {
        $this->recalculateBalance($transaction->customer);
    }

    public function forceDeleted(StoreTransaction $transaction): void
    {
        $this->recalculateBalance($transaction->customer);
    }

    protected function recalculateBalance($customer)
    {
        if (!$customer) return;

        $debts = $customer->transactions()->where('type', 'debt')->sum('amount');
        $payments = $customer->transactions()->where('type', 'payment')->sum('amount');
        
        $customer->updateQuietly(['balance' => $debts - $payments]);
    }
}
