<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = config('global.modules');
        $operations = config('global.crud_operations');

        if ($modules) {
            foreach ($modules as $moduleKey => $moduleLangKey) {
                $moduleOperations = config("global.custom_operations.{$moduleKey}") ?? $operations;
                
                foreach ($moduleOperations as $opKey => $opLangKey) {
                    $permissionName = $moduleKey . '_' . $opKey;
                    $description = "Can {$opKey} {$moduleKey}";
                    Permission::firstOrCreate(
                        ['name' => $permissionName],
                        ['description' => $description]
                    );
                }
            }
        }
    }
}
