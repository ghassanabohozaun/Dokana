<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Dashboard\CanBeDeleted;
use App\Contracts\MustBelongToStore;
use App\Traits\BelongsToStore;
use App\Traits\Dashboard\Filterable;

class StoreCustomer extends Model implements MustBelongToStore
{
    use HasFactory, CanBeDeleted, BelongsToStore, Filterable;

    protected $fillable = ['store_id', 'name', 'phone', 'balance', 'status', 'bypass_debt_limit'];

    protected $casts = [
        'bypass_debt_limit' => 'boolean',
    ];

    protected $appends = ['calculated_balance', 'debt_age'];

    public function scopeActive($query)
    {
        return $query->whereStatus(1);
    }

    public function scopeInactive($query)
    {
        return $query->whereStatus(0);
    }

    public function transactions()
    {
        return $this->hasMany(StoreTransaction::class)->latest();
    }

    protected $restrictiveRelations = [
        'transactions' => 'store_customers.customer_has_transactions',
    ];

    public function getCalculatedBalanceAttribute()
    {
        return ($this->total_debts ?? 0) - ($this->total_payments ?? 0);
    }

    public function getDebtAgeAttribute()
    {
        if ($this->balance <= 0) {
            return null;
        }

        if ($this->relationLoaded('transactions')) {
            $totalPayments = (float) $this->transactions->where('type', 'payment')->sum('amount');
            $debts = $this->transactions
                ->where('type', 'debt')
                ->sortBy(function($t) {
                    $dateStr = $t->transaction_date ? $t->transaction_date->format('Y-m-d H:i:s') : '0000-00-00 00:00:00';
                    return $dateStr . '_' . sprintf('%010d', $t->id);
                });
        } else {
            $totalPayments = (float) $this->transactions()->where('type', 'payment')->sum('amount');
            $debts = $this->transactions()
                ->where('type', 'debt')
                ->reorder()
                ->orderBy('transaction_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        $remainingPayments = $totalPayments;

        foreach ($debts as $debt) {
            $amount = (float) $debt->amount;
            if ($remainingPayments >= $amount) {
                $remainingPayments -= $amount;
            } else {
                $transactionDate = $debt->transaction_date;
                if ($transactionDate) {
                    return (int) now()->diffInDays($transactionDate, true);
                }
                break;
            }
        }

        return null;
    }
}
