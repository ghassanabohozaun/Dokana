<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class HeaderNotifications extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $lastNotificationId = null;
    public $activeTab = 'all'; // Added tab state

    public function mount()
    {
        $this->loadNotifications();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $query = $user->unreadNotifications();
            
            // Filter by tab if not 'all'
            if ($this->activeTab !== 'all') {
                $query->where('data->category', $this->activeTab);
            }

            $this->unreadCount = $query->count();
            $this->notifications = $query->take(10)->get();
            
            $latest = $this->notifications->first();
            if ($latest) {
                $this->lastNotificationId = $latest->id;
            }
        }
    }

    public function checkNotifications()
    {
        if (!Auth::check()) return;

        $user = Auth::user();
        $latest = $user->unreadNotifications()->latest()->first();
        $currentCount = $user->unreadNotifications()->count();

        // Check if there is a newly arrived notification
        if ($latest && $latest->id !== $this->lastNotificationId) {
            $this->lastNotificationId = $latest->id;
            $this->loadNotifications();

            // Translate the new notification using the keys
            $title = __($latest->data['title_key'] ?? 'notifications.system_alert', $latest->data['params'] ?? []);
            $message = __($latest->data['message_key'] ?? '', $latest->data['params'] ?? []);

            $this->dispatch('new-notification-received', [
                'title' => $title,
                'message' => $message,
                'level' => $latest->data['level'] ?? 'info'
            ]);
        } elseif ($currentCount !== $this->unreadCount) {
            // Count changed (maybe marked as read elsewhere), just reload
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
            $this->loadNotifications();
        }
    }

    public function performAction($notificationId, $action)
    {
        // Example action router
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            if ($action === 'collectCheque') {
                // Here you would find the cheque and mark it as collected
                // Cheque::find($notification->data['params']['cheque_id'])->update(['status' => 'collected']);
                session()->flash('success', 'تم تحصيل الشيك بنجاح!');
            }
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.notifications.header-notifications');
    }
}
