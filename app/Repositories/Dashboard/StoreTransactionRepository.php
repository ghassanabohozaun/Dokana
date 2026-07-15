<?php

namespace App\Repositories\Dashboard;

use App\Models\StoreTransaction;
use App\Traits\Dashboard\HandleAjaxPagination;

class StoreTransactionRepository
{
    use HandleAjaxPagination;

    protected $model;

    public function __construct(StoreTransaction $model)
    {
        $this->model = $model;
    }

    // get one
    public function getOne($id)
    {
        return $this->model->find($id);
    }

    // get all
    public function getAll($keyword = null, $store_id = null, $type = null, $specific_date = null, $start_date = null, $end_date = null, $store_customer_id = null, $store_bank_account_id = null)
    {
        $query = $this->model->with(['store', 'customer'])
            ->filter(['keyword' => $keyword, 'store_id' => $store_id, 'type' => $type, 'store_customer_id' => $store_customer_id, 'store_bank_account_id' => $store_bank_account_id], [], ['store_id', 'type', 'store_customer_id', 'store_bank_account_id'])
            ->orderByDesc('id');

        // Apply general text search across customer name if needed
        if ($keyword) {
            $query->whereHas('customer', function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('phone', 'like', '%' . $keyword . '%');
            });
        }

        if ($specific_date) {
            $query->whereDate('transaction_date', '=', $specific_date);
        }

        if ($start_date) {
            $query->whereDate('transaction_date', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('transaction_date', '<=', $end_date);
        }

        return $this->applyAjaxPagination(request(), $query);
    }

    public function getMetrics($keyword = null, $store_id = null, $type = null, $specific_date = null, $start_date = null, $end_date = null, $store_customer_id = null, $store_bank_account_id = null)
    {
        $query = $this->model->filter(['keyword' => $keyword, 'store_id' => $store_id, 'type' => $type, 'store_customer_id' => $store_customer_id, 'store_bank_account_id' => $store_bank_account_id], [], ['store_id', 'type', 'store_customer_id', 'store_bank_account_id']);

        if ($keyword) {
            $query->whereHas('customer', function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('phone', 'like', '%' . $keyword . '%');
            });
        }

        if ($specific_date) {
            $query->whereDate('transaction_date', '=', $specific_date);
        }

        if ($start_date) {
            $query->whereDate('transaction_date', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('transaction_date', '<=', $end_date);
        }

        $total_debts = (clone $query)->where('type', 'debt')->sum('amount');
        $total_payments = (clone $query)->where('type', 'payment')->sum('amount');
        $net_balance = $total_payments - $total_debts;
        $total_transactions_count = (clone $query)->count();

        return [
            'total_debts' => $total_debts,
            'total_payments' => $total_payments,
            'net_balance' => $net_balance,
            'total_transactions_count' => $total_transactions_count,
        ];
    }

    // create
    public function create($data)
    {
        if (isset($data['store_id']) && $data['store_id'] === '') {
            $data['store_id'] = null;
        }
        return $this->model->create($data);
    }

    // update
    public function update($storeTransaction, $data)
    {
        if (isset($data['store_id']) && $data['store_id'] === '') {
            $data['store_id'] = null;
        }
        return $storeTransaction->update($data);
    }

    // destroy
    public function destroy($storeTransaction)
    {
        return $storeTransaction->forceDelete();
    }
}
