<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class PHPMailerService
{
    protected PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->configure();
    }

    protected function configure(): void
    {
        try {
            // Server settings
            $this->mailer->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
            $this->mailer->isSMTP();                                         // Send using SMTP
            $this->mailer->Host       = config('mail.mailers.smtp.host', '127.0.0.1');      // Set the SMTP server to send through
            $this->mailer->SMTPAuth   = true;                                // Enable SMTP authentication
            $this->mailer->Username   = config('mail.mailers.smtp.username');               // SMTP username
            $this->mailer->Password   = config('mail.mailers.smtp.password');               // SMTP password
            $this->mailer->SMTPSecure = config('mail.mailers.smtp.encryption', 'tls');      // Enable TLS encryption
            $this->mailer->Port       = config('mail.mailers.smtp.port', 587);              // TCP port to connect to
            $this->mailer->CharSet    = 'UTF-8';                            // Set charset for Arabic support

            // Default sender
            $this->mailer->setFrom(config('mail.from.address', 'hello@example.com'), config('mail.from.name', 'Example'));
            
            // Enable debug output in development environment
            if (config('app.debug')) {
                $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER; // Debug output
            }
        } catch (Exception $e) {
            throw new Exception("Error configuring PHPMailer: {$this->mailer->ErrorInfo}");
        }
    }

    /**
     * Send an email
     *
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $body Email body (HTML)
     * @param string $toName Recipient name (optional)
     * @param array $attachments Array of file paths to attach (optional)
     * @return bool Whether the email was sent successfully
     * @throws Exception
     */
    public function send(string $to, string $subject, string $body, string $toName = '', array $attachments = []): bool
    {
        try {
            // Reset recipients
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Recipients
            $this->mailer->addAddress($to, $toName);

            // Attachments
            foreach ($attachments as $attachment) {
                $this->mailer->addAttachment($attachment);
            }

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;

            // Send the email
            return $this->mailer->send();
        } catch (Exception $e) {
            throw new Exception("Error sending email: {$this->mailer->ErrorInfo}");
        }
    }
}