<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Store;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super User (System Admin) - Assigned to Store 1 (Main) for isolation
        $superRole = Role::firstOrCreate(
            ['name->en' => 'Super User'],
            [
                'store_id' => 1, // Private to System Store
                'name' => [
                    'en' => 'Super User',
                    'ar' => 'المستخدم الرئيسي',
                ],
                'description' => 'System Super Administrator',
            ]
        );

        // Assign all permissions to Super Admin
        $allPermissions = Permission::all();
        $superRole->permissions()->sync($allPermissions->pluck('id'));

        // 2. Store Manager - Global (Visible to all stores)
        $adminRole = Role::firstOrCreate(
            ['name->en' => 'Store Manager'],
            [
                'store_id' => null, // Global Role
                'name' => [
                    'en' => 'Store Manager',
                    'ar' => 'مدير الدكانة',
                ],
                'description' => 'Full access to store modules except store management',
            ]
        );

        // Assign all permissions except stores_* to Store Admin
        $storeAdminPermissions = Permission::where('name', 'not like', 'stores_%')->get();
        $adminRole->permissions()->sync($storeAdminPermissions->pluck('id'));

        // 3. Cashier - Global
        $cashierRole = Role::firstOrCreate(
            ['name->en' => 'Cashier'],
            [
                'store_id' => null, // Global Role
                'name' => [
                    'en' => 'Cashier',
                    'ar' => 'كاشير',
                ],
                'description' => 'Has access only to the Debt Notebook (Market)',
            ]
        );

        // Cashier has access to read and create notebook data
        $notebookPermissions = Permission::whereIn('name', ['notebook_read', 'notebook_create'])->get();
        $cashierRole->permissions()->sync($notebookPermissions->pluck('id'));
    }
}
