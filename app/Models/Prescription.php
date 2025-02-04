<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'consultation_id',
        'patient_id',
        'doctor_id',
        'notes',
        'created_by',
        'date',
        'age',
        'blood_pressure',
        'weight',
        'height',
        'diagnosis',
        'treatment',
        'next_appointment_date'
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultantion::class);
    }

    public function details()
    {
        return $this->hasMany(PrescriptionDetail::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

}
