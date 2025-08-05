<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\UserManagement\App\Models\User;

class SendPushNotification extends Command
{
    protected $signature = 'notification:send-push {user_id} {title} {message} {--data=*}';
    protected $description = 'Send a push notification to a specific user';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $title = $this->argument('title');
        $message = $this->argument('message');
        $data = $this->option('data');

        // Convert data option to associative array
        $dataArray = [];
        foreach ($data as $item) {
            if (strpos($item, '=') !== false) {
                list($key, $value) = explode('=', $item, 2);
                $dataArray[$key] = $value;
            }
        }

        // Add title and message to data array
        $dataArray['title'] = $title;
        $dataArray['message'] = $message;
        $dataArray['body'] = $message;

        // Find the user
        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return 1;
        }

        if (!$user->fcm_token) {
            $this->error("User with ID {$userId} does not have an FCM token.");
            return 1;
        }

        try {
            // Send the push notification
            sendDataMessage($user->fcm_token, $dataArray);

            // Also store in database
            $user->notify(new \App\Notifications\SystemNotification(
                $title,
                $message,
                $dataArray
            ));

            $this->info("Push notification sent to user {$userId} successfully.");
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to send push notification: {$e->getMessage()}");
            return 1;
        }
    }
}
