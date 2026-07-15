<?php

namespace App\Repositories\Dashboard;

use App\Models\StoreCustomer;
use App\Traits\Dashboard\HandleAjaxPagination;

class StoreCustomerRepository
{
    use HandleAjaxPagination;

    protected $model;

    public function __construct(StoreCustomer $model)
    {
        $this->model = $model;
    }

    // get one
    public function getOne($id)
    {
        return $this->model->with(['store'])
            ->withSum(['transactions as total_debts' => function($q) {
                $q->where('type', 'debt');
            }], 'amount')
            ->withSum(['transactions as total_payments' => function($q) {
                $q->where('type', 'payment');
            }], 'amount')
            ->find($id);
    }

    // get all
    public function getAll($keyword = null, $store_id = null, $status = null, $sort_by = null)
    {
        $query = $this->model->with(['store'])
            ->withSum(['transactions as total_debts' => function($q) {
                $q->where('type', 'debt');
            }], 'amount')
            ->withSum(['transactions as total_payments' => function($q) {
                $q->where('type', 'payment');
            }], 'amount')
            ->filter(['keyword' => $keyword, 'store_id' => $store_id, 'status' => $status], ['name', 'phone'], ['store_id', 'status']);
            
        if ($sort_by === 'highest_debts') {
            $query->orderBy('total_debts', 'desc')->orderBy('id', 'desc');
        } elseif ($sort_by === 'highest_payments') {
            $query->orderBy('total_payments', 'desc')->orderBy('id', 'desc');
        } elseif ($sort_by === 'oldest_debts') {
            $query->orderByDesc('debt_age')->orderBy('id', 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        return $this->applyAjaxPagination(request(), $query);
    }

    public function getMetrics($keyword = null, $store_id = null, $status = null)
    {
        $query = $this->model->filter(['keyword' => $keyword, 'store_id' => $store_id, 'status' => $status], ['name', 'phone'], ['store_id', 'status']);

        $total_customers_count = (clone $query)->count();

        // Calculate debts and payments by joining store_transactions
        $total_debts = (clone $query)
            ->join('store_transactions', 'store_customers.id', '=', 'store_transactions.store_customer_id')
            ->where('store_transactions.type', 'debt')
            ->sum('store_transactions.amount');

        $total_payments = (clone $query)
            ->join('store_transactions', 'store_customers.id', '=', 'store_transactions.store_customer_id')
            ->where('store_transactions.type', 'payment')
            ->sum('store_transactions.amount');

        $net_balance = $total_payments - $total_debts;

        return [
            'total_customers_count' => $total_customers_count,
            'total_debts' => $total_debts,
            'total_payments' => $total_payments,
            'net_balance' => $net_balance,
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
    public function update($storeCustomer, $data)
    {
        if (isset($data['store_id']) && $data['store_id'] === '') {
            $data['store_id'] = null;
        }
        return $storeCustomer->update($data);
    }

    // destroy
    public function destroy($storeCustomer)
    {
        return $storeCustomer->delete();
    }

    // change status
    public function changeStatus($storeCustomer, $status)
    {
        return $storeCustomer->update([
            'status' => $status,
        ]);
    }
}
