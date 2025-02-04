<?php

namespace App\Models;

use App\Enum\RoleEnum;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'address',
        'phone_number',
        'otp',
        'otp_sent_at',
        'otp_verified_at',
        'is_active',
        'email_verified_at'
    ];
    /**
     * Get the identifier that will be stored in the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key-value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }


    public function patient(){

        return $this->belongsTo(Patient::class, 'id', 'user_id')
        ->with(['role:id,name'])
        ->where('role_id', RoleEnum::Patient);
    }
    public function nurse(){

        return $this->select('id', 'name', 'email', 'phone_number', 'address', 'is_active', 'role_id')
        ->with(['role:id,name'])
        ->where('role_id', RoleEnum::Nurse);
    }

    public function receptionist(){

        return $this->select('id', 'name', 'email', 'phone_number', 'address', 'is_active', 'role_id')
        ->with(['role:id,name'])
        ->where('role_id', RoleEnum::Receptionist);
    }

}
