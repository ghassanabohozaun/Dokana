<?php

namespace App\Services\Dashboard;

use App\Models\StoreTransaction;
use App\Repositories\Dashboard\StoreTransactionRepository;

class StoreTransactionService
{
    protected $storeTransactionRepository;

    public function __construct(StoreTransactionRepository $storeTransactionRepository)
    {
        $this->storeTransactionRepository = $storeTransactionRepository;
    }

    // get all
    public function getAll($request)
    {
        return $this->storeTransactionRepository->getAll(
            $request->keyword,
            $request->store_id,
            $request->type,
            $request->specific_date,
            $request->start_date,
            $request->end_date
        );
    }

    // get one
    public function getOne($id)
    {
        return StoreTransaction::findOrFail($id);
    }

    // create
    public function store(array $data)
    {
        $data['store_id'] = user()->store_id ?? $data['store_id'] ?? null;

        return $this->storeTransactionRepository->create($data);
    }

    // update
    public function update($id, array $data)
    {
        $storeTransaction = $this->getOne($id);

        if (!$storeTransaction) {
            return false;
        }

        $storeTransaction = $this->storeTransactionRepository->update($storeTransaction, $data);
        if (!$storeTransaction) {
            return false;
        }

        return $storeTransaction;
    }

    // destroy
    public function destroy($id)
    {
        $storeTransaction = $this->getOne($id);

        if (!$storeTransaction) {
            return false;
        }

        $storeTransaction = $this->storeTransactionRepository->destroy($storeTransaction);
        if (!$storeTransaction) {
            return false;
        }
        return $storeTransaction;
    }
}
