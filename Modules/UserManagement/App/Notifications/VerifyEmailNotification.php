<?php

namespace Modules\UserManagement\App\Notifications;

use App\Services\PHPMailerService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\Exception;

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

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        try {
            // Using Laravel's built-in mail message builder
            return (new MailMessage)
                ->subject('التحقق من البريد الإلكتروني')
                ->view(
                    'emails.verify-email',
                    [
                        'name' => $notifiable->name,
                        'code' => $this->code
                    ]
                );
                
            // Note: The custom PHPMailer implementation is removed as we're using Laravel's built-in mail system
        } catch (Exception $e) {
            // Log the error
            logger()->error('Failed to send verification email: ' . $e->getMessage());
            // Still need to return a MailMessage object even in case of error
            return (new MailMessage)
                ->subject('التحقق من البريد الإلكتروني')
                ->line('حدث خطأ أثناء إرسال رمز التحقق. يرجى المحاولة مرة أخرى لاحقًا.');
        }
    }
}
