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
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $senderName = $this->message->sender->name;
        $receiverName = $this->message->receiver->name;

        $title = 'رسالة جديدة';

        if ($notifiable->fcm_token) {
            sendDataMessage($notifiable->fcm_token, [
                'title' => $title,
                'body' => $this->message->message,
                'appointment_id' => (string) $this->message->appointment_id,
                'sender_name' => $senderName,
                'receiver_name' => $receiverName
            ]);
        }

        return [
            'title' => $title,
            'body' => $this->message->message,
            'appointment_id' => (string) $this->message->appointment_id,
            'sender_name' => $senderName,
            'receiver_name' => $receiverName
        ];
    }

    // public function toFcm($notifiable)
    // {
    //     if ($notifiable->fcm_token) {
    //         sendDataMessage($notifiable->fcm_token, [
    //             'title' => 'رسالة جديدة',
    //             'body' => $this->message->message,
    //             'appointment_id' => $this->message->appointment_id,
    //         ]);
    //     }
    // }
}
