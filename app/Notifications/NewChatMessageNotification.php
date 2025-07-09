<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewChatMessageNotification extends Notification
{

    public function __construct(public $message, public $appointment) {}

    public function via($notifiable)
    {
        return ['database', 'fcm'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'رسالة جديدة',
            'body' => $this->message->message,
            'appointment_id' => $this->message->appointment_id,
        ];
    }

    public function toFcm($notifiable)
    {
        if ($notifiable->fcm_token) {
            sendDataMessage($notifiable->fcm_token, [
                'title' => 'رسالة جديدة',
                'body' => $this->message->message,
                'appointment_id' => $this->message->appointment_id,
            ]);
        }
    }
}
