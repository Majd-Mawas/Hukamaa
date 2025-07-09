<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentReminderEmail extends Notification
{
    public function __construct(public $appointment) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تذكير بموعد الاستشارة')
            ->line('تذكير: تبقى 24 ساعة على موعد الاستشارة عبر منصة حكماء. يرجى التأكد من جاهزيتك.');
    }
}
