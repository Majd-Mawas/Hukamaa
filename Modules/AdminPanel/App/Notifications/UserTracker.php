<?php

namespace Modules\AdminPanel\App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserTracker extends Notification
{
    use Queueable;

    protected $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'time' => now(),
        ];
    }
}
