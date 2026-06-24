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
            $request->end_date,
            $request->store_customer_id,
            $request->store_bank_account_id
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
        if (isset($data['store_customer_id'])) {
            $customer = \App\Models\StoreCustomer::find($data['store_customer_id']);
            if ($customer && $customer->store_id !== null) {
                $data['store_id'] = $customer->store_id;
            }
        }

        if (empty($data['store_id'])) {
            $data['store_id'] = user()->store_id ?? $data['store_id'] ?? null;
        }

        if ($data['type'] === 'debt') {
            $customer = \App\Models\StoreCustomer::find($data['store_customer_id']);
            if ($customer && !$customer->bypass_debt_limit && $customer->debt_age !== null && $customer->debt_age > 10) {
                throw new \Exception(__('store_transactions.debt_age_exceeded_limit', ['days' => $customer->debt_age]) ?? "لا يمكن تسجيل دين جديد لهذا العميل لوجود دين مستحق منذ أكثر من 10 أيام.");
            }
        }

        if (empty($data['description'])) {
            $data['description'] = $data['type'] === 'payment' ? __('store_transactions.payment') : __('store_transactions.debt');
        }

        return $this->storeTransactionRepository->create($data);
    }

    // update
    public function update($id, array $data)
    {
        $storeTransaction = $this->getOne($id);

        if (!$storeTransaction) {
            return false;
        }

        if (isset($data['store_customer_id'])) {
            $customer = \App\Models\StoreCustomer::find($data['store_customer_id']);
            if ($customer && $customer->store_id !== null) {
                $data['store_id'] = $customer->store_id;
            }
        }

        if (empty($data['description'])) {
            $data['description'] = $data['type'] === 'payment' ? __('store_transactions.payment') : __('store_transactions.debt');
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
