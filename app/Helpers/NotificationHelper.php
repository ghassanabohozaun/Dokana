<?php

use App\Models\User;
use App\Notifications\SystemNotification;

if (!function_exists('notifyAdmins')) {
    /**
     * Send a notification to all Admins (or super users).
     *
     * @param string $titleKey
     * @param string $messageKey
     * @param array $params
     * @param string $category
     * @param string|null $url
     * @param string $level
     * @param string $icon
     * @param array $actionParams
     */
    function notifyAdmins($titleKey, $messageKey, $params = [], $category = 'system', $url = null, $level = 'info', $icon = 'la la-bell', $actionParams = [])
    {
        // For now, we assume the Admin is the user with ID 1, or users with a specific role.
        // If you use Spatie Roles, you can do: $admins = User::role('admin')->get();
        // Since the current requirement states there is only one user (the manager), we fetch User 1.
        $admins = User::where('id', 1)->get(); // Alternatively, you can fetch all users if there's only one.
        
        if ($admins->isEmpty()) {
            $admins = User::all(); // Fallback if ID 1 is deleted, just notify everyone in this simple phase.
        }

        $notification = new SystemNotification($titleKey, $messageKey, $params, $category, $url, $level, $icon, $actionParams);

        foreach ($admins as $admin) {
            $admin->notify($notification);
        }
    }
}
