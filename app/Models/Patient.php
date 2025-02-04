<?php

namespace App\Models;

use App\Enum\RoleEnum;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id', 'patient_id', 'name', 'age', 'gender', 'contact', 'id_card', 'allergies', 'created_by',
        'date_of_birth', 'email', 'passport', 'address', 'emergency_contact_name', 'emergency_contact_phone',
        'blood_type', 'chronic_conditions', 'current_medications', 'previous_surgeries', 'family_medical_history',
        'preferred_doctor', 'insurance_provider', 'insurance_policy_number', 'billing_address'
    ];



    public function user(){

        return $this->belongsTo(User::class, 'user_id')
        ->select('id', 'name', 'email', 'phone_number', 'address', 'is_active', 'role_id')
        ->with(['role:id,name'])
        ->where('role_id', RoleEnum::Patient);
    }

    public function medicalRecords(){

        return $this->hasMany(Prescription::class, 'patient_id');
    }
}
