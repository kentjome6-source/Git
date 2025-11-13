<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GroomingService;
use App\Models\Shelter;

class GroomingServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the Boss Jack Pet Grooming Services shelter
        $shelter = Shelter::where('name', 'Boss Jack Pet Grooming Services')->first();
        
        if ($shelter) {
            $groomingServices = [
                [
                    'shelter_id' => $shelter->id,
                    'name' => 'Basic Grooming Package',
                    'description' => 'Includes bath, brush, nail trim, and ear cleaning',
                    'price' => 350.00,
                    'duration' => 60,
                    'is_available' => true
                ],
                [
                    'shelter_id' => $shelter->id,
                    'name' => 'Full Grooming Service',
                    'description' => 'Complete grooming package including hair cut, styling, and aromatherapy',
                    'price' => 650.00,
                    'duration' => 120,
                    'is_available' => true
                ],
                [
                    'shelter_id' => $shelter->id,
                    'name' => 'Deluxe Spa Treatment',
                    'description' => 'Premium grooming with oatmeal bath, blueberry facial, and massage',
                    'price' => 850.00,
                    'duration' => 150,
                    'is_available' => true
                ],
                [
                    'shelter_id' => $shelter->id,
                    'name' => 'Nail Trimming Only',
                    'description' => 'Professional nail trimming and filing service',
                    'price' => 150.00,
                    'duration' => 30,
                    'is_available' => true
                ],
                [
                    'shelter_id' => $shelter->id,
                    'name' => 'Bath & Brush',
                    'description' => 'Thorough bath with blow dry and brushing',
                    'price' => 250.00,
                    'duration' => 45,
                    'is_available' => true
                ]
            ];
            
            foreach ($groomingServices as $service) {
                GroomingService::create($service);
            }
        }
    }
}