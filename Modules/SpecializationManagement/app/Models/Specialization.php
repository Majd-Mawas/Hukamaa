<?php

namespace Modules\SpecializationManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
