<?php

namespace Modules\UserManagement\Services;

use Modules\UserManagement\Models\User;
use Modules\UserManagement\Notifications\VerifyEmailNotification;

class EmailVerificationService
{
    public function sendVerificationEmail(User $user): void
    {
        $user->notify(new VerifyEmailNotification());
    }

    public function verifyEmail(User $user, string $hash): bool
    {
        if (!hash_equals(
            (string) $hash,
            sha1($user->getEmailForVerification())
        )) {
            return false;
        }

        if ($user->hasVerifiedEmail()) {
            return false;
        }

        return $user->markEmailAsVerified();
    }

    public function resendVerificationEmail(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        $this->sendVerificationEmail($user);
    }
}
