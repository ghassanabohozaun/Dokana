<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\StoreBankAccountRepository;

class StoreBankAccountService
{
    protected $repository;

    public function __construct(StoreBankAccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($request)
    {
        return $this->repository->getAll($request);
    }

    public function store(array $data)
    {
        // Handle is_default logic (Checkbox sends 'on' or is missing)
        $data['is_default'] = isset($data['is_default']) && $data['is_default'] === 'on' ? 1 : 0;
        
        // Ensure store_id is set for regular users
        if (!isset($data['store_id'])) {
            $data['store_id'] = user()->store_id;
        }

        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        // Handle is_default logic
        $data['is_default'] = isset($data['is_default']) && $data['is_default'] === 'on' ? 1 : 0;
        
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
