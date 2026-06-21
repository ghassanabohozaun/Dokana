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

        Store::firstOrCreate(
            [
                'name->en' => 'Demo Store',
            ],
            [
                'name' => [
                    'en' => 'Demo Store',
                    'ar' => 'دكانة تجريبية',
                ],
                'subscription_plan' => 'Premium',
                'status' => 'active',
                'email' => 'demo-store@dokana.com',
                'phone' => '0590000000',
            ],
        );
    }
}
