<?php

namespace App\Repositories\Dashboard;

use App\Models\Store;
use App\Traits\Dashboard\HandleAjaxPagination;
class StoreRepository
{
    use HandleAjaxPagination;

    protected $model;

    public function __construct(Store $model)
    {
        $this->model = $model;
    }

    public function getAll($request)
    {
        $query = $this->model
            ->with('creator')
            ->when($request->store_id, function($query) use ($request) {
                return $query->where('id', $request->store_id);
            })
            ->filter($request->only(['keyword']), ['name', 'email', 'phone'])
            ->orderByDesc('id');

        return $this->applyAjaxPagination($request, $query);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $store = $this->find($id);
        $store->update($data);
        return $store;
    }

    public function delete($id)
    {
        $store = $this->find($id);
        return $store->delete();
    }

    public function updateStatus($id, $status)
    {
        $store = $this->find($id);
        $store->status = $status;
        $store->save();
        return $store;
    }

    public function autocomplete($searchValue)
    {
        $query = $this->model->query()->where('status', 'active');

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name->en', 'like', '%' . $searchValue . '%')
                  ->orWhere('name->ar', 'like', '%' . $searchValue . '%');
            });
        }

        $limit = empty($searchValue) ? 5 : 30;
        $paginator = $query->orderByDesc('id')->paginate($limit);

        $results = $paginator->map(function ($store) {
            return [
                'id'       => $store->id,
                'text'     => $store->name,
            ];
        });

        return [
            'results' => $results,
            'total_count' => $paginator->total()
        ];
    }
}
