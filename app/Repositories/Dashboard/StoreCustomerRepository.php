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
    public function getAll($keyword = null, $store_id = null)
    {
        $query = $this->model->with(['store'])
            ->withSum(['transactions as total_debts' => function($q) {
                $q->where('type', 'debt');
            }], 'amount')
            ->withSum(['transactions as total_payments' => function($q) {
                $q->where('type', 'payment');
            }], 'amount')
            ->filter(['keyword' => $keyword, 'store_id' => $store_id], ['name', 'phone'], ['store_id'])
            ->orderByDesc('id');

        return $this->applyAjaxPagination(request(), $query);
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
