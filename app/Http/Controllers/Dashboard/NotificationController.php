<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class NotificationController extends Controller
{
    /**
     * Redirect a notification after marking it as read.
     */
    public function redirect($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // Mark as read
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $data = $notification->data;
        $url = '#';

        if (isset($data['action_url'])) {
            $routeParams = $data['action_params'] ?? [];
            
            // Check if it's a valid route name
            if (Route::has($data['action_url'])) {
                return redirect()->route($data['action_url'], $routeParams);
            }
            
            // If it's just a raw URL
            return redirect($data['action_url']);
        }

        // Fallback if no URL is provided
        return redirect()->route('dashboard.notifications');
    }
}
