<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    public $titleKey;
    public $messageKey;
    public $params;
    public $category;
    public $actionUrl;
    public $actionParams;
    public $level;
    public $icon;

    /**
     * Create a new notification instance.
     */
    public function __construct($titleKey, $messageKey, $params = [], $category = 'system', $actionUrl = null, $level = 'info', $icon = 'la la-bell', $actionParams = [])
    {
        $this->titleKey = $titleKey;
        $this->messageKey = $messageKey;
        $this->params = $params;
        $this->category = $category;
        $this->actionUrl = $actionUrl;
        $this->actionParams = $actionParams;
        $this->level = $level;
        $this->icon = $icon;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for the database.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title_key'   => $this->titleKey,
            'message_key' => $this->messageKey,
            'params'      => $this->params,
            'category'    => $this->category,
            'action_url'  => $this->actionUrl ?? 'javascript:void(0)',
            'action_params' => $this->actionParams,
            'level'       => $this->level,
            'icon'        => $this->icon,
        ];
    }
}
