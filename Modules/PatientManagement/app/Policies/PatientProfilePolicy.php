<?php

namespace Modules\PatientManagement\App\Policies;

use App\Models\User;
use Modules\PatientManagement\App\Models\PatientProfile;

class PatientProfilePolicy
{
    public function update(User $user, PatientProfile $profile = null): bool
    {
        return $user->role === 'patient';
    }
}
