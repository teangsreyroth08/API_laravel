<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialization;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specailist = [
            [
                'name' => 'General Practitioner',
                'description' => 'Provides primary healthcare and treats common medical conditions.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cardiologist',
                'description' => 'Specializes in diagnosing and treating heart diseases.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Neurologist',
                'description' => 'Focuses on disorders of the brain, spinal cord, and nervous system.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pediatrician',
                'description' => 'Provides medical care for infants, children, and adolescents.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Specialization::insert($specailist);

    }
}
