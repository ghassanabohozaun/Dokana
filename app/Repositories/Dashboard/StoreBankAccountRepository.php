<?php

namespace App\Repositories\Dashboard;

use App\Models\StoreBankAccount;
use App\Traits\Dashboard\HandleAjaxPagination;

class StoreBankAccountRepository
{
    use HandleAjaxPagination;

    protected $model;

    public function __construct(StoreBankAccount $model)
    {
        $this->model = $model;
    }

    public function getAll($request)
    {
        $query = $this->model
            ->with(['store', 'creator'])
            ->when($request->store_id, function($query) use ($request) {
                return $query->where('store_id', $request->store_id);
            })
            // Filter by specific columns and translations
            ->filter($request->only(['keyword']), ['bank_name', 'account_holder_name', 'account_number', 'iban'])
            ->orderByDesc('id');

        return $this->applyAjaxPagination($request, $query);
    }

    public function find($id)
    {
        return $this->model->with(['store', 'creator'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $account = $this->find($id);
        $account->update($data);
        return $account;
    }

    public function delete($id)
    {
        $account = $this->find($id);
        return $account->delete();
    }
}
