<?php

namespace Modules\SpecializationManagement\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\DoctorManagement\App\Models\DoctorProfile;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_name',
        'specialization_name',
    ];

    public function doctorProfiles()
    {
        return $this->hasMany(DoctorProfile::class);
    }
}
