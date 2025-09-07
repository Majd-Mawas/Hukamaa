<?php

require __DIR__ . '/vendor/autoload.php';

use App\Services\PHPMailerService;

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test sending an email with PHPMailer
try {
    $mailer = app(PHPMailerService::class);
    
    // Replace with a real email for testing
    $to = 'test@example.com'; 
    $subject = 'PHPMailer Test';
    
    // Create HTML email body with Arabic text support
    $body = '<div dir="rtl" style="font-family: Arial, sans-serif; line-height: 1.6;">';
    $body .= '<h1>اختبار PHPMailer</h1>';
    $body .= '<p>هذا بريد إلكتروني تجريبي تم إرساله باستخدام PHPMailer.</p>';
    $body .= '<p>رمز التحقق: <strong>123456</strong></p>';
    $body .= '</div>';
    
    $name = 'مستخدم اختبار';
    
    echo "Sending email to {$to}...\n";
    echo "Using SMTP settings from config:\n";
    echo "- Host: " . config('mail.mailers.smtp.host') . "\n";
    echo "- Port: " . config('mail.mailers.smtp.port') . "\n";
    echo "- Username: " . config('mail.mailers.smtp.username') . "\n";
    echo "- From: " . config('mail.from.address') . "\n";
    
    $result = $mailer->send($to, $subject, $body, $name);
    
    if ($result) {
        echo "\nEmail sent successfully!\n";
    } else {
        echo "\nFailed to send email.\n";
    }
} catch (Exception $e) {
    echo "\nError: " . $e->getMessage() . "\n";
}