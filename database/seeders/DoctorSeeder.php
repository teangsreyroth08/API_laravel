<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Specialization;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure users and specializations exist before seeding
        $users = User::pluck('id')->toArray();
        $specializations = Specialization::pluck('id')->toArray();

        if (empty($users) || empty($specializations)) {
            $this->command->info('Skipping DoctorSeeder: No users or specializations found.');
            return;
        }

        DB::table('doctors')->insert([
            [
                'user_id' => $users[0] ?? null, // Get first available user
                'specialization_id' => $specializations[0] ?? null, // Get first available specialization
                'contact' => '0123456789',
                'availability' => true,
            ],
            [
                'user_id' => $users[1] ?? null,
                'specialization_id' => $specializations[1] ?? null,
                'contact' => '0987654321',
                'availability' => false,
            ],
            [
                'user_id' => $users[2] ?? null,
                'specialization_id' => $specializations[0] ?? null, 
                'contact' => '0123456789',
                'availability' => true,
            ],
        ]);
    }
}