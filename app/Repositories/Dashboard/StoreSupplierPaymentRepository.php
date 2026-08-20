<?php

namespace App\Repositories\Dashboard;

use App\Models\StoreSupplierPayment;
use App\Traits\Dashboard\HandleAjaxPagination;

class StoreSupplierPaymentRepository
{
    use HandleAjaxPagination;

    protected $model;

    public function __construct(StoreSupplierPayment $model)
    {
        $this->model = $model;
    }

    public function getAll($keyword = null, $storeId = null, $supplierId = null, $invoiceId = null, $storeBankAccountId = null, $specificDate = null)
    {
        $query = $this->model->with(['store', 'supplier', 'invoice', 'bankAccount', 'createdBy'])
            ->filter([
                'keyword' => $keyword,
                'store_id' => $storeId,
                'store_supplier_id' => $supplierId,
                'store_supplier_invoice_id' => $invoiceId,
                'store_bank_account_id' => $storeBankAccountId,
            ], ['notes'], ['store_id', 'store_supplier_id', 'store_supplier_invoice_id', 'store_bank_account_id'])
            ->when($specificDate, function ($q) use ($specificDate) {
                return $q->whereDate('payment_date', $specificDate);
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
        $payment = $this->find($id);
        unset($data['store_id']);
        $payment->update($data);
        return $payment;
    }

    public function delete($id)
    {
        $payment = $this->find($id);
        return $payment->delete();
    }
}
