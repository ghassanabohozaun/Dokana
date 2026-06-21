<?php

namespace App\Repositories\Dashboard;

use App\Models\Department;
use App\Traits\Dashboard\HandleAjaxPagination;

class DepartmentRepository
{
    use HandleAjaxPagination;

    protected $model;

    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    // get one
    public function getOne($id)
    {
        return $this->model->find($id);
    }

    // get all
    public function getAll($keyword = null, $store_id = null)
    {
        $query = $this->model->with(['store', 'creator'])
            ->filter(['keyword' => $keyword, 'store_id' => $store_id], ['name'], ['store_id'])
            ->orderByDesc('id');

        return $this->applyAjaxPagination(request(), $query);
    }

    // get active all
    public function getActiveAll()
    {
        return $this->model->active()
            ->get();
    }

    // create
    public function create($data)
    {
        if (isset($data['store_id']) && $data['store_id'] === '') {
            $data['store_id'] = null;
        }
        return $this->model->create($data);
    }

    // update
    public function update($department, $data)
    {
        if (isset($data['store_id']) && $data['store_id'] === '') {
            $data['store_id'] = null;
        }
        return $department->update($data);
    }

    // destroy
    public function destroy($department)
    {
        return $department->forceDelete();
    }

    // change status
    public function changeStatus($department, $status)
    {
        return $department->update([
            'status' => $status,
        ]);
    }
}
