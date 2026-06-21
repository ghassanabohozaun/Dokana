<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:test {--type=system}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger a generic test notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        if ($type === 'contract') {
            $contractId = \App\Models\Contract::first()->id ?? 1;
            notifyAdmins(
                'notifications.contract_expiring_title',
                'notifications.contract_expiring_msg',
                ['contract_no' => 'CON-100' . rand(1, 99), 'property' => 'Al-Yasmeen Villa', 'days' => rand(1, 10)],
                'contracts',
                'dashboard.contracts.edit', // Use route name
                'warning',
                'fas fa-file-contract',
                ['contract' => $contractId]
            );
        } elseif ($type === 'financial') {
            $chequeId = \App\Models\Cheque::first()->id ?? 1;
            notifyAdmins(
                'notifications.cheque_due_title',
                'notifications.cheque_due_msg',
                ['cheque_no' => 'CHQ-9988', 'amount' => '5,000 SAR', 'days' => rand(1, 3)],
                'financial',
                'dashboard.cheques.edit', // Use route name
                'danger',
                'fas fa-money-check-alt',
                ['cheque' => $chequeId]
            );
            
            // Add custom action button data manually to the last created notification for testing
            $notification = \App\Models\User::find(1)->notifications()->latest()->first();
            $data = $notification->data;
            $data['action_livewire'] = 'performAction("'.$notification->id.'", "collectCheque")';
            $data['action_text'] = 'notifications.collect_now'; // Store the translation key
            $notification->data = $data;
            $notification->save();
        } else {
            notifyAdmins(
                'notifications.test_title',
                'notifications.test_message',
                [],
                'system',
                'dashboard.index', // Use a valid route name like dashboard.index instead of '/'
                'info',
                'fas fa-info-circle'
            );
        }

        $this->info("Test $type notification triggered successfully!");
    }
}
