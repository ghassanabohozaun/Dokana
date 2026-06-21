<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contract;
use App\Models\Cheque;
use Carbon\Carbon;

class DailySystemDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:daily-digest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily summary notification of expiring contracts and due cheques';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Calculate expiring contracts within 60 days
        $expiringContractsCount = Contract::whereBetween('end_date', [Carbon::today(), Carbon::today()->addDays(60)])
                                          ->where('status', '!=', 'expired') // Assuming you have a status field
                                          ->count();

        // Calculate due cheques within 7 days (including pending and held)
        $dueChequesCount = Cheque::whereBetween('due_date', [Carbon::today(), Carbon::today()->addDays(7)])
                                 ->whereIn('status', ['pending', 'held'])
                                 ->count();

        if ($expiringContractsCount > 0 || $dueChequesCount > 0) {
            notifyAdmins(
                'notifications.digest_title',
                'notifications.digest_msg',
                ['contracts' => $expiringContractsCount, 'cheques' => $dueChequesCount],
                'system',
                '/dashboard/notifications',
                'warning',
                'la la-coffee'
            );
            $this->info('Daily digest sent!');
        } else {
            $this->info('Nothing to report today.');
        }
    }
}
