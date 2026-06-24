<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\StoreWithdrawalRepository;

class StoreWithdrawalService
{
    protected $repository;

    public function __construct(StoreWithdrawalRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($request)
    {
        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $storeId = $request->store_id ?? null;
        } else {
            $storeId = user()->store_id;
        }

        return $this->repository->getAll(
            $request->keyword, 
            $storeId,
            $request->store_bank_account_id,
            $request->specific_date
        );
    }

    public function store(array $data)
    {
        if (!(user()->store_id == 1 || user()->role_id == 1 || user()->id == 1)) {
            $data['store_id'] = user()->store_id;
        }
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        if (!(user()->store_id == 1 || user()->role_id == 1 || user()->id == 1)) {
            $data['store_id'] = user()->store_id;
        }
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
