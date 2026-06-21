<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\DepartmentRepository;

class DepartmentService
{
    protected $departmentRepository;
    // constructor
    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    // get one
    public function getOne($id)
    {
        return $this->departmentRepository->getOne($id);
    }

    // get all
    public function getAll($keyword = null, $store_id = null)
    {
        return $this->departmentRepository->getAll($keyword, $store_id);
    }

    // get active all
    public function getActiveAll()
    {
        return $this->departmentRepository->getActiveAll();
    }

    // create
    public function create($data)
    {
        $department = $this->departmentRepository->create($data);
        if (!$department) {
            return false;
        }
        return $department;
    }

    // update
    public function update($data)
    {
        $department = self::getOne($data['id']);

        if (!$department) {
            return false;
        }

        $department = $this->departmentRepository->update($department, $data);
        if (!$department) {
            return false;
        }
        return $department;
    }

    // destroy
    public function destroy($id)
    {
        $department = $this->getOne($id);

        if (!$department) {
            return false;
        }

        $department = $this->departmentRepository->destroy($department);
        if (!$department) {
            return false;
        }
        return $department;
    }

    // change status
    public function changeStatus($id, $status)
    {
        $department = self::getOne($id);
        if (!$department) {
            return false;
        }
        $department = $this->departmentRepository->changeStatus($department, $status);
        if (!$department) {
            return false;
        }
        return $department;
    }
}
