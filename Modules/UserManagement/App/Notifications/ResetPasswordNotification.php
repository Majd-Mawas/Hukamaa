<?php

namespace Modules\UserManagement\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Password Verification Code')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->line('Your verification code is: ' . $this->code)
            ->line('This verification code will expire in 24 hours.')
            ->line('If you did not request a password reset, no further action is required.');
    }
}
