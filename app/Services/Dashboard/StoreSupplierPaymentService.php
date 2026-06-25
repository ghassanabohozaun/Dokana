<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\StoreSupplierPaymentRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class StoreSupplierPaymentService
{
    protected $repository;

    public function __construct(StoreSupplierPaymentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($keyword = null, $storeId = null, $supplierId = null, $invoiceId = null, $storeBankAccountId = null, $specificDate = null)
    {
        return $this->repository->getAll($keyword, $storeId, $supplierId, $invoiceId, $storeBankAccountId, $specificDate);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = auth()->id();
            $payment = $this->repository->create($data);
            DB::commit();
            return $payment;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();
        try {
            $payment = $this->repository->update($id, $data);
            DB::commit();
            return $payment;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $result = $this->repository->delete($id);
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
