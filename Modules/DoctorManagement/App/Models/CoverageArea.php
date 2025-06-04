<?php

namespace Modules\DoctorManagement\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\DoctorManagement\App\Models\DoctorProfile;

class CoverageArea extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function doctors()
    {
        return $this->belongsToMany(DoctorProfile::class, 'coverage_area_doctor');
    }
}
