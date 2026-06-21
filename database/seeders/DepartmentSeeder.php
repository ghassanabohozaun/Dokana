<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $store = Store::where('id', 1)->first();

        $departments = [
            [
                'name' => [
                    'ar' => 'الإدارة',
                    'en' => 'Managment',
                ],
                'store_id' => $store->id,
            ],

            [
                'name' => [
                    'ar' => 'قسم المالية',
                    'en' => 'Finance department',
                ],
                'store_id' => $store->id,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
