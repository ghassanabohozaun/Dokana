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


    }
}
