<?php

namespace App\Repositories\Dashboard;

use App\Models\StoreSupplier;
use App\Traits\Dashboard\HandleAjaxPagination;

class StoreSupplierRepository
{
    use HandleAjaxPagination;

    protected $model;

    public function __construct(StoreSupplier $model)
    {
        $this->model = $model;
    }

    public function getAll($keyword = null, $storeId = null)
    {
        $query = $this->model->with(['store', 'createdBy'])
            ->withSum('invoices', 'total_amount')
            ->withSum('invoices', 'paid_amount')
            ->withSum('invoices', 'remaining_amount')
            ->filter([
                'keyword' => $keyword,
                'store_id' => $storeId,
            ], ['name', 'mobile', 'bank_name', 'account_number'], ['store_id'])
            ->orderByDesc('id');

        return $this->applyAjaxPagination(request(), $query);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $supplier = $this->find($id);
        $supplier->update($data);
        return $supplier;
    }

    public function delete($id)
    {
        $supplier = $this->find($id);
        return $supplier->delete();
    }
    
    public function getActiveList($storeId = null)
    {
        return $this->model->where('status', true)
            ->when($storeId, function ($q) use ($storeId) {
                return $q->where('store_id', $storeId);
            })
            ->get();
    }
}
