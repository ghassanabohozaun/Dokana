<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\PaymentEntityRepository;

class PaymentEntityService
{
    protected $repository;

    public function __construct(PaymentEntityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($request)
    {
        return $this->repository->getAll($request);
    }

    public function store(array $data)
    {
        // Status comes as string 'active' or 'inactive' from checkbox? 
        // Wait, the user said $table->boolean('status')->default(true);
        // So the checkbox sends 'on' or '1'
        $data['status'] = isset($data['status']) && $data['status'] === 'on' ? 1 : 0;
        
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        $data['status'] = isset($data['status']) && $data['status'] === 'on' ? 1 : 0;
        
        return $this->repository->update($id, $data);
    }

    public function changeStatus($id, $status)
    {
        return $this->repository->update($id, ['status' => $status]);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
