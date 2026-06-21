<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Store;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superRole = Role::where('name->en', 'Super User')->first();
        $adminRole = Role::where('name->en', 'Store Manager')->first();

        // 1. Super Admin
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => [
                    'en' => 'Super User',
                    'ar' => 'المستخدم الرئيسي',
                ],
                'password' => bcrypt('123456'),
                'store_id' => 1,
                'role_id' => $superRole->id,
                'status' => true,
                'mobile' => '0592404940',
            ],
        );

        // 2. Demo Store Admin (Store Manager)
        $demoStore = Store::where('name->en', 'Demo Store')->first();
        if ($demoStore) {
            User::firstOrCreate(
                ['email' => 'demo@admin.com'],
                [
                    'name' => [
                        'en' => 'Store Admin',
                        'ar' => 'مدير المتجر',
                    ],
                    'password' => bcrypt('123456'),
                    'store_id' => $demoStore->id,
                    'role_id' => $adminRole->id,
                    'status' => true,
                    'mobile' => '0599624222',
                ],
            );
        }
    }
}
