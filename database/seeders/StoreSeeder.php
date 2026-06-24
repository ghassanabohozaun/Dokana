<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        Store::firstOrCreate(
            [
                'name->en' => 'Main Store',
            ],
            [
                'name' => [
                    'en' => 'Main Store',
                    'ar' => 'الدكانة الرئيسية',
                ],
                'subscription_plan' => 'Basic',
                'status' => 'active',
                'email' => 'main@dokana.com',
                'phone' => '01000000000',
            ],
        );

        // Ensure Cash Box is created for each store using the centralized Service logic
        $storeService = app(\App\Services\Dashboard\StoreService::class);
        $stores = Store::all();
        foreach ($stores as $store) {
            $storeService->createDefaultCashBox($store);
        }
    }
}
