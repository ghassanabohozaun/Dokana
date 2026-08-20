<?php

namespace App\Repositories\Dashboard;

use App\Models\StoreSupplierInvoice;
use App\Traits\Dashboard\HandleAjaxPagination;

class StoreSupplierInvoiceRepository
{
    use HandleAjaxPagination;

    protected $model;

    public function __construct(StoreSupplierInvoice $model)
    {
        $this->model = $model;
    }

    public function getAll($keyword = null, $storeId = null, $supplierId = null)
    {
        $query = $this->model->with(['store', 'supplier', 'createdBy'])
            ->filter([
                'keyword' => $keyword,
                'store_id' => $storeId,
                'store_supplier_id' => $supplierId,
            ], ['invoice_number'], ['store_id', 'store_supplier_id'])
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
        $invoice = $this->find($id);
        unset($data['store_id']);
        $invoice->update($data);
        return $invoice;
    }

    public function delete($id)
    {
        $invoice = $this->find($id);
        return $invoice->delete();
    }
}
