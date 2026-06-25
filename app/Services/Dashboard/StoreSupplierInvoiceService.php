<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\StoreSupplierInvoiceRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class StoreSupplierInvoiceService
{
    protected $repository;

    public function __construct(StoreSupplierInvoiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($keyword = null, $storeId = null, $supplierId = null)
    {
        return $this->repository->getAll($keyword, $storeId, $supplierId);
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
            $paidAmount = $data['paid_amount'] ?? 0;
            $data['remaining_amount'] = $data['total_amount'] - $paidAmount;
            $data['paid_amount'] = $paidAmount;
            
            if ($paidAmount >= $data['total_amount'] && $data['total_amount'] > 0) {
                $data['status'] = 'paid';
            } elseif ($paidAmount > 0) {
                $data['status'] = 'partially_paid';
            } else {
                $data['status'] = 'unpaid';
            }

            $invoice = $this->repository->create($data);
            DB::commit();
            return $invoice;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();
        try {
            // Note: In a real scenario, updating total_amount might require recalculating status.
            // Since payments handle status dynamically via observer, we should be careful here.
            $invoice = $this->repository->find($id);
            
            $data['remaining_amount'] = $data['total_amount'] - $invoice->paid_amount;
            
            if ($invoice->paid_amount >= $data['total_amount'] && $data['total_amount'] > 0) {
                $data['status'] = 'paid';
            } elseif ($invoice->paid_amount > 0) {
                $data['status'] = 'partially_paid';
            } else {
                $data['status'] = 'unpaid';
            }

            $invoice = $this->repository->update($id, $data);
            DB::commit();
            return $invoice;
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
