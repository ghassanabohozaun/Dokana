<?php

namespace App\Repositories\Dashboard;

use App\Models\Setting;

class SettingRepository
{
    protected $model;

    public function __construct(Setting $model)
    {
        $this->model = $model;
    }

    /**
     * Get settings for the specified store, or the current logged-in store.
     * If no settings exist, create a default record for them.
     */
    public function getSetting($storeId = null)
    {
        $id = $storeId ?: user()->store_id;

        return $this->model->where('store_id', $id)->firstOrCreate(
            ['store_id' => $id],
            ['site_name' => ['ar' => 'نظام دكانة', 'en' => 'Dokana System']]
        );
    }

    /**
     * Update settings.
     */
    public function updateSettings($setting, $data)
    {
        return $setting->update($data);
    }
}
