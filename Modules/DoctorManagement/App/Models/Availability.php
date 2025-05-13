<?php

namespace Modules\DoctorManagement\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'weekday',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_id');
    }
}
