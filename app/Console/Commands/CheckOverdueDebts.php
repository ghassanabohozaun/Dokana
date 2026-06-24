<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StoreCustomer;
use App\Models\User;
use App\Notifications\SystemNotification;

class CheckOverdueDebts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-overdue-debts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit customer debt ages, alert store admins when debts exceed 10 days, and run SMS triggers placeholder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all customers with balance > 0 and eager load transactions
        $customers = StoreCustomer::with('transactions')->where('balance', '>', 0)->get();

        $notifiedCount = 0;

        foreach ($customers as $customer) {
            $debtAge = $customer->debt_age;

            // Check if debt age is greater than 10 days
            if ($debtAge !== null && $debtAge > 10) {
                $storeId = $customer->store_id;

                // Fetch store employees/admins plus Super Admin (User with ID 1)
                $usersToNotify = User::where('store_id', $storeId)
                    ->orWhere('id', 1)
                    ->get()
                    ->unique('id');

                $notification = new SystemNotification(
                    'notifications.overdue_debt_title',
                    'notifications.overdue_debt_body',
                    [
                        'customer_name' => $customer->name,
                        'days' => $debtAge,
                        'balance' => number_format($customer->balance, 1)
                    ],
                    'debts',
                    route('dashboard.store-customers.index') . '?keyword=' . urlencode($customer->name),
                    'danger',
                    'fas fa-clock'
                );

                foreach ($usersToNotify as $user) {
                    $user->notify($notification);
                }

                // =========================================================================
                // SMS GATEWAY INTEGRATION PLACEHOLDER
                // =========================================================================
                // You can integrate SMS providers here (e.g. Twilio, Jawwal SMS, etc.):
                //
                // if ($customer->phone) {
                //     $message = "عزيزي العميل {$customer->name}، يرجى العلم بوجود مبالغ مستحقة تبلغ {$customer->balance} شيكل منذ {$debtAge} يوماً. يرجى السداد في أقرب وقت.";
                //     // $smsService->send($customer->phone, $message);
                // }
                // =========================================================================
                $this->info("SMS Trigger placeholder for customer {$customer->name} (phone: " . ($customer->phone ?? 'N/A') . ", debt age: {$debtAge} days)");

                $notifiedCount++;
            }
        }

        $this->info("Audit completed. Generated notifications for {$notifiedCount} overdue customers.");
    }
}
