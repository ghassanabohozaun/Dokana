<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class NotificationCenter extends Component
{
    use WithPagination;

    // Optional: use bootstrap pagination
    protected $paginationTheme = 'bootstrap';

    public $activeTab = 'all';
    public $selectedNotifications = [];
    public $selectAll = false;

    public function updatedSelectAll($value)
    {
        if ($value) {
            $query = Auth::user()->notifications();
            if ($this->activeTab !== 'all') {
                $query->where('data->category', $this->activeTab);
            }
            $this->selectedNotifications = $query->paginate(15)->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedNotifications = [];
        }
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->selectedNotifications = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function deleteNotification($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->delete();
        }
    }

    public function deleteAllNotifications()
    {
        Auth::user()->notifications()->delete();
        $this->selectedNotifications = [];
        $this->selectAll = false;
    }

    public function deleteSelected()
    {
        if (!empty($this->selectedNotifications)) {
            Auth::user()->notifications()->whereIn('id', $this->selectedNotifications)->delete();
            $this->selectedNotifications = [];
            $this->selectAll = false;
        }
    }

    public function render()
    {
        $query = Auth::user()->notifications();

        if ($this->activeTab !== 'all') {
            $query->where('data->category', $this->activeTab);
        }

        return view('livewire.notification-center', [
            'notifications' => $query->paginate(15)
        ])->layout('layouts.dashboard.app');
    }
}
