<?php

namespace Modules\PatientManagement\App\Policies;

use Modules\PatientManagement\App\Models\PatientProfile;
use Modules\UserManagement\App\Models\User;

class PatientProfilePolicy
{
    public function update(User $user, PatientProfile $profile = null): bool
    {
        return $user->role === 'patient';
    }
}
