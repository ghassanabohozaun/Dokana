<?php

namespace App\Repositories\Dashboard;

use App\Models\StoreWithdrawal;
use App\Traits\Dashboard\HandleAjaxPagination;

class StoreWithdrawalRepository
{
    use HandleAjaxPagination;

    protected $model;

    public function __construct(StoreWithdrawal $model)
    {
        $this->model = $model;
    }

    public function getAll($keyword = null, $storeId = null, $storeBankAccountId = null, $specificDate = null)
    {
        $query = $this->model->with(['store', 'bankAccount', 'creator'])
            ->when($storeId, function ($q) use ($storeId) {
                return $q->where('store_id', $storeId);
            })
            ->when($storeBankAccountId, function ($q) use ($storeBankAccountId) {
                if ($storeBankAccountId === 'cash') {
                    return $q->whereNull('store_bank_account_id');
                }
                return $q->where('store_bank_account_id', $storeBankAccountId);
            })
            ->when($specificDate, function ($q) use ($specificDate) {
                return $q->whereDate('withdrawal_date', $specificDate);
            })
            ->when($keyword, function ($q) use ($keyword) {
                return $q->where('reason', 'like', "%{$keyword}%");
            })
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
        $withdrawal = $this->find($id);
        $withdrawal->update($data);
        return $withdrawal;
    }

    public function delete($id)
    {
        $withdrawal = $this->find($id);
        return $withdrawal->forceDelete();
    }
}
