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
     * @return void
     */
    public function toMail($notifiable): void
    {
        try {
            $mailer = app(PHPMailerService::class);
            
            $subject = 'التحقق من البريد الإلكتروني';
            
            // Create HTML email body
            $body = '<div dir="rtl" style="font-family: Arial, sans-serif; line-height: 1.6;">';
            $body .= '<h2>مرحباً ' . $notifiable->name . '!</h2>';
            $body .= '<p>شكراً لتسجيلك. يرجى استخدام الرمز التالي للتحقق من بريدك الإلكتروني:</p>';
            $body .= '<p style="font-size: 24px; font-weight: bold; text-align: center; padding: 10px; background-color: #f5f5f5; border-radius: 5px;">' . $this->code . '</p>';
            $body .= '<p>سينتهي صلاحية هذا الرمز خلال 60 دقيقة.</p>';
            $body .= '<p>إذا لم تقم بإنشاء حساب، فلا يلزم اتخاذ أي إجراء آخر.</p>';
            $body .= '</div>';
            
            // Send email using PHPMailer
            $mailer->send(
                $notifiable->email,
                $subject,
                $body,
                $notifiable->name
            );
        } catch (Exception $e) {
            // Log the error
            logger()->error('Failed to send verification email: ' . $e->getMessage());
        }
    }
}
