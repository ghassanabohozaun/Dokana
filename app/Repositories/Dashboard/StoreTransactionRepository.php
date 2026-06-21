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
    public function getAll($keyword = null, $store_id = null, $type = null, $specific_date = null, $start_date = null, $end_date = null)
    {
        $query = $this->model->with(['store', 'customer'])
            ->filter(['keyword' => $keyword, 'store_id' => $store_id, 'type' => $type], [], ['store_id', 'type'])
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
        return $storeTransaction->delete();
    }
}
