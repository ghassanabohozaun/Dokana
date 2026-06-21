<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\StoreRepository;
use App\Utils\ImageManagerUtils;
use App\Models\Setting;
use App\Models\Store;
use Illuminate\Support\Facades\File;

class StoreService
{
    protected $repository, $imageManagerUtils;

    public function __construct(StoreRepository $repository, ImageManagerUtils $imageManagerUtils)
    {
        $this->repository = $repository;
        $this->imageManagerUtils = $imageManagerUtils;
    }

    public function getAll($request)
    {
        return $this->repository->getAll($request);
    }

    public function getActiveStoresForDropdown()
    {
        return Store::active()->orderByDesc('id')->get();
    }

    public function store(array $data)
    {
        if (isset($data['logo'])) {
            $data['logo'] = $this->imageManagerUtils->uploadSingleImage('', $data['logo'], 'stores');
        }
        
        $store = $this->repository->create($data);

        if ($store) {
            $this->syncStoreSettings($store);
        }

        return $store;
    }

    public function update($id, array $data)
    {
        $store = $this->repository->find($id);
        if (!$store) {
            return false;
        }

        if (isset($data['logo'])) {
            if ($store->logo) {
                $this->imageManagerUtils->removeImageFromLocal($store->logo, 'stores');
            }
            $data['logo'] = $this->imageManagerUtils->uploadSingleImage('', $data['logo'], 'stores');
        } elseif (isset($data['delete_logo']) && $data['delete_logo'] == 1) {
            if ($store->logo) {
                $this->imageManagerUtils->removeImageFromLocal($store->logo, 'stores');
            }
            $data['logo'] = null;
        }

        $updatedStore = $this->repository->update($id, $data);
        
        if ($updatedStore) {
            $this->syncStoreSettings($updatedStore);
        }

        return $updatedStore;
    }

    /**
     * Synchronize store data with its settings.
     * Creates settings if they don't exist, or updates them if they do.
     */
    protected function syncStoreSettings($store)
    {
        $settingData = [
            'site_name' => $store->getTranslations('name'),
            'email'     => $store->email,
            'phone'     => $store->phone,
            'address'   => [
                'ar' => $store->address,
                'en' => $store->address,
            ],
        ];

        // Handle Logo Synchronization
        if ($store->logo) {
            $sourcePath = public_path('uploads/stores/' . $store->logo);
            $destPath = public_path('uploads/settings/' . $store->logo);
            
            if (File::exists($sourcePath)) {
                if (!File::isDirectory(public_path('uploads/settings'))) {
                    File::makeDirectory(public_path('uploads/settings'), 0755, true);
                }
                File::copy($sourcePath, $destPath);
                $settingData['logo'] = $store->logo;
                $settingData['favicon'] = $store->logo;
            }
        }

        // Use updateOrCreate to handle both new and existing stores
        return Setting::updateOrCreate(
            ['store_id' => $store->id],
            $settingData
        );
    }

    public function delete($id)
    {
        $store = $this->repository->find($id);
        if (!$store) {
            return false;
        }

        // Check for restrictive relations (e.g., users, bank accounts)
        $store->checkRestrictiveRelations();

        if ($store->logo) {
            $this->imageManagerUtils->removeImageFromLocal($store->logo, 'stores');
        }
        return $this->repository->delete($id);
    }

    public function updateStatus($id, $status)
    {
        $store = $this->repository->find($id);
        if (!$store) {
            return false;
        }

        $newStatus = ($status == 1) ? 'active' : 'inactive';
        return $this->repository->updateStatus($id, $newStatus);
    }

    public function autocomplete($searchValue)
    {
        return $this->repository->autocomplete($searchValue);
    }
}
