<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\StoreCustomerRepository;

class StoreCustomerService
{
    protected $storeCustomerRepository;
    // constructor
    public function __construct(StoreCustomerRepository $storeCustomerRepository)
    {
        $this->storeCustomerRepository = $storeCustomerRepository;
    }

    // get one
    public function getOne($id)
    {
        return $this->storeCustomerRepository->getOne($id);
    }

    // get all
    public function getAll($keyword = null, $store_id = null)
    {
        return $this->storeCustomerRepository->getAll($keyword, $store_id);
    }

    // get metrics
    public function getMetrics($keyword = null, $store_id = null)
    {
        return $this->storeCustomerRepository->getMetrics($keyword, $store_id);
    }

    // create
    public function create($data)
    {
        $storeCustomer = $this->storeCustomerRepository->create($data);
        if (!$storeCustomer) {
            return false;
        }
        return $storeCustomer;
    }

    // update
    public function update($data)
    {
        $storeCustomer = self::getOne($data['id']);

        if (!$storeCustomer) {
            return false;
        }

        $storeCustomer = $this->storeCustomerRepository->update($storeCustomer, $data);
        if (!$storeCustomer) {
            return false;
        }
        return $storeCustomer;
    }

    // destroy
    public function destroy($id)
    {
        $storeCustomer = $this->getOne($id);

        if (!$storeCustomer) {
            return false;
        }

        if ($storeCustomer->is_walk_in) {
            throw new \App\Exceptions\DeleteRestrictionException(__('store_customers.cannot_delete_walk_in') ?? 'لا يمكن حذف زبون المبيعات المباشرة (الطياري).');
        }

        $storeCustomer->checkRestrictiveRelations();

        $storeCustomer = $this->storeCustomerRepository->destroy($storeCustomer);
        if (!$storeCustomer) {
            return false;
        }
        return $storeCustomer;
    }

    // change status
    public function changeStatus($id, $status)
    {
        $storeCustomer = self::getOne($id);
        if (!$storeCustomer) {
            return false;
        }
        $storeCustomer = $this->storeCustomerRepository->changeStatus($storeCustomer, $status);
        if (!$storeCustomer) {
            return false;
        }
        return $storeCustomer;
    }
}
