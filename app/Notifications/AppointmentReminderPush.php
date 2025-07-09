<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Services\FirebaseService; // assume you have a service

class AppointmentReminderPush extends Notification
{
    public function __construct(public $appointment) {}

    public function via($notifiable)
    {
        return ['database', 'fcm'];
    }

    public function toDatabase($notifiable): array
    {
        return  [
            'title' => 'تذكير بالموعد',
            'body' => 'موعدك بعد 20 دقيقة يرجى التحقق من توفر اتصال جيد بالشبكة',
            'appointment_id' => $this->appointment->id,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'موعدك بعد 20 دقيقة يرجى التحقق من توفر اتصال جيد بالشبكة',
            'appointment_id' => $this->appointment->id,
        ];
    }

    public function toFcm($notifiable)
    {
        if ($notifiable->fcm_token) {
            sendDataMessage($notifiable->fcm_token, [
                'title' => 'تذكير بالموعد',
                'body' => 'موعدك بعد 20 دقيقة يرجى التحقق من توفر اتصال جيد بالشبكة',
                'appointment_id' => $this->appointment->id,
            ]);
        }
    }
}
