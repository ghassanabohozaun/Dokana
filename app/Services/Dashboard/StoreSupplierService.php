<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\StoreSupplierRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class StoreSupplierService
{
    protected $repository;

    public function __construct(StoreSupplierRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($keyword = null, $storeId = null)
    {
        return $this->repository->getAll($keyword, $storeId);
    }

    public function getActiveList($storeId = null)
    {
        return $this->repository->getActiveList($storeId);
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
            $data['status'] = $data['status'] ?? true;
            $supplier = $this->repository->create($data);
            DB::commit();
            return $supplier;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();
        try {
            $data['status'] = $data['status'] ?? true;
            $supplier = $this->repository->update($id, $data);
            DB::commit();
            return $supplier;
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
