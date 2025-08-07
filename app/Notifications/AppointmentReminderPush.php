<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class AppointmentReminderPush extends Notification
{
    protected $reminderType;

    public function __construct(public $appointment, $reminderType = '20m')
    {
        $this->reminderType = $reminderType;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {

        if ($notifiable->fcm_token) {
            sendDataMessage($notifiable->fcm_token, [
                'title' => 'تذكير بالموعد',
                'message' => $this->getMessage(),
                'appointment_id' => $this->appointment->id,
            ]);
        }
        return [
            'title' => 'تذكير بالموعد',
            'message' => $this->getMessage(),
            'appointment_id' => $this->appointment->id,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->getMessage(),
            'appointment_id' => $this->appointment->id,
        ];
    }
    protected function getMessage()
    {
        if ($this->reminderType === '24h') {
            return 'تذكير: تبقى 24 ساعة على موعد الاستشارة عبر منصة حكماء. يرجى التأكد من جاهزيتك.';
        }

        return 'موعدك بعد 20 دقيقة يرجى التحقق من توفر اتصال جيد بالشبكة';
    }
}
