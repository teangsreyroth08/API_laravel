<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch role IDs to ensure they exist
        $adminRole = Role::where('name', 'admin')->first();
        $doctorRole = Role::where('name', 'doctor')->first();
        $receptionistRole = Role::where('name', 'receptionist')->first();
        $nurseRole = Role::where('name', 'nurse')->first();
        $patientRole = Role::where('name', 'patient')->first();

        if (!$adminRole || !$doctorRole || !$receptionistRole || !$nurseRole || !$patientRole) {
            throw new \Exception('Roles not found. Run `php artisan db:seed --class=RoleSeeder` first.');
        }

        User::insert([
            [
                'name' => 'Admin User',
                'email' => 'lifelessclinic@gmail.com',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'phone_number' => '0123456789',
                'address' => '123 ITC Techno',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ousa',
                'email' => 'ousalifelessclinic@gmail.com',
                'password' => Hash::make('ousa123'),
                'role_id' => $doctorRole->id,
                'phone_number' => '0123456789',
                'address' => '123 ITC Techno',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meng',
                'email' => 'menglifelessclinic@gmail.com',
                'password' => Hash::make('meng123'),
                'role_id' => $receptionistRole->id,
                'phone_number' => '0123456789',
                'address' => '123 ITC Techno',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pheanith',
                'email' => 'pheanithlifelessclinic@gmail.com',
                'password' => Hash::make('pheanith123'),
                'role_id' => $nurseRole->id,
                'phone_number' => '0123456789',
                'address' => '123 ITC Techno',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nak',
                'email' => 'naklifelessclinic@gmail.com',
                'password' => Hash::make('nak123'),
                'role_id' => $patientRole->id,
                'phone_number' => '0123456789',
                'address' => '123 ITC Techno',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}