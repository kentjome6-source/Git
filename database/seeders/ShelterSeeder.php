<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shelter;

class ShelterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shelters = [
            // Add your desired shelter data here
            // For now, we're removing the sample locations as requested
        ];

        foreach ($shelters as $shelter) {
            Shelter::create($shelter);
        }
    }
}