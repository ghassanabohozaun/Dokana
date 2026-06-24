<?php

namespace App\Repositories\Dashboard;

use App\Models\PaymentEntity;
use App\Traits\Dashboard\HandleAjaxPagination;

class PaymentEntityRepository
{
    use HandleAjaxPagination;

    protected $model;

    public function __construct(PaymentEntity $model)
    {
        $this->model = $model;
    }

    public function getAll($request)
    {
        $query = $this->model->with(['creator'])
            ->filter($request->only(['keyword', 'type', 'status']), ['name', 'type', 'status'])
            ->orderByDesc('id');

        return $this->applyAjaxPagination($request, $query);
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
        $entity = $this->find($id);
        $entity->update($data);
        return $entity;
    }

    public function delete($id)
    {
        $entity = $this->find($id);
        return $entity->delete();
    }
}
