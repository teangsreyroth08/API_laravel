<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultantion extends Model
{
    protected $fillable = ['appointment_id', 'notes'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }
}
