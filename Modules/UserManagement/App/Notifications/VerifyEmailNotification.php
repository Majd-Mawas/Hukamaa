<?php

namespace Modules\UserManagement\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    protected string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
        $this->onQueue(null); // Disable queue
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify Email Address')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for registering. Please use the following code to verify your email address:')
            ->line('**' . $this->code . '**')
            ->line('This code will expire in 60 minutes.')
            ->line('If you did not create an account, no further action is required.');
    }
}
