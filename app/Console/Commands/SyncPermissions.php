<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs missing permissions from config/global.php to the database and assigns them to the Super Admin (Role ID 1).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting permissions synchronization...");

        $modules = array_keys(config('global.modules', []));
        $crudOps = array_keys(config('global.crud_operations', []));
        $customOps = config('global.custom_operations', []);

        $expectedPermissions = [];

        // 1. Generate standard CRUD permissions for all modules
        foreach ($modules as $module) {
            foreach ($crudOps as $op) {
                $expectedPermissions[] = "{$module}_{$op}";
            }
        }

        // 2. Generate Custom permissions defined in config
        foreach ($customOps as $module => $ops) {
            foreach (array_keys($ops) as $op) {
                $expectedPermissions[] = "{$module}_{$op}";
            }
        }

        // 3. Fetch existing permissions from the database
        $existingPermissions = DB::table('permissions')->pluck('name', 'id')->toArray();
        $existingNames = array_values($existingPermissions);

        $newCount = 0;
        $now = Carbon::now();

        $this->getOutput()->progressStart(count($expectedPermissions));

        foreach ($expectedPermissions as $perm) {
            $permId = null;

            // If permission doesn't exist, insert it
            if (!in_array($perm, $existingNames)) {
                $permId = DB::table('permissions')->insertGetId([
                    'name' => $perm,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $this->line("\n[+] Created new permission: <comment>{$perm}</comment>");
                $newCount++;
            } else {
                // Get the ID of the existing permission
                $permId = array_search($perm, $existingPermissions);
            }

            // 4. Ensure the Super Admin (Role ID: 1) has this permission
            if ($permId) {
                $roleHasIt = DB::table('role_permissions')
                    ->where('role_id', 1)
                    ->where('permission_id', $permId)
                    ->exists();

                if (!$roleHasIt) {
                    DB::table('role_permissions')->insert([
                        'role_id' => 1,
                        'permission_id' => $permId
                    ]);
                    $this->line("\n[>] Assigned permission to Super Admin: <comment>{$perm}</comment>");
                }
            }

            $this->getOutput()->progressAdvance();
        }

        $this->getOutput()->progressFinish();

        $this->info("✔ Synchronization completed successfully!");
        $this->info("Total new permissions added: {$newCount}");

        return Command::SUCCESS;
    }
}
